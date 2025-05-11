<?php
session_start();
require_once __DIR__ . '/inc/protect.php';
isBanned();

$errors = [];
$timeFile = __DIR__ . '/anon_times.json';
$formDir = __DIR__ . '/anon_forms';
$photoDir = __DIR__ . '/anon_photos';
$ip = $_SERVER['REMOTE_ADDR'];

if (!is_dir($formDir)) mkdir($formDir);
if (!is_dir($photoDir)) mkdir($photoDir);

// антиспам: дозволяємо лише 1 запит на годину з одного IP
if (file_exists($timeFile)) {
    $times = json_decode(file_get_contents($timeFile), true);
    if (isset($times[$ip]) && time() - $times[$ip] < 3600) {
        $errors[] = "⏳ Повторне відправлення доступне через 1 годину.";
    }
} else {
    $times = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    $unit = trim($_POST['unit'] ?? '');
    $battalion = trim($_POST['battalion'] ?? '');
    $platoon = trim($_POST['platoon'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $idcard = trim($_POST['idcard'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $tid = trim($_POST['tid'] ?? '');
    $telegram = trim($_POST['telegram'] ?? '');

    if (!preg_match('/^А\d{4}$/u', $unit)) {
        $errors[] = "❌ Військова частина має бути у форматі А1234.";
    }

    if (empty($platoon)) {
        $errors[] = "❌ Вкажіть роту / взвод.";
    }

    if (empty($name)) {
        $errors[] = "❌ ПІБ не може бути порожнім.";
    }

    if (!preg_match('/^[А-ЯІЇЄҐ]{2}\d{6}$/u', $idcard)) {
        $errors[] = "❌ Номер військового квитка має бути у форматі: АА123456.";
    }

    if (!empty($phone)) {
        $phone = str_replace([' ', '-', '(', ')'], '', $phone);
        if (!preg_match('/^(\+380\d{9}|0\d{9})$/', $phone)) {
            $errors[] = "❌ Неправильний формат телефону.";
        }
    }

    // 🔽 Перевірка завантаженого фото
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "❌ Завантажте фото з військовим квитком.";
    } else {
        $file = $_FILES['photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = "❌ Дозволено лише зображення JPG, PNG або WEBP.";
        } elseif ($file['size'] > 5 * 1024 * 1024) {
            $errors[] = "❌ Максимальний розмір файлу — 5 МБ.";
        }
    }

    if (empty($errors)) {
        $formData = [
            'В/ч' => $unit,
            'Батальйон' => $battalion,
            'Рота/взвод' => $platoon,
            'ПІБ' => $name,
            'Квиток' => $idcard,
            'Телефон' => $phone,
            'ID трима' => $tid,
            'Telegram' => $telegram,
        ];

        // 🔽 Збереження анкети
        $formName = time() . '_' . bin2hex(random_bytes(4));
        $filename = $formDir . '/' . $formName . '.json';
        file_put_contents($filename, json_encode($formData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 🔽 Збереження фото з ПІБ як назвою
$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$cleanName = preg_replace('/[^а-яА-ЯіїєґІЇЄҐa-zA-Z0-9\s]/u', '', $name);
$cleanName = preg_replace('/\s+/', '_', trim($cleanName));
$photoFilename = $cleanName . '.' . $ext;
$photoPath = $photoDir . '/' . $photoFilename;

// Якщо файл з таким ім’ям існує — додаємо індекс
$i = 1;
while (file_exists($photoPath)) {
    $photoFilename = $cleanName . "_$i." . $ext;
    $photoPath = $photoDir . '/' . $photoFilename;
    $i++;
}

move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);


        // Оновлення часу
        $times[$ip] = time();
        file_put_contents($timeFile, json_encode($times));

        header("Location: thank.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>🔒 Анонімна анкета</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login">
<div class="login-box">
    <h2>🔒 Анонімна анкета</h2>
    <?php if (!empty($errors)): ?>
        <div style="background:#300; color: #fff; padding:10px; border-radius:5px; margin-bottom:10px">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" style="text-align:left;">
        <label>Військова частина *<br><input type="text" name="unit" required></label><br>
        <label>Бригада / полк / батальйон *<br><input type="text" name="battalion" required></label><br>
        <label>Рота / взвод *<br><input type="text" name="platoon" required></label><br>
        <label>ПІБ *<br><input type="text" name="name" required></label><br>
        <label>Номер військового квитка *<br><input type="text" name="idcard" required></label><br>
        <label>Номер телефону *<br><input type="text" name="phone"></label><br>
        <label>ID трима (необов’язково)<br><input type="text" name="tid"></label><br>
        <label>Telegram логін (необов’язково)<br><input type="text" name="telegram"></label><br>
        <label>Фото з військовим квитком *<br><input type="file" name="photo" accept="image/jpeg,image/png,image/webp" required></label><br><br>
        <button type="submit" style="width: 100%;">📨 Надіслати</button>
    </form>
</div>
</body>

</html>
