<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit("⛔ Доступ заборонено.");
}

if (!empty($_GET['f'])) {
    $file = basename($_GET['f']);
    $path = __DIR__ . '/data/' . $file;
    if (file_exists($path)) {
        unlink($path);
    }
}
header("Location: index.php");
exit;