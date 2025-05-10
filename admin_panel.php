<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "<h1>403 Заборонено</h1>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Адмін-панель</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f2f2f2;
            text-align: center;
            padding: 40px;
        }
        .admin-container {
            display: inline-block;
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .admin-container h1 {
            margin-bottom: 20px;
        }
        .admin-btn {
            display: block;
            margin: 15px auto;
            padding: 12px 25px;
            font-size: 16px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
        }
        .admin-btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Адмін-панель</h1>
        <a href="anon_list.php" class="admin-btn">📋 Перевірити анкети</a>
        <a href="manage_users.php" class="admin-btn">🔧 Керування користувачами</a>
    </div>
</body>
</html>
