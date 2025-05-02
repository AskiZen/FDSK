<?php
require_once __DIR__ . '/inc/protect.php';
isBanned();

$role = $_SESSION['role'] ?? '';
if (!$role) {
    header("Location: login.php");
    exit;
}

$files = array_diff(scandir(__DIR__ . "/data"), ['.', '..']);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>📁 Файли</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<canvas id="bg"></canvas>
<div class="container">
    <h1>📁 Локальні файли</h1>

    <?php if ($role === 'admin'): ?>
    <form action="upload.php" method="POST" enctype="multipart/form-data" class="upload-form">
        <input type="file" name="file" required>
        <button type="submit">⬆️ Завантажити</button>
    </form>
    <?php endif; ?>

    <ul class="file-list">
        <?php foreach ($files as $file): ?>
            <li>
                <a href="download.php?f=<?= urlencode($file) ?>" download><?= htmlspecialchars($file) ?></a>
                <?php if ($role === 'admin'): ?>
                    <a href="delete.php?f=<?= urlencode($file) ?>" class="delete">✖</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <form action="logout.php" method="POST">
        <button type="submit" class="logout">🚪 Вийти</button>
    </form>
</div>

<script src="bg.js"></script>
<script>
    document.querySelector("form.logout").addEventListener("submit", function() {
        setTimeout(() => window.close(), 500);
    });
</script>
</body>
</html>