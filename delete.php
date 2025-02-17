<?php
session_start(); // Inicia a sessão (se ainda não estiver iniciada)
require 'db.php'; // Inclui o arquivo de conexão com o banco de dados

// Função para enviar uma resposta JSON padronizada
function jsonResponse($success, $error = null) {
    echo json_encode(['success' => $success, 'error' => $error]);
    exit;
}

// Verifica se a requisição é do tipo POST e se os dados foram recebidos corretamente
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty(file_get_contents('php://input'))) {
    jsonResponse(false, 'Requisição inválida');
}

// Decodifica os dados JSON recebidos
$data = json_decode(file_get_contents('php://input'), true);

// Validação dos dados de entrada
if (!isset($data['id']) || !is_numeric($data['id'])) {
    jsonResponse(false, 'ID inválido');
}

if (!isset($_SESSION['user_id'])) {
    jsonResponse(false, 'Usuário não autenticado');
}

$id = (int)$data['id']; // Converte o ID para inteiro
$user_id = $_SESSION['user_id']; // Obtém o ID do usuário da sessão

try {
    // Verifica se a URL pertence ao usuário logado
    $stmt = $pdo->prepare("SELECT user_id FROM urls WHERE id = ?");
    $stmt->execute([$id]);
    $url = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$url) {
        jsonResponse(false, 'URL não encontrada');
    }

    if ($url['user_id'] != $user_id) {
        jsonResponse(false, 'Acesso não autorizado');
    }

    // Deleta a URL
    $stmt = $pdo->prepare("DELETE FROM urls WHERE id = ?");
    if ($stmt->execute([$id])) {
        jsonResponse(true); // Sucesso
    } else {
        jsonResponse(false, 'Falha ao deletar URL');
    }
} catch (PDOException $e) {
    // Log do erro (recomendado para produção)
    error_log("Erro no delete.php: " . $e->getMessage());

    // Resposta genérica para o cliente
    jsonResponse(false, 'Erro interno no servidor');
}