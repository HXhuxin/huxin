<?php
// 数据库配置
$db_path = __DIR__ . '/database.sqlite';

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 创建媒体表（如果不存在）
    $db->exec('CREATE TABLE IF NOT EXISTS media (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        type TEXT NOT NULL,
        filename TEXT NOT NULL,
        title TEXT NOT NULL,
        description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )');
} catch(PDOException $e) {
    die('数据库连接失败: ' . $e->getMessage());
}

// 检查是否录
function checkLogin() {
    session_start();
    if (!isset($_SESSION['admin'])) {
        header('Location: login.php');
        exit;
    }
}

// 获取文件扩展名
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

// 检查是否是允许的文件类型
function isAllowedFile($filename) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov'];
    return in_array(getFileExtension($filename), $allowed);
} 