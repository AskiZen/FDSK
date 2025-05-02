<?php
require_once __DIR__ . '/inc/protect.php';
isBanned();

$userPassword = '12345';
$adminPassword = 'admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password'] ?? '');

    if ($password === $userPassword) {
        $_SESSION['role'] = 'user';
        setcookie("auth", "user", time() + 3600, "/");
        header("Location: index.php");
        exit;
    } elseif ($password === $adminPassword) {
        $_SESSION['role'] = 'admin';
        setcookie("auth", "admin", time() + 3600, "/");
        header("Location: index.php");
        exit;
    } else {
        banUser($password);
        http_response_code(403);
        exit("🚫 Невірний пароль. Вас заблоковано.");
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>🔐 Вхід</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login">
    <div class="login-box">
        <h2>Вхід до системи</h2>
        <form method="POST">
            <input type="password" name="password" placeholder="Введіть пароль" required>
            <button type="submit">Увійти</button>
        </form>
    </div>
</body>
</html>