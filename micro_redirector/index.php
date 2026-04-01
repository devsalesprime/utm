<?php
/**
 * UTM Micro-Redirector (Prosperus Club)
 * Este arquivo pega qualquer código (/utm/XXXXXX) repassado pelo .htaccess,
 * interroga a API da Sales Prime via cURL e redireciona instantaneamente o usuário.
 */

// Pega o código fornecido pelo .htaccess
$code = isset($_GET['v']) ? trim($_GET['v']) : '';

// URL Mestra do Backend Principal (Ambiente Online)
// Como o banco de dados oficial está na salesprime.com.br, esta VPS sempre chamará esta URL
$master_api_url = "https://salesprime.com.br/utm/api/resolve_url.php";

if (empty($code)) {
    // Redireciona para o site raiz se acessarem /utm vazio
    header("Location: /");
    exit;
}

// Capturar os dados do usuário para não perdermos o rastreamento real na base de dados
$real_ip = $_SERVER['REMOTE_ADDR'] ?? '';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$referer = $_SERVER['HTTP_REFERER'] ?? '';

// Montar os parâmetros para a chamada à API Central
$query = http_build_query([
    'code' => $code,
    'ip'   => $real_ip,
    'ua'   => $user_agent,
    'ref'  => $referer
]);

$remote_url = $master_api_url . "?" . $query;

// Inicializa o cURL para chamar a API CEntral (Sales Prime) de forma super rápida (timeout 2s)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $remote_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 2); // Não travar o servidor Prosperus se a Sales Prime cair
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Se der erro de SSL no VPS, descomente isso
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Decodificar a resposta
$result = json_decode($response, true);

// Se a Sales Prime devolveu "success: true" e o link real:
if ($http_code === 200 && $result && isset($result['success']) && $result['success'] === true) {
    $long_url = $result['long_url'];
    
    // Desabilitar o cache para que cada clique novo sempre bata no php do proxy e conte +1
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    
    // Redirecionamento Final
    header("Location: " . $long_url, true, 302);
    exit;
}

// Se o código não existir ou a Sales Prime cair, volta para a home da Prosperus!
header("Location: /");
exit;
