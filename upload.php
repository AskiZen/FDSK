<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit("⛔ Доступ заборонено.");
}

if (!empty($_FILES['file'])) {
    $file = $_FILES['file'];
    $filename = basename($file['name']);
    $dest = __DIR__ . '/data/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        // Якщо передано опис — зберігаємо його у файл .txt
        if (!empty($_POST['description'])) {
            $desc = trim($_POST['description']);
            file_put_contents(__DIR__ . '/data/' . $filename . '.txt', $desc);
        }
    }
}

header("Location: index.php");
exit;
