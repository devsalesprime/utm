<?php
session_start();
require 'db.php';

// Verifique se o usuário está autenticado
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

function generateShortCode()
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $code;
}

/**
 * Função para sanitizar o nome personalizado
 * Remove espaços, caracteres especiais e acentos
 * Mantém apenas letras, números, hífens e underscores
 */
function sanitizeCustomName($name) {
    if (empty($name)) {
        return '';
    }
    
    // Remove espaços no início e fim
    $name = trim($name);
    
    // Converte para minúsculas
    $name = strtolower($name);
    
    // Remove acentos e caracteres especiais
    $name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);
    
    // Substitui espaços por hífens
    $name = str_replace(' ', '-', $name);
    
    // Remove caracteres que não sejam letras, números, hífens ou underscores
    $name = preg_replace('/[^a-z0-9\-_]/', '', $name);
    
    // Remove múltiplos hífens consecutivos
    $name = preg_replace('/-+/', '-', $name);
    
    // Remove hífens no início e fim
    $name = trim($name, '-_');
    
    // Limita o tamanho a 50 caracteres
    $name = substr($name, 0, 50);
    
    return $name;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $website_url = trim($_POST['website_url']);
    $profile = trim($_POST['profile']);
    $utm_content = trim($_POST['utm_content']);
    $utm_campaign = trim($_POST['utm_campaign']);
    $utm_source = trim($_POST['utm_source']);
    $utm_medium = trim($_POST['utm_medium']);
    $utm_term = trim($_POST['utm_term']);
    $custom_name = trim($_POST['custom_name']);
    $username = $_SESSION['username'];
    
    // Tratar o campo "Outros" se necessário
    if ($utm_campaign === 'Outro' && isset($_POST['utm_campaign_other'])) {
        $utm_campaign = trim($_POST['utm_campaign_other']);
    }

    // Validar URL base
    if (!filter_var($website_url, FILTER_VALIDATE_URL)) {
        die("URL inválida. Por favor insira uma URL válida.");
    }

    // Garantir que não tenha espaços extras e converter para maiúsculo
    $utm_campaign = strtoupper(str_replace(' ', '_', $utm_campaign));
    $utm_term = strtoupper($utm_term);

    // Construir a URL com UTM
    $utm_url = $website_url;

    // Adicionar parâmetros UTM
    $query_params = [
        'utm_campaign' => $utm_campaign,
        'utm_source' => $utm_source,
        'utm_medium' => $utm_medium,
        'utm_content' => $utm_content,
        'utm_term' => $utm_term
    ];
    
    // Construir a query string
    $query_string = http_build_query($query_params);
    $utm_url .= (strpos($utm_url, '?') === false ? '?' : '&') . $query_string;
    
    // Corrigir caracteres codificados
    $utm_url = str_replace(['%5B', '%5D'], ['[', ']'], $utm_url);

    try {
        // Criar tabela se não existir
        $pdo->exec("CREATE TABLE IF NOT EXISTS urls (
            id INT AUTO_INCREMENT PRIMARY KEY,
            original_url TEXT NOT NULL,
            long_url TEXT NOT NULL,
            shortened_url VARCHAR(255) NOT NULL UNIQUE,
            username VARCHAR(255) NOT NULL,
            comment TEXT,
            generation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");        
        
        // Sanitizar nome personalizado
        $sanitized_custom_name = sanitizeCustomName($custom_name);
        
        // Verificar nome personalizado
        if (!empty($sanitized_custom_name)) {
            $stmt = $pdo->prepare("SELECT id FROM urls WHERE shortened_url = ?");
            $stmt->execute([$sanitized_custom_name]);
            if ($stmt->rowCount() > 0) {
                echo "<script>alert('Nome personalizado já está em uso. Por favor escolha outro nome.'); window.history.back();</script>";
                exit;
            }
            $short_code = $sanitized_custom_name;
        } else {
            // Gerar código curto único
            do {
                $short_code = generateShortCode();
                $stmt = $pdo->prepare("SELECT id FROM urls WHERE shortened_url = ?");
                $stmt->execute([$short_code]);
            } while ($stmt->rowCount() > 0);
        }

        // Inserir na base de dados
        $stmt = $pdo->prepare("INSERT INTO urls (original_url, long_url, shortened_url, username, comment) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$website_url, $utm_url, $short_code, $username, $_POST['utm_comment'] ?? null])) {
            // Se o nome foi sanitizado, armazenar mensagem na sessão
            if (!empty($custom_name) && $sanitized_custom_name !== $custom_name) {
                $_SESSION['utm_alert_message'] = 'O nome personalizado foi ajustado para: "' . $sanitized_custom_name . '"\n\nCaracteres especiais, espaços e acentos foram removidos para garantir compatibilidade.';
            }
            
            // Redirecionar para index.php
            header("Location: index.php?success=1&code=" . $short_code);
            exit();
        } else {
            die("Erro ao inserir UTM no banco de dados.");
        }
    } catch (PDOException $e) {
        die("Erro ao processar URL: " . $e->getMessage());
    }
} else {
    // Se não for POST, redirecionar para index
    header("Location: index.php");
    exit();
}
?>

