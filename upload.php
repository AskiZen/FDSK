<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit("⛔ Доступ заборонено.");
}

if (!empty($_FILES['file'])) {
    $file = $_FILES['file'];
    $dest = __DIR__ . '/data/' . basename($file['name']);
    move_uploaded_file($file['tmp_name'], $dest);
}
header("Location: index.php");
exit;