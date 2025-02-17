<?php
// Verifica se os parâmetros foram fornecidos
if (!isset($_GET['url']) || !isset($_GET['filename'])) {
    die("Parâmetros inválidos.");
}

$url = $_GET['url'];
$filename = $_GET['filename'];

// Valida a URL
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    die("URL inválida.");
}

// Verifica se a URL é de um domínio permitido (opcional, mas recomendado)
$allowedDomains = ['example.com', 'trusted-domain.com']; // Substitua pelos domínios permitidos
$parsedUrl = parse_url($url);
if (!in_array($parsedUrl['host'], $allowedDomains)) {
    die("Domínio não permitido.");
}

// Valida o nome do arquivo para evitar injeção de cabeçalhos
if (preg_match('/[^a-zA-Z0-9_\-\.]/', $filename)) {
    die("Nome do arquivo inválido.");
}

// Verifica se a URL aponta para um arquivo válido
$headers = get_headers($url, 1);
if (!$headers || strpos($headers[0], '200') === false) {
    die("Arquivo não encontrado ou URL inválida.");
}

// Verifica o tipo de conteúdo (opcional, mas recomendado)
$contentType = $headers['Content-Type'] ?? '';
$allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'text/plain']; // Defina os tipos permitidos
if (!in_array($contentType, $allowedTypes)) {
    die("Tipo de arquivo não permitido.");
}

// Configura os cabeçalhos para download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Content-Length: ' . $headers['Content-Length']);

// Lê o arquivo e envia para o navegador
$fileContent = file_get_contents($url);
if ($fileContent === false) {
    die("Erro ao ler o arquivo.");
}

echo $fileContent;
exit;