<?php
require_once 'admin/config.php';

// 获取所有媒体文件
$stmt = $db->query('SELECT * FROM media ORDER BY created_at DESC');
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huxin的个人网站</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- AOS库用于滚动动画 -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- 自定义样式 -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- 头部区域 -->
    <header class="header">
        <div class="container">
            <h1 class="logo">Huxin</h1>
            <nav class="nav">
                <a href="#about" class="nav-link">关于我</a>
                <a href="#gallery" class="nav-link">作品集</a>
                <a href="#contact" class="nav-link">联系方式</a>
            </nav>
        </div>
    </header>

    <!-- 主要展示区 -->
    <section id="hero" class="hero">
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <div class="avatar-container">
                    <div class="avatar"></div>
                    <div class="avatar-ring"></div>
                </div>
                <h2 class="hero-title">你好，我是 Huxin</h2>
                <p class="hero-subtitle">一名热爱设计和开发的创作者</p>
                <div class="hero-buttons">
                    <a href="#about" class="btn btn-primary">了解更多</a>
                    <a href="#contact" class="btn btn-outline">联系我</a>
                </div>
            </div>
        </div>
    </section>

    <!-- 作品展示区 -->
    <section id="gallery" class="gallery" data-aos="fade-up">
        <div class="container">
            <h2 class="section-title">我的作品</h2>
            <div class="gallery-grid">
                <?php foreach ($media as $item): ?>
                <div class="gallery-item <?php echo $item['type'] === 'video' ? 'video-item' : ''; ?>">
                    <?php if ($item['type'] === 'video'): ?>
                        <video src="imgs/<?php echo $item['filename']; ?>" poster="imgs/<?php echo $item['filename']; ?>" preload="metadata">
                            <source src="imgs/<?php echo $item['filename']; ?>" type="video/mp4">
                        </video>
                        <div class="play-button">
                            <i class="fas fa-play"></i>
                        </div>
                    <?php else: ?>
                        <img src="imgs/<?php echo $item['filename']; ?>" alt="<?php echo $item['title']; ?>">
                    <?php endif; ?>
                    <div class="gallery-caption">
                        <h3><?php echo $item['title']; ?></h3>
                        <p><?php echo $item['description']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 底部区域 -->
    <footer class="footer">
        <div class="container">
            <div class="social-links">
                <a href="https://v.douyin.com/iUjPSc2F/" class="social-link" title="抖音" target="_blank">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="https://b23.tv/VNpJxy4" class="social-link" title="哔哩哔哩" target="_blank">
                    <i class="fab fa-bilibili"></i>
                </a>
                <a href="https://v.kuaishou.com/bd16Nv" class="social-link" title="快手" target="_blank">
                    <i class="fas fa-video"></i>
                </a>
                <a href="https://www.xiaohongshu.com/user/profile/62276a050000000010009ab8" class="social-link" title="小红书" target="_blank">
                    <i class="fas fa-book"></i>
                </a>
            </div>
            <p class="copyright">© 2024 Huxin | 保留所有权利</p>
        </div>
    </footer>

    <!-- 图片预览模态框 -->
    <div id="imageModal" class="modal">
        <span class="modal-close">&times;</span>
        <img class="modal-content" id="modalImage">
        <div class="modal-caption"></div>
        <button class="modal-nav prev-btn"><i class="fas fa-chevron-left"></i></button>
        <button class="modal-nav next-btn"><i class="fas fa-chevron-right"></i></button>
    </div>

    <!-- 返回顶部按钮 -->
    <button id="backToTop" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- JavaScript -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="script.js"></script>
</body>
</html> 