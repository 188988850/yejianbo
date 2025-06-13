// 帮助中心管理
class HelpCenter {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.setupSearch();
    }
    
    setupEventListeners() {
        // 文章点击事件
        document.querySelectorAll('.help-article').forEach(article => {
            article.addEventListener('click', () => {
                const articleId = article.dataset.id;
                this.showArticle(articleId);
            });
        });
        
        // 返回按钮
        const backButton = document.getElementById('help-back');
        if (backButton) {
            backButton.addEventListener('click', () => this.showList());
        }
    }
    
    setupSearch() {
        const searchInput = document.getElementById('help-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.searchArticles(e.target.value);
            });
        }
    }
    
    async showArticle(articleId) {
        try {
            const response = await fetch(`/api/help/article/${articleId}`);
            const article = await response.json();
            
            if (article) {
                document.getElementById('help-list').style.display = 'none';
                document.getElementById('help-article').style.display = 'block';
                
                document.getElementById('article-title').textContent = article.title;
                document.getElementById('article-content').innerHTML = article.content;
                
                // 更新阅读量
                await fetch(`/api/help/view/${articleId}`, { method: 'POST' });
            }
        } catch (error) {
            console.error('Error loading article:', error);
        }
    }
    
    showList() {
        document.getElementById('help-list').style.display = 'block';
        document.getElementById('help-article').style.display = 'none';
    }
    
    async searchArticles(keyword) {
        try {
            const response = await fetch(`/api/help/search?keyword=${encodeURIComponent(keyword)}`);
            const articles = await response.json();
            
            const listContainer = document.getElementById('help-list');
            listContainer.innerHTML = '';
            
            articles.forEach(article => {
                const articleElement = document.createElement('div');
                articleElement.className = 'help-article';
                articleElement.dataset.id = article.id;
                articleElement.innerHTML = `
                    <h3>${article.title}</h3>
                    <p>${article.description || ''}</p>
                `;
                listContainer.appendChild(articleElement);
            });
        } catch (error) {
            console.error('Error searching articles:', error);
        }
    }
}

// 反馈管理
class FeedbackManager {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupEventListeners();
    }
    
    setupEventListeners() {
        const feedbackForm = document.getElementById('feedback-form');
        if (feedbackForm) {
            feedbackForm.addEventListener('submit', (e) => this.submitFeedback(e));
        }
    }
    
    async submitFeedback(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = {
            type: formData.get('type'),
            content: formData.get('content'),
            rating: formData.get('rating')
        };
        
        try {
            const response = await fetch('/api/feedback/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            if (response.ok) {
                this.showSuccess('反馈提交成功！');
                e.target.reset();
            } else {
                this.showError('提交失败，请重试');
            }
        } catch (error) {
            console.error('Error submitting feedback:', error);
            this.showError('提交失败，请重试');
        }
    }
    
    showSuccess(message) {
        const notification = document.createElement('div');
        notification.className = 'notification notification-success';
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
    
    showError(message) {
        const notification = document.createElement('div');
        notification.className = 'notification notification-error';
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
}

// 初始化
document.addEventListener('DOMContentLoaded', () => {
    new HelpCenter();
    new FeedbackManager();
}); 