<?php
// Verifica se as variáveis de ambiente estão definidas
if (!getenv('DB_HOST') || !getenv('DB_NAME') || !getenv('DB_USER') || !getenv('DB_PASS')) {
    die("Erro: Variáveis de ambiente do banco de dados não configuradas.");
}

// Define as configurações do banco de dados
define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));

try {
    // Cria a conexão com o banco de dados
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lança exceções em erros
            PDO::ATTR_EMULATE_PREPARES => false, // Desativa a emulação de prepared statements
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Define o modo de busca padrão
        ]
    );

    echo "Conexão com o banco de dados estabelecida com sucesso!";
} catch (PDOException $e) {
    // Log do erro em um arquivo (recomendado para produção)
    error_log("Erro na conexão com o banco de dados: " . $e->getMessage());

    // Mensagem genérica para o usuário
    die("Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.");
}