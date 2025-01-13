<?php
session_start();
require 'db.php';

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    // Verifique se a senha tem pelo menos 8 caracteres
    return strlen($password) >= 8;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['name'];
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error'] = "Email ou senha inválidos";
        }

        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }

        if ($_SESSION['login_attempts'] >= 3) {
            die('Muitas tentativas de login. Por favor, tente novamente mais tarde.');
        }

        if (!$user) {
            $_SESSION['login_attempts']++;
        } else {
            $_SESSION['login_attempts'] = 0; // Redefine após login bem-sucedido
        }

    } elseif (isset($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!validateEmail($email)) {
            $_SESSION['error'] = "Email inválido";
        } elseif (!validatePassword($password)) {
            $_SESSION['error'] = "A senha deve ter pelo menos 8 caracteres";
        } else {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $query = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $query->execute([$email]);
            if ($query->rowCount() > 0) {
                $_SESSION['error'] = "Email já está em uso";
            } else {
                $query = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                try {
                    $query->execute([$name, $email, $passwordHash]);
                    $_SESSION['success'] = "Cadastro realizado com sucesso! Faça login.";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Erro ao cadastrar: " . $e->getMessage();
                }
            }
        }
    }
    header('Location: index.php');
    exit();
}
?>