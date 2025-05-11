<?php
session_start();
require_once __DIR__ . '/inc/protect.php';
isBanned();

$maxAttempts = 3;
$usersDir = __DIR__ . '/users';
if (!is_dir($usersDir)) mkdir($usersDir);

$adminPassword = '11220569532'; // Статичний пароль адміна

if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

$showResetLink = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password'] ?? '');

    if ($password === $adminPassword) {
        $_SESSION['role'] = 'admin';
        $_SESSION['raw_pass'] = $password;
        $_SESSION['user'] = 'admin';
        setcookie("auth", "admin", time() + 3600, "/");
        header("Location: index.php");
        exit;
    }

    foreach (glob("$usersDir/*.pass") as $file) {
        $storedPass = trim(file_get_contents($file));
        if ($password === $storedPass) {
            $user = basename($file, '.pass');
            $_SESSION['role'] = 'user';
            $_SESSION['raw_pass'] = $password;
            $_SESSION['user'] = $user;
            setcookie("auth", "user", time() + 3600, "/");

            // Логування IP
            $ip = $_SERVER['REMOTE_ADDR'];
            $logFile = __DIR__ . "/logs/{$user}.iplog";
            file_put_contents($logFile, $ip . "\n", FILE_APPEND);

            header("Location: index.php");
            exit;
        }
    }

    $_SESSION['attempts']++;
    $showResetLink = true;

    if ($_SESSION['attempts'] >= $maxAttempts) {
        banUser($_SERVER['REMOTE_ADDR']);
        http_response_code(403);
        exit("🚫 Занадто багато спроб. Вас заблоковано.");
    }

    $error = "❌ Невірний пароль. Спроба {$_SESSION['attempts']} з {$maxAttempts}.";
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
    <?php if (!empty($error)): ?>
        <div style="background:#300; color: #fff; padding:10px; border-radius:5px; margin-bottom:10px">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <input type="password" name="password" placeholder="Введіть пароль" required>
        <button type="submit">Увійти</button>
    </form>
    <?php if ($showResetLink): ?>
    <div style="margin-top: 15px;">
        <button type="button" onclick="location.href='anon_form.php'" style="
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            background-color: #007e97;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;">
            ❓ Забули пароль?
        </button>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
