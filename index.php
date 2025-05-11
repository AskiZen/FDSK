<?php
require_once __DIR__ . '/inc/protect.php';
isBanned();

$role = $_SESSION['role'] ?? '';
if (!$role) {
    header("Location: login.php");
    exit;
}

$dataDir = __DIR__ . "/data";
$files = array_filter(scandir($dataDir), function ($f) {
    return !in_array($f, ['.', '..']) && !str_ends_with($f, '.txt');
});
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>📁 Файли</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .top-right {
            position: absolute;
            top: 15px;
            right: 20px;
        }
        .admin-btn {
            background: #800000;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        .admin-btn:hover {
            background: #a00000;
        }
        .file-list li {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #444;
        }
        .file-desc {
            color: #999;
            margin-top: 4px;
            font-size: 13px;
        }
    </style>
</head>
<body>
<canvas id="bg"></canvas>

<?php if ($role === 'admin'): ?>
    <div class="top-right">
        <a href="admin_panel.php" class="admin-btn">⚙️ Адмін-панель</a>
    </div>
<?php endif; ?>

<div class="container">
    <h1>📁 Локальні файли</h1>

    <?php if ($role === 'admin'): ?>
        <form action="upload.php" method="POST" enctype="multipart/form-data" class="upload-form">
            <input type="file" name="file" required><br>
            <input type="text" name="description" placeholder="Опис (необов’язково)" style="width: 100%; margin-top: 5px;"><br>
            <button type="submit" style="margin-top: 8px;">⬆️ Завантажити</button>
        </form>
    <?php endif; ?>

    <ul class="file-list">
        <?php foreach ($files as $file): ?>
            <li>
                <a href="download.php?f=<?= urlencode($file) ?>" download><?= htmlspecialchars($file) ?></a>
                <?php
                $descFile = $dataDir . '/' . $file . '.txt';
                if (file_exists($descFile)):
                    $desc = file_get_contents($descFile);
                ?>
                    <div class="file-desc">📄 <?= nl2br(htmlspecialchars($desc)) ?></div>
                <?php endif; ?>
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
    document.querySelector("form.logout").addEventListener("submit", function () {
        setTimeout(() => window.close(), 500);
    });
</script>
</body>
</html>
