<?php
session_start();
require_once __DIR__ . '/inc/protect.php';
isBanned();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || $_SESSION['raw_pass'] !== '123456') {
    banUser($_SERVER['REMOTE_ADDR']);
    exit("🚫 Доступ заборонено.");
}

$dir = __DIR__ . '/users';
if (!is_dir($dir)) mkdir($dir);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new_user'], $_POST['new_pass'])) {
        $username = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['new_user']);
        $password = trim($_POST['new_pass']);
        if ($username && $password) {
            file_put_contents("$dir/$username.pass", $password);
        }
    }
    if (isset($_POST['delete']) && isset($_POST['user_file'])) {
        $target = basename($_POST['user_file']);
        @unlink("$dir/$target");
    }
    if (isset($_POST['update']) && isset($_POST['user_file'], $_POST['new_password'])) {
        $target = basename($_POST['user_file']);
        file_put_contents("$dir/$target", trim($_POST['new_password']));
    }
}

$files = glob("$dir/*.pass");
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>👤 Користувачі</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .user-block { border-bottom: 1px solid #ccc; padding: 10px 0; }
        .user-block form {
            display: grid;
            grid-template-columns: 200px 1fr auto auto;
            gap: 10px;
            align-items: center;
            margin-top: 5px;
        }
        .user-block input[type="text"] { padding: 5px; width: 95%; }
        .user-block button { padding: 5px 10px; }
        .new-user-form {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }
        .new-user-form input[type="text"] {
            padding: 5px;
            width: 100%;
        }
        .new-user-form button {
            grid-column: span 2;
            padding: 5px 10px;
            justify-self: start;
        }
    </style>
</head>
<body class="login">
<div class="login-box">
    <h2>👤 Управління користувачами</h2>
    <a href='admin_panel.php'>← Назад</a>
    <hr>
    <?php foreach ($files as $file): 
        $username = basename($file, '.pass');
        $ipLogFile = __DIR__ . "/logs/$username.iplog";
        $ipList = file_exists($ipLogFile) ? array_unique(array_map('trim', file($ipLogFile))) : [];
    ?>
    <div class='user-block'>
        <strong><?= htmlspecialchars($username) ?></strong><br>
        <div><b>IP історія:</b> <?= htmlspecialchars(implode(", ", $ipList)) ?></div>
        <form method='post'>
            <label style="text-align: right;">Новий пароль:</label>
            <input type='text' name='new_password'>
            <input type='hidden' name='user_file' value='<?= htmlspecialchars($file) ?>'>
            <button type='submit' name='update'>🔄 Оновити</button>
            <button type='submit' name='delete' onclick="return confirm('Видалити користувача?')">🗑 Видалити</button>
        </form>
    </div>
    <?php endforeach; ?>

    <hr><h3>➕ Додати нового користувача</h3>
    <form method='post' class="new-user-form">
        <label style="text-align:right;">Логін (назва файлу):</label>
        <input type='text' name='new_user' required>
        <label style="text-align:right;">Пароль:</label>
        <input type='text' name='new_pass' required>
        <button type='submit'>📂 Створити</button>
    </form>
</div>
</body>
</html>
