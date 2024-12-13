<?php
require_once 'config.php';
checkLogin();

// 处理文件上传
if (isset($_POST['upload'])) {
    $file = $_FILES['file'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    if ($file['error'] === 0 && isAllowedFile($file['name'])) {
        $extension = getFileExtension($file['name']);
        $newFilename = uniqid() . '.' . $extension;
        $uploadPath = '../imgs/' . $newFilename;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $type = in_array($extension, ['mp4', 'mov']) ? 'video' : 'image';
            $stmt = $db->prepare('INSERT INTO media (type, filename, title, description) VALUES (?, ?, ?, ?)');
            $stmt->execute([$type, $newFilename, $title, $description]);
            $success = '文件上传成功！';
        } else {
            $error = '文件上传失败，请重试。';
        }
    } else {
        $error = '无效的文件类型或上传出错。';
    }
}

// 处理文件删除
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $db->prepare('SELECT filename FROM media WHERE id = ?');
    $stmt->execute([$id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($file) {
        $filepath = '../imgs/' . $file['filename'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        $stmt = $db->prepare('DELETE FROM media WHERE id = ?');
        $stmt->execute([$id]);
        $success = '文件删除成功！';
    }
}

// 获取所有媒体文件
$stmt = $db->query('SELECT * FROM media ORDER BY created_at DESC');
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>媒体管理 - Huxin的个人网站</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-container {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .upload-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .media-item {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .media-preview {
            position: relative;
            padding-top: 75%;
        }
        .media-preview img,
        .media-preview video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .media-info {
            padding: 15px;
        }
        .media-actions {
            padding: 15px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
        }
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success {
            background: #e8f5e9;
            color: #2e7d32;
        }
        .error {
            background: #ffebee;
            color: #c62828;
        }
        .logout-btn {
            background: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>媒体管理</h1>
            <a href="logout.php" class="btn logout-btn">退出登录</a>
        </div>

        <?php if (isset($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="upload-form" method="post" enctype="multipart/form-data">
            <h2>上传新文件</h2>
            <div class="form-group">
                <input type="file" name="file" required accept="image/*,video/*">
            </div>
            <div class="form-group">
                <input type="text" name="title" placeholder="标题" required>
            </div>
            <div class="form-group">
                <textarea name="description" placeholder="描述"></textarea>
            </div>
            <button type="submit" name="upload" class="btn btn-primary">上传</button>
        </form>

        <div class="media-grid">
            <?php foreach ($media as $item): ?>
                <div class="media-item">
                    <div class="media-preview">
                        <?php if ($item['type'] === 'video'): ?>
                            <video src="../imgs/<?php echo $item['filename']; ?>" controls></video>
                        <?php else: ?>
                            <img src="../imgs/<?php echo $item['filename']; ?>" alt="<?php echo $item['title']; ?>">
                        <?php endif; ?>
                    </div>
                    <div class="media-info">
                        <h3><?php echo $item['title']; ?></h3>
                        <p><?php echo $item['description']; ?></p>
                    </div>
                    <div class="media-actions">
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('确定要删除这个文件吗？')">删除</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html> 