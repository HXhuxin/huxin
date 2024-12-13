// 初始化AOS动画库
AOS.init({
    duration: 800,
    once: true
});

// 图片和视频预览功能
const mediaPreview = {
    modal: document.getElementById('imageModal'),
    modalImg: document.getElementById('modalImage'),
    modalCaption: document.querySelector('.modal-caption'),
    closeBtn: document.querySelector('.modal-close'),
    prevBtn: document.querySelector('.prev-btn'),
    nextBtn: document.querySelector('.next-btn'),
    galleryItems: document.querySelectorAll('.gallery-item'),
    currentIndex: 0,
    videoPlaying: false,

    init() {
        // 为每个媒体项添加点击事件
        this.galleryItems.forEach((item, index) => {
            if (item.classList.contains('video-item')) {
                const video = item.querySelector('video');
                const playButton = item.querySelector('.play-button');
                const caption = item.querySelector('.gallery-caption').innerHTML;
                
                playButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.currentIndex = index;
                    this.openVideoModal(video, caption);
                });

                video.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.currentIndex = index;
                    this.openVideoModal(video, caption);
                });
            } else {
                const img = item.querySelector('img');
                const caption = item.querySelector('.gallery-caption').innerHTML;
                
                item.addEventListener('click', () => {
                    this.currentIndex = index;
                    this.openImageModal(img.src, caption);
                });
            }
        });

        // 关闭按钮事件
        this.closeBtn.addEventListener('click', () => this.closeModal());
        
        // 点击模态框背景关闭
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal || (!this.videoPlaying && e.target === this.modalImg)) {
                this.closeModal();
            }
        });

        // 上一张/下一张按钮事件
        this.prevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.showPrevItem();
        });
        this.nextBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.showNextItem();
        });

        // 键盘事件
        document.addEventListener('keydown', (e) => {
            if (!this.modal.classList.contains('active')) return;
            
            switch(e.key) {
                case 'Escape':
                    this.closeModal();
                    break;
                case 'ArrowLeft':
                    this.showPrevItem();
                    break;
                case 'ArrowRight':
                    this.showNextItem();
                    break;
            }
        });
    },

    openImageModal(src, caption) {
        this.videoPlaying = false;
        this.modal.classList.remove('video-modal');
        this.modalImg.style.display = 'block';
        if (this.modal.querySelector('video')) {
            this.modal.querySelector('video').remove();
        }
        this.modalImg.src = src;
        this.modalCaption.innerHTML = caption;
        this.modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    },

    openVideoModal(videoElement, caption) {
        this.videoPlaying = true;
        this.modal.classList.add('video-modal');
        this.modalImg.style.display = 'none';
        
        if (this.modal.querySelector('video')) {
            this.modal.querySelector('video').remove();
        }
        
        const videoClone = videoElement.cloneNode(true);
        videoClone.controls = true;
        videoClone.autoplay = true;
        this.modalImg.insertAdjacentElement('afterend', videoClone);
        
        this.modalCaption.innerHTML = caption;
        this.modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    },

    closeModal() {
        if (this.videoPlaying) {
            const video = this.modal.querySelector('video');
            if (video) {
                video.pause();
                video.remove();
            }
        }
        this.modal.classList.remove('active', 'video-modal');
        this.videoPlaying = false;
        document.body.style.overflow = '';
    },

    showPrevItem() {
        this.currentIndex = (this.currentIndex - 1 + this.galleryItems.length) % this.galleryItems.length;
        this.showCurrentItem();
    },

    showNextItem() {
        this.currentIndex = (this.currentIndex + 1) % this.galleryItems.length;
        this.showCurrentItem();
    },

    showCurrentItem() {
        const currentItem = this.galleryItems[this.currentIndex];
        const caption = currentItem.querySelector('.gallery-caption').innerHTML;
        
        if (currentItem.classList.contains('video-item')) {
            const video = currentItem.querySelector('video');
            this.openVideoModal(video, caption);
        } else {
            const img = currentItem.querySelector('img');
            this.openImageModal(img.src, caption);
        }
    }
};

// 返回顶部按钮功能
const backToTop = {
    button: document.getElementById('backToTop'),
    
    init() {
        window.addEventListener('scroll', () => this.toggleButton());
        this.button.addEventListener('click', () => this.scrollToTop());
    },
    
    toggleButton() {
        if (window.scrollY > 300) {
            this.button.classList.add('visible');
        } else {
            this.button.classList.remove('visible');
        }
    },
    
    scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
};

// 初始化所有功能
document.addEventListener('DOMContentLoaded', () => {
    mediaPreview.init();
    backToTop.init();
}); 