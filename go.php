<?php
session_start();
require 'db.php';

// Função para validar o código curto
function isValidCode($code)
{
    return preg_match('/^[a-zA-Z0-9]+$/', $code);
}

// Função para registrar o clique
function incrementClick($pdo, $code)
{
    $stmt = $pdo->prepare("UPDATE urls SET clicks = COALESCE(clicks, 0) + 1 WHERE shortened_url = ?");
    $stmt->execute([$code]);
}

// Função para redirecionar de forma segura
function safeRedirect($url)
{
    // Valida a URL antes de redirecionar
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        // Preserva colchetes na URL
        $url = str_replace(['%5B', '%5D'], ['[', ']'], $url);
        header("Location: $url", true, 302);
        exit;
    } else {
        $_SESSION['error'] = "URL inválida.";
        header("Location: index.php");
        exit;
    }
}

// Obter o código e validar
$code = isset($_GET['code']) ? trim($_GET['code']) : '';
if (!isValidCode($code)) {
    $_SESSION['error'] = "Código inválido.";
    header("Location: index.php");
    exit;
}

// Buscar a URL longa associada
try {
    $stmt = $pdo->prepare("SELECT long_url FROM urls WHERE shortened_url = ?");
    $stmt->execute([$code]);
    $url = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($url) {
        // Incrementar cliques
        incrementClick($pdo, $code);

        // Redirecionar para a URL longa
        safeRedirect($url['long_url']);
    } else {
        $_SESSION['error'] = "Código não encontrado.";
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    // Log do erro (recomendado para produção)
    error_log("Erro no redirect.php: " . $e->getMessage());

    // Mensagem genérica para o usuário
    $_SESSION['error'] = "Erro ao processar a solicitação. Por favor, tente novamente.";
    header("Location: index.php");
    exit;
}