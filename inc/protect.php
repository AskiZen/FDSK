<?php
session_start();

function getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    return $_SERVER['REMOTE_ADDR'];
}

function getSubnet($ip) {
    $parts = explode('.', $ip);
    if (count($parts) >= 3) {
        return "{$parts[0]}.{$parts[1]}.{$parts[2]}.";
    }
    return $ip;
}

function getDeviceInfo() {
    return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown device';
}

function isBanned() {
    $ip = getIP();
    $subnet = getSubnet($ip);
    $bannedFile = __DIR__ . '/../bans/banned.txt';
    if (!file_exists($bannedFile)) return;

    $banned = file($bannedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($banned as $line) {
        if (str_contains($line, "IP:$ip") || str_contains($line, "Subnet:$subnet")) {
            http_response_code(403);
            die("🚫 Вас заблоковано. IP або підмережа у чорному списку.");
        }
    }
}

function banUser($inputPassword) {
    $ip = getIP();
    $subnet = getSubnet($ip);
    $device = getDeviceInfo();
    $timestamp = date("d-m-Y H:i:s");

    $banDir = __DIR__ . '/../bans';
    if (!is_dir($banDir)) {
        mkdir($banDir, 0755, true);
    }

    $bannedFile = $banDir . '/banned.txt';
    $banLogFile = $banDir . '/ban_log.txt';

    $log = "$timestamp | IP:$ip | Subnet:$subnet | Device:$device | Input:$inputPassword\n";
    $entry_ip = "IP:$ip | Time:$timestamp | Device:$device | Input:$inputPassword\n";
    $entry_subnet = "Subnet:$subnet | Time:$timestamp | Device:$device\n";

    file_put_contents($bannedFile, $entry_ip, FILE_APPEND);
    file_put_contents($bannedFile, $entry_subnet, FILE_APPEND);
    file_put_contents($banLogFile, $log, FILE_APPEND);
}
