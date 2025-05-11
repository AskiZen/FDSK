<?php
$uri = $_SERVER['REQUEST_URI'] ?? '';
if (preg_match('/\/users\/.*\.pass$/i', $uri)) {
    require_once __DIR__ . '/inc/protect.php';
    banUser("direct .pass access");
    http_response_code(403);
    exit("🚫 Заборонено.");
}
?>
