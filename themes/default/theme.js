// 主题管理
class ThemeManager {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.init();
    }
    
    init() {
        this.applyTheme();
        this.setupEventListeners();
    }
    
    applyTheme() {
        document.documentElement.setAttribute('data-theme', this.theme);
    }
    
    setupEventListeners() {
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }
    }
    
    toggleTheme() {
        this.theme = this.theme === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', this.theme);
        this.applyTheme();
    }
}

// 通知管理
class NotificationManager {
    constructor() {
        this.container = document.getElementById('notification-container');
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.checkNotifications();
    }
    
    setupEventListeners() {
        // 设置通知点击事件
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('notification-close')) {
                this.closeNotification(e.target.closest('.notification'));
            }
        });
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type} fade-in`;
        notification.innerHTML = `
            <div class="notification-content">${message}</div>
            <button class="notification-close">&times;</button>
        `;
        this.container.appendChild(notification);
        setTimeout(() => this.closeNotification(notification), 5000);
    }
    
    closeNotification(notification) {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 300);
    }
    
    async checkNotifications() {
        try {
            const response = await fetch('/api/notifications/unread');
            const notifications = await response.json();
            notifications.forEach(notification => {
                this.showNotification(notification.message, notification.type);
            });
        } catch (error) {
            console.error('Error checking notifications:', error);
        }
    }
}

// 初始化
document.addEventListener('DOMContentLoaded', () => {
    new ThemeManager();
    new NotificationManager();
}); 