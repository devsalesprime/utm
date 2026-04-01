<?php
/**
 * Redirecionamento de Short Codes com Tracking Detalhado
 * Versão: 2.2 - Registra cliques na tabela `clicks`
 */

require 'includes/db.php';

// Obter o código e validar
$code = isset($_GET['code']) ? trim($_GET['code']) : '';
if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $code)) {
    header("Location: https://salesprime.com.br/utm/?error=invalid_code");
    exit;
}

// Buscar a URL longa associada e verificar se está habilitada
$stmt = $pdo->prepare("SELECT id, long_url, is_enabled FROM urls WHERE shortened_url = ?");
$stmt->execute([$code]);
$url = $stmt->fetch(PDO::FETCH_ASSOC);

if ($url) {
    // Verificar se a UTM está habilitada
    if (!$url['is_enabled']) {
        header("Location: https://salesprime.com.br/");
        exit;
    }

    // Incrementar cliques (atômico)
    $stmt = $pdo->prepare("UPDATE urls SET clicks = COALESCE(clicks, 0) + 1 WHERE id = ?");
    $stmt->execute([$url['id']]);

    // Capturar IP Real (suportando proxies como a VPS do Prosperus)
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_X_REAL_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    if (strpos($ip_address, ',') !== false) {
        $ip_address = explode(',', $ip_address)[0]; // Pega o primeiro se houver múltiplos
    }

    // Registrar clique detalhado na tabela clicks
    try {
        $stmt = $pdo->prepare(
            "INSERT INTO clicks (utm_id, ip_address, user_agent, referer)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $url['id'],
            substr(trim($ip_address), 0, 45),
            substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
            substr($_SERVER['HTTP_REFERER'] ?? '', 0, 500)
        ]);
    } catch (PDOException $e) {
        // Não bloquear o redirect se o tracking falhar
        error_log("Click tracking error: " . $e->getMessage());
    }

    // Redirecionar para a URL longa preservando colchetes
    $redirect_url = $url['long_url'];
    $redirect_url = str_replace(['%5B', '%5D'], ['[', ']'], $redirect_url);

    header("Location: $redirect_url", true, 302);
    exit;
}

// Código não encontrado
header("Location: https://salesprime.com.br/utm/?error=not_found");
exit;