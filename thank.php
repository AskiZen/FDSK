<?php
// 60 секунд до перенаправлення
$redirectTime = 30;
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>✅ Дякуємо!</title>
    <meta http-equiv="refresh" content="<?= $redirectTime ?>;url=index.php">
    <style>
        body {
            background-color: #111;
            color: #0f0;
            font-family: sans-serif;
            text-align: center;
            padding-top: 80px;
        }
        .box {
            background: #1e1e1e;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px #0f0;
        }
        .timer {
            font-size: 18px;
            color: #ccc;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>✅ Дякуємо за реєстрацію!</h2>
        <p>Ваша анкета успішно надіслана адміністрації.</p>
        <p>📬 Найближчим часом Ви отримаєте <b>особисте повідомлення</b> з підтвердженням або причиною відмови.</p>
        <p>🛡️ У разі підтвердження — Ви отримаєте новий пароль для входу.</p>
        <div class="timer">⏳ Повернення на головну через <span id="countdown"><?= $redirectTime ?></span> сек.</div>
    </div>

    <script>
        let seconds = <?= $redirectTime ?>;
        const countdown = document.getElementById("countdown");
        setInterval(() => {
            if (seconds > 0) {
                seconds--;
                countdown.textContent = seconds;
            }
        }, 1000);
    </script>
</body>
</html>
