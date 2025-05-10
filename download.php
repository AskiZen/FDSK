<?php
require_once __DIR__ . '/inc/protect.php';
isBanned();
session_start();

if (!isset($_SESSION['role'])) {
    http_response_code(403);
    exit("⛔ Доступ лише після входу.");
}

if (!empty($_GET['f'])) {
    $file = basename($_GET['f']);
    $path = __DIR__ . "/data/$file";
    if (file_exists($path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}
http_response_code(404);
echo "Файл не знайдено.";