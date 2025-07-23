<?php
session_start();
require 'db.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['username']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: index.php');
    exit();
}

// Mensagens de feedback
$message = '';
$messageType = '';

// Aprovar usuário
if (isset($_POST['approve'])) {
    $userId = $_POST['user_id'];
    $stmt = $pdo->prepare("UPDATE users SET is_approved = TRUE WHERE id = ?");
    if ($stmt->execute([$userId])) {
        $message = "Usuário aprovado com sucesso!";
        $messageType = "success";
    } else {
        $message = "Erro ao aprovar usuário.";
        $messageType = "danger";
    }
}

// Rejeitar usuário
if (isset($_POST['reject'])) {
    $userId = $_POST['user_id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$userId])) {
        $message = "Usuário rejeitado com sucesso!";
        $messageType = "success";
    } else {
        $message = "Erro ao rejeitar usuário.";
        $messageType = "danger";
    }
}

// Promover usuário a administrador
if (isset($_POST['make_admin'])) {
    $userId = $_POST['user_id'];
    $stmt = $pdo->prepare("UPDATE users SET is_admin = TRUE WHERE id = ?");
    if ($stmt->execute([$userId])) {
        $message = "Usuário promovido a administrador com sucesso!";
        $messageType = "success";
    } else {
        $message = "Erro ao promover usuário a administrador.";
        $messageType = "danger";
    }
}

// Remover privilégios de administrador
if (isset($_POST['remove_admin'])) {
    $userId = $_POST['user_id'];
    $stmt = $pdo->prepare("UPDATE users SET is_admin = FALSE WHERE id = ?");
    if ($stmt->execute([$userId])) {
        $message = "Privilégios de administrador removidos com sucesso!";
        $messageType = "success";
    } else {
        $message = "Erro ao remover privilégios de administrador.";
        $messageType = "danger";
    }
}

// deletar usuário
if (isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$userId])) {
        $message = "Usuário excluído com sucesso!";
        $messageType = "success";
    } else {
        $message = "Erro ao excluir usuário.";
        $messageType = "danger";
    }
}

// Filtro de visualização
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Buscar usuários com base no filtro
switch ($filter) {
    case 'pending':
        $stmt = $pdo->query("SELECT id, name, email, created_at, is_approved, is_admin FROM users WHERE is_approved = FALSE ORDER BY created_at DESC");
        break;
    case 'approved':
        $stmt = $pdo->query("SELECT id, name, email, created_at, is_approved, is_admin FROM users WHERE is_approved = TRUE ORDER BY created_at DESC");
        break;
    case 'admins':
        $stmt = $pdo->query("SELECT id, name, email, created_at, is_approved, is_admin FROM users WHERE is_admin = TRUE ORDER BY created_at DESC");
        break;
    default: // 'all'
        $stmt = $pdo->query("SELECT id, name, email, created_at, is_approved, is_admin FROM users ORDER BY created_at DESC");
        break;
}

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="language" content="pt-BR">
    <title>Painel Administrativo - Gerador de UTM</title>
    <meta name="description" content="Gerador de UTMs da equipe Sales Prime">
    <meta name="robots" content="none">
    <meta name="author" content="Rugemtugem">
    <meta name="keywords" content="#geradordeutm #utm #salesprime">

    <meta property="og:type" content="page">
    <meta property="og:url" content="https://salesprime.com.br/utm/">
    <meta property="og:title" content="Gerador de UTM">
    <meta property="og:image" content="https://salesprime.com.br/wp-content/uploads/2024/09/thumbnail-sales.jpg">
    <meta property="og:description" content="Gerador de UTMs da equipe Sales Prime">

    <meta property="article:author" content="Rugemtugem">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@">
    <meta name="twitter:title" content="Gerador de UTM">
    <meta name="twitter:creator" content="@">
    <meta name="twitter:description" content="Gerador de UTMs da equipe Sales Prime">
    <link rel="icon" href="https://lp.salesprime.com.br/wp-content/uploads/2024/08/cropped-Favicon-32px-32x32.png.webp" sizes="32x32" />
