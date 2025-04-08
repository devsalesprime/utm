<?php
session_start();
require 'db.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['username']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: index.php');
    exit();
}

// Aprovar usuário
if (isset($_POST['approve'])) {
    $userId = $_POST['user_id'];
    $stmt = $pdo->prepare("UPDATE users SET is_approved = TRUE WHERE id = ?");
    $stmt->execute([$userId]);
}

// Rejeitar usuário
if (isset($_POST['reject'])) {
    $userId = $_POST['user_id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);
}

// Buscar usuários pendentes
$stmt = $pdo->query("SELECT id, name, email, created_at FROM users WHERE is_approved = FALSE");
$pendingUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Aprovação de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col">
                <h2>Painel Administrativo - Aprovação de Usuários</h2>
            </div>
            <div class="col text-end">
                <a href="index.php" class="btn btn-secondary">Voltar</a>
            </div>
        </div>

        <?php if (empty($pendingUsers)): ?>
            <div class="alert alert-info">Não há usuários pendentes de aprovação.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Data de Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingUsers as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" name="approve" class="btn btn-success btn-sm">Aprovar</button>
                                        <button type="submit" name="reject" class="btn btn-danger btn-sm">Rejeitar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>