// 初始化AOS动画库
AOS.init({
    duration: 800,
    once: true
});

// 轮播图功能
const slider = {
    slides: document.querySelectorAll('.slide'),
    currentSlide: 0,
    autoPlayInterval: null,
    
    init() {
        // 创建轮播点
        const dotsContainer = document.querySelector('.slider-dots');
        this.slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => this.goToSlide(index));
            dotsContainer.appendChild(dot);
        });
        
        // 添加按钮事件监听
        document.querySelector('.prev').addEventListener('click', () => this.prevSlide());
        document.querySelector('.next').addEventListener('click', () => this.nextSlide());
        
        // 鼠标悬停暂停自动播放
        const sliderContainer = document.querySelector('.slider-container');
        sliderContainer.addEventListener('mouseenter', () => this.pauseAutoPlay());
        sliderContainer.addEventListener('mouseleave', () => this.startAutoPlay());
        
        // 开始自动播放
        this.startAutoPlay();
    },
    
    showSlide(index) {
        // 更新轮播点状态
        document.querySelectorAll('.dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
        
        // 更新幻灯片状态
        this.slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        
        this.currentSlide = index;
    },
    
    nextSlide() {
        const next = (this.currentSlide + 1) % this.slides.length;
        this.showSlide(next);
    },
    
    prevSlide() {
        const prev = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.showSlide(prev);
    },
    
    goToSlide(index) {
        this.showSlide(index);
    },
    
    startAutoPlay() {
        this.autoPlayInterval = setInterval(() => this.nextSlide(), 5000);
    },
    
    pauseAutoPlay() {
        clearInterval(this.autoPlayInterval);
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

// 表单提交处理
const contactForm = {
    form: document.getElementById('contactForm'),
    
    init() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    },
    
    handleSubmit(e) {
        e.preventDefault();
        
        // 获取表单数据
        const formData = new FormData(this.form);
        const data = Object.fromEntries(formData.entries());
        
        // 这里可以添加表单提交逻辑
        console.log('Form submitted:', data);
        
        // 清空表单
        this.form.reset();
        
        // 显示成功消息
        alert('消息已发送！');
    }
};

// 初始化所有功能
document.addEventListener('DOMContentLoaded', () => {
    slider.init();
    backToTop.init();
    contactForm.init();
}); 