<link rel="icon" href="https://lp.salesprime.com.br/wp-content/uploads/2024/08/cropped-Favicon-32px-192x192.png.webp" sizes="192x192" />
<link rel="apple-touch-icon" href="https://lp.salesprime.com.br/wp-content/uploads/2024/08/cropped-Favicon-32px-180x180.png.webp" />
<meta name="msapplication-TileImage" content="https://lp.salesprime.com.br/wp-content/uploads/2024/08/cropped-Favicon-32px-270x270.png" />
    <link href="style.css?v3" rel="stylesheet">
    <script src="script.js?v1"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="p-3 fixed-top mt-5" style="width: 10%;">
        <div id="themeSwitch" class="theme-switch-container light-active">
            <div class="theme-switch-bg"></div>
            <div class="theme-switch-option light-option active">
                <i class="bi bi-sun"></i>
                <span>Light</span>
            </div>
            <div class="theme-switch-option dark-option">
                <i class="bi bi-moon-stars-fill"></i>
                <span>Dark</span>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col">
                <img src="images/logo-light.png" alt="Logo" class="img-fluid d-none" id="logo-light" style="max-width: 150px;">
                <img src="images/logo-dark.png" alt="Logo" class="img-fluid d-block" id="logo-dark" style="max-width: 150px;">
                <script>
                    // Troca a logo conforme o tema e salva no localStorage
                    function updateLogo() {
                        // Detecta tema salvo no localStorage ou pelo body
                        let theme = localStorage.getItem('theme');
                        if (!theme) {
                            theme = document.body.classList.contains('dark-theme') ||
                                document.querySelector('.theme-switch-container')?.classList.contains('dark-active')
                                ? 'dark' : 'light';
                        }
                        const isDark = theme === 'dark';
                        document.getElementById('logo-light').classList.toggle('d-none', isDark);
                        document.getElementById('logo-dark').classList.toggle('d-none', !isDark);
                    }
                    // Detecta troca de tema e salva no localStorage
                    document.addEventListener('DOMContentLoaded', updateLogo);
                    document.querySelectorAll('.theme-switch-option').forEach(opt => {
                        opt.addEventListener('click', function() {
                            setTimeout(() => {
                                // Detecta tema atual e salva
                                const isDark = document.body.classList.contains('dark-theme') ||
                                    document.querySelector('.theme-switch-container')?.classList.contains('dark-active');
                                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                                updateLogo();
                            }, 10);
                        });
                    });
                </script>
                <h2 class="mt-4">Painel Administrativo</h2>
            </div>
            <div class="col text-end">
                <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-90deg-left"></i> Voltar</a>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="mb-4">
            <div class="btn-group" role="group">
                <a href="?filter=all" class="btn btn-outline-primary <?= $filter === 'all' ? 'active' : '' ?>">
                    Todos os Usuários
                </a>
                <a href="?filter=pending" class="btn btn-outline-warning <?= $filter === 'pending' ? 'active' : '' ?>">
                    Pendentes de Aprovação
                </a>
                <a href="?filter=approved" class="btn btn-outline-success <?= $filter === 'approved' ? 'active' : '' ?>">
                    Aprovados
                </a>
                <a href="?filter=admins" class="btn btn-outline-danger <?= $filter === 'admins' ? 'active' : '' ?>">
                    Administradores
                </a>
            </div>
        </div>

        <?php if (empty($users)): ?>
            <div class="alert alert-info">Não há usuários para exibir com o filtro selecionado.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Data de Cadastro</th>
                            <th>Status</th>
                            <th>Função</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <?php if ($user['is_approved']): ?>
                                        <span class="badge bg-success">Aprovado</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['is_admin']): ?>
                                        <span class="badge bg-danger">Administrador</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Usuário</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?php if (!$user['is_approved']): ?>
                                            <form method="POST" class="d-inline me-1">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" name="approve" class="btn btn-success btn-sm" title="Aprovar">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                            <form method="POST" class="d-inline me-1">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" name="reject" class="btn btn-danger btn-sm" title="Rejeitar" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if (!$user['is_admin']): ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" name="make_admin" class="btn btn-primary btn-sm" title="Promover a Administrador">
                                                    <i class="bi bi-shield-plus"></i>
                                                </button>
                                            <form method="POST" class="d-inline me-1">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" name="delete_user" class="btn btn-danger btn-sm" title="Deletar" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" name="remove_admin" class="btn btn-warning btn-sm" title="Remover privilégios de Administrador">
                                                    <i class="bi bi-shield-minus"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
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