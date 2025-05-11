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

// Каталог анкет
$formDir = __DIR__ . '/anon_forms';
if (!is_dir($formDir)) mkdir($formDir);
$files = glob($formDir . '/*.json');

// Обробка видалення
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    $target = basename($_POST['delete_file']);
    $targetPath = $formDir . '/' . $target;
    if (file_exists($targetPath)) {
        unlink($targetPath);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Інтерфейс
echo '<div style="max-width: 900px; margin: auto; font-family: sans-serif; padding: 20px;">';
echo '<h2>📋 Анонімні анкети</h2>';
echo '<a href="index.php" style="text-decoration: none; color: #007e97;">← Назад</a><hr>';

if (!$files) {
    echo '<p style="color: grey;">Немає поданих анкет.</p>';
} else {
    foreach ($files as $file) {
        $data = json_decode(file_get_contents($file), true);
        $filename = basename($file);
        echo '<div style="border: 1px solid #ccc; border-radius: 6px; padding: 10px; margin-bottom: 15px; background: #f9f9f9;">';
        echo '<b>📌 Подано:</b> <i>' . date("d.m.Y H:i", filemtime($file)) . '</i><br><br>';
        echo '<ul style="padding-left: 20px;">';
        foreach ($data as $key => $value) {
            echo '<li><b>' . htmlspecialchars($key) . ':</b> ' . htmlspecialchars($value) . '</li>';
        }
        echo '</ul>';
        echo '<form method="POST" onsubmit="return confirm(\'Видалити цю анкету?\')">
                <input type="hidden" name="delete_file" value="' . htmlspecialchars($filename) . '">
                <button type="submit" style="background:#a00; color:#fff; border:none; padding:6px 10px; border-radius:4px;">🗑 Видалити</button>
              </form>';
        echo '</div>';
    }
}
echo '</div>';
