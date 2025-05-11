<?php
session_start();
require_once __DIR__ . '/inc/protect.php';
isBanned();

// Блокування за несанкціонований доступ
function ban_ip($ip) {
    $subnet = preg_replace('/\.\d+$/', '', $ip) . '.*';
    file_put_contents(__DIR__ . '/banned.txt', $ip . "\n", FILE_APPEND);
    file_put_contents(__DIR__ . '/banned.txt', $subnet . "\n", FILE_APPEND);
    header('Location: banned.php');
    exit;
}

$client_ip = $_SERVER['REMOTE_ADDR'];
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || ($_SESSION['raw_pass'] ?? '') !== '11220569532') {
    ban_ip($client_ip);
}

// Каталоги
$formDir = __DIR__ . '/anon_forms';
$photoDir = __DIR__ . '/anon_photos';
if (!is_dir($formDir)) mkdir($formDir);
$files = glob($formDir . '/*.json');

// Обробка видалення
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    $target = basename(trim($_POST['delete_file']));
    $targetPath = $formDir . '/' . $target;

    if (file_exists($targetPath)) {
        $formData = json_decode(file_get_contents($targetPath), true);
        unlink($targetPath);

        // Видалення відповідного фото
        if (isset($formData['ПІБ'])) {
            $cleanName = preg_replace('/[^а-яА-ЯіїєґІЇЄҐa-zA-Z0-9\s]/u', '', $formData['ПІБ']);
            $cleanName = preg_replace('/\s+/', '_', trim($cleanName));
            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                $photoFile = "$photoDir/$cleanName.$ext";
                if (file_exists($photoFile)) {
                    unlink($photoFile);
                    break;
                }
            }
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo '<div style="color:red; padding:10px;">❌ Файл не знайдено.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>📋 Анонімні анкети</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login">
<div class="login-box">
    <h2>📋 Анонімні анкети</h2>
    <a href="index.php" style="text-decoration: none; color: #66ccff;">← Назад</a><br><br>

    <?php if (!$files): ?>
        <div style="color: grey;">Немає поданих анкет.</div>
    <?php else: ?>
        <?php foreach ($files as $file): ?>
            <?php
            $data = json_decode(file_get_contents($file), true);
            $filename = basename($file);
            ?>
            <div style="background:#111; border:1px solid #333; padding:15px; border-radius:8px; margin-bottom:20px;">
                <b>📌 Подано:</b> <i><?= date("d.m.Y H:i", filemtime($file)) ?></i><br><br>
                <ul style="list-style: none; padding-left: 0; color: #ccc;">
                    <?php foreach ($data as $key => $value): ?>
                        <li><b><?= htmlspecialchars($key) ?>:</b> <?= htmlspecialchars($value) ?></li>
                    <?php endforeach; ?>
                </ul>

                <?php
                $photoShown = false;
                if (isset($data['ПІБ'])) {
                    $cleanName = preg_replace('/[^а-яА-ЯіїєґІЇЄҐa-zA-Z0-9\s]/u', '', $data['ПІБ']);
                    $cleanName = preg_replace('/\s+/', '_', trim($cleanName));
                    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                        $photoPath = "$photoDir/$cleanName.$ext";
                        if (file_exists($photoPath)) {
                            echo '<div style="margin-top:10px;"><b>📷 Фото:</b><br>';
                            echo '<img src="anon_photos/' . htmlspecialchars($cleanName) . '.' . $ext . '" style="max-width:100%; max-height:300px; border:1px solid #444; margin-top:5px;"></div>';
                            echo '<div style="margin-top:5px;"><a href="anon_photos/' . htmlspecialchars($cleanName) . '.' . $ext . '" download style="color:#66ccff;">⬇️ Завантажити фото</a></div>';
                            $photoShown = true;
                            break;
                        }
                    }
                    if (!$photoShown) {
                        echo '<div style="color: #777;">(Фото не знайдено)</div>';
                    }
                }
                ?>

                <form method="POST" onsubmit="return confirm('Видалити цю анкету разом з фото?')" style="margin-top:10px;">
                    <input type="hidden" name="delete_file" value="<?= htmlspecialchars($filename) ?>">
                    <button type="submit" style="background:#a00; color:#fff; border:none; padding:6px 10px; border-radius:4px;">🗑 Видалити</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
