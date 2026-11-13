<?php
session_start();
require_once 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Simple validation
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'Tous les champs sont requis';
        header('Location: ../auth.php');
        exit;
    }
    
    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Les mots de passe ne correspondent pas';
        header('Location: ../auth.php');
        exit;
    }
    
    if (strlen($password) < 6) {
        $_SESSION['error'] = 'Le mot de passe doit avoir au moins 6 caractères';
        header('Location: ../auth.php');
        exit;
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = 'Cet email est déjà utilisé';
        header('Location: ../auth.php');
        exit;
    }
    
    // Hash password and insert
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hashed_password, 'user']);
    
    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['user_name'] = $name;
    $_SESSION['role'] = 'user';
    $_SESSION['success'] = 'Inscription réussie!';
    
    header('Location: ../index.php');
    exit;
}
?>