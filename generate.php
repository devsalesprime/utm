<?php
session_start();
require 'db.php';

// Verifique se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Função para gerar código curto
function generateShortCode($length = 6)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $code;
}

// Função para construir a URL com parâmetros UTM
function buildUtmUrl($baseUrl, $utmParams)
{
    $utmUrl = $baseUrl;
    $firstParam = true;

    foreach ($utmParams as $key => $value) {
        if (!empty($value)) {
            $utmUrl .= ($firstParam ? '?' : '&') . $key . "=" . rawurlencode($value);
            $firstParam = false;
        }
    }

    return $utmUrl;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $website_url = trim($_POST['website_url']);
    $utm_campaign = trim($_POST['utm_campaign']);
    $utm_source = trim($_POST['utm_source']);
    $utm_medium = trim($_POST['utm_medium']);
    $utm_term = trim($_POST['utm_term']);
    $custom_name = trim($_POST['custom_name']);
    $user_id = $_SESSION['user_id'];

    // Validar URL base
    if (!filter_var($website_url, FILTER_VALIDATE_URL)) {
        $_SESSION['error'] = "URL inválida. Por favor insira uma URL válida.";
        header('Location: index.php');
        exit();
    }

    // Construir a URL com UTM
    $utmParams = [
        'utm_campaign' => $utm_campaign,
        'utm_source' => $utm_source,
        'utm_medium' => $utm_medium,
        'utm_term' => $utm_term,
    ];
    $utm_url = buildUtmUrl($website_url, $utmParams);

    try {
        // Criar tabela se não existir
        $pdo->exec("CREATE TABLE IF NOT EXISTS urls (
            id INT AUTO_INCREMENT PRIMARY KEY,
            original_url TEXT NOT NULL,
            long_url TEXT NOT NULL,
            shortened_url VARCHAR(255) NOT NULL UNIQUE,
            user_id INT NOT NULL,
            generation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Verificar se o nome personalizado já existe
        if (!empty($custom_name)) {
            $stmt = $pdo->prepare("SELECT id FROM urls WHERE shortened_url = ?");
            $stmt->execute([$custom_name]);
            if ($stmt->rowCount() > 0) {
                $_SESSION['error'] = "Nome personalizado já está em uso. Por favor escolha outro nome.";
                header('Location: index.php');
                exit();
            }
            $short_code = $custom_name;
        } else {
            // Gerar código curto único
            do {
                $short_code = generateShortCode();
                $stmt = $pdo->prepare("SELECT id FROM urls WHERE shortened_url = ?");
                $stmt->execute([$short_code]);
            } while ($stmt->rowCount() > 0);
        }

        // Inserir na base de dados
        $stmt = $pdo->prepare("INSERT INTO urls (original_url, long_url, shortened_url, user_id) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$website_url, $utm_url, $short_code, $user_id])) {
            $_SESSION['success'] = "URL encurtada com sucesso! Código: " . $short_code;
            header("Location: index.php");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Erro ao processar URL: " . $e->getMessage()); // Log do erro
        $_SESSION['error'] = "Erro ao processar URL. Por favor, tente novamente.";
        header('Location: index.php');
        exit();
    }
}