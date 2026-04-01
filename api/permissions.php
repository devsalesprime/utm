<?php
/**
 * API - Gerenciamento de Permissões de Usuários
 * Versão: 2.5
 * Apenas admins podem acessar
 */

session_start();
require __DIR__ . '/../includes/db.php';

// Verificar autenticação e admin
if (!isset($_SESSION['username']) || !isset($_SESSION['is_admin'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Não autenticado']));
}

if (!$_SESSION['is_admin']) {
    http_response_code(403);
    die(json_encode(['error' => 'Acesso negado']));
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$currentAdmin = $_SESSION['username'];

try {
    switch ($action) {
        // LIST (GET)
        case 'list':
            $stmt = $pdo->query(
                "SELECT udp.*, u.name as user_name, d.name as domain_name, d.domain_url 
                 FROM user_domain_permissions udp
                 LEFT JOIN users u ON udp.user_id = u.id
                 JOIN domains d ON udp.domain_id = d.id
                 ORDER BY u.name, d.name"
            );
            $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $permissions]);
            break;

        // CREATE
        case 'create_permission':
            $username = trim($_POST['username'] ?? '');
            $domain_id = intval($_POST['domain_id'] ?? 0);
            $can_create = isset($_POST['can_create']) ? 1 : 0;
            $can_edit = isset($_POST['can_edit']) ? 1 : 0;
            $can_delete = isset($_POST['can_delete']) ? 1 : 0;

            if (!$username || !$domain_id) {
                throw new Exception('Usuário e domínio são obrigatórios');
            }

            // Verificar se domínio existe
            $stmt = $pdo->prepare("SELECT id FROM domains WHERE id = ?");
            $stmt->execute([$domain_id]);
            if (!$stmt->fetch()) {
                throw new Exception('Domínio não encontrado');
            }

            // Inserir ou atualizar permissão
            $stmt = $pdo->prepare(
                "INSERT INTO user_domain_permissions (username, domain_id, can_create, can_edit, can_delete, assigned_by) 
                 VALUES (?, ?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE 
                    can_create = ?, can_edit = ?, can_delete = ?, assigned_by = ?"
            );
            $stmt->execute([
                $username, $domain_id, $can_create, $can_edit, $can_delete, $currentAdmin,
                $can_create, $can_edit, $can_delete, $currentAdmin
            ]);

            echo json_encode(['success' => true, 'message' => 'Permissão atribuída com sucesso']);
            break;

        // UPDATE
        case 'update_permission':
            $permission_id = intval($_POST['permission_id'] ?? 0);
            $can_create = isset($_POST['can_create']) ? 1 : 0;
            $can_edit = isset($_POST['can_edit']) ? 1 : 0;
            $can_delete = isset($_POST['can_delete']) ? 1 : 0;

            if (!$permission_id) {
                throw new Exception('ID da permissão inválido');
            }

            $stmt = $pdo->prepare(
                "UPDATE user_domain_permissions 
                 SET can_create = ?, can_edit = ?, can_delete = ?, assigned_by = ? 
                 WHERE id = ?"
            );
            $stmt->execute([$can_create, $can_edit, $can_delete, $currentAdmin, $permission_id]);

            echo json_encode(['success' => true, 'message' => 'Permissão atualizada com sucesso']);
            break;

        // DELETE
        case 'delete_permission':
            $permission_id = intval($_POST['permission_id'] ?? 0);

            if (!$permission_id) {
                throw new Exception('ID da permissão inválido');
            }

            $stmt = $pdo->prepare("DELETE FROM user_domain_permissions WHERE id = ?");
            $stmt->execute([$permission_id]);

            echo json_encode(['success' => true, 'message' => 'Permissão removida com sucesso']);
            break;

        // GET ALL PERMISSIONS
        case 'get_user_permissions':
            $username = $_POST['username'] ?? '';

            if (!$username) {
                throw new Exception('Usuário obrigatório');
            }

            $stmt = $pdo->prepare(
                "SELECT udp.*, d.name as domain_name, d.domain_url 
                 FROM user_domain_permissions udp
                 JOIN domains d ON udp.domain_id = d.id
                 WHERE udp.username = ?"
            );
            $stmt->execute([$username]);
            $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $permissions]);
            break;

        default:
            throw new Exception('Ação inválida');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
