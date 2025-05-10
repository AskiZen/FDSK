<?php
session_start();
require_once __DIR__ . '/inc/protect.php';
isBanned();

function ban_now($ip) {
    $subnet = preg_replace('/\.\d+$/', '', $ip) . '.*';
    file_put_contents(__DIR__ . '/bans/banned.txt', $ip . "\n", FILE_APPEND);
    file_put_contents(__DIR__ . '/bans/banned.txt', $subnet . "\n", FILE_APPEND);
    header('Location: banned.php');
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if (isset($_POST['ban'])) ban_now($ip);
if (isset($_POST['exit'])) ban_now($ip);
if (isset($_POST['confirm'])) {
    header("Location: anon_form.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>⛔ Заблоковано</title>
    <style>
        body {
            margin: 0;
            font-family: 'Courier New', monospace;
            background: url('https://media.giphy.com/media/eGk6czY6vXrxe/giphy.gif') center center / cover no-repeat fixed;
            color: #00ff00;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        #text {
            max-width: 800px;
            white-space: pre-wrap;
            font-size: 16px;
            line-height: 1.5;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
        }
        .btns {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btns button {
            padding: 10px 20px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        .red { background-color: #900; color: white; }
        .blue { background-color: #005; color: white; }
        .flag {
            background-color: #0057b7;
            background-image: linear-gradient(to bottom, #0057b7 50%, #ffd700 50%);
            color: #000;
            width: 100%;
            max-width: 300px;
            margin: 20px auto 0;
        }
    </style>
</head>
<body>
<div id="text"></div>
<form method="POST" class="btns">
    <button class="red" name="ban">🔴 Заблокувати мене</button>
    <button class="blue" name="exit">🔵 Вийти</button>
</form>
<form method="POST" style="margin-top: 20px; display: flex; justify-content: center;">
    <button class="flag" name="confirm">🇺🇦 Підтвердити особу</button>
</form>
<script>
    const text = `🔒 Ви потрапили до системи автоматичного захисту.

Ваша IP-адреса: <?= $ip ?>
USER-AGENT: <?= $userAgent ?>

Виявлено підозрілу активність.
Режим ізоляції активовано.

⚠️ Спроби несанкціонованого доступу можуть призвести до повного блокування.

☠️ Застосовуються протоколи безпеки... Руни активовані.
⌛ Очікування дії користувача.`;

    let i = 0;
    const speed = 50;
    function typeText() {
        if (i < text.length) {
            document.getElementById('text').textContent += text.charAt(i);
            i++;
            setTimeout(typeText, speed);
        }
    }
    typeText();
</script>
</body>
</html>
