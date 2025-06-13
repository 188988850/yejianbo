// 系统监控管理
class SystemMonitor {
    constructor() {
        this.charts = {};
        this.init();
    }
    
    init() {
        this.setupCharts();
        this.startMonitoring();
    }
    
    setupCharts() {
        // CPU使用率图表
        this.charts.cpu = new Chart(document.getElementById('cpu-chart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'CPU使用率',
                    data: [],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
        
        // 内存使用率图表
        this.charts.memory = new Chart(document.getElementById('memory-chart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: '内存使用率',
                    data: [],
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // 请求统计图表
        this.charts.requests = new Chart(document.getElementById('requests-chart'), {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: '请求数',
                    data: [],
                    backgroundColor: 'rgb(54, 162, 235)'
                }]
            },
            options: {
                responsive: true
            }
        });
    }
    
    async startMonitoring() {
        while (true) {
            await this.updateCharts();
            await new Promise(resolve => setTimeout(resolve, 5000));
        }
    }
    
    async updateCharts() {
        try {
            const response = await fetch('/api/system/status');
            const data = await response.json();
            
            // 更新CPU图表
            this.updateChart(this.charts.cpu, data.cpu_usage);
            
            // 更新内存图表
            this.updateChart(this.charts.memory, data.memory_usage);
            
            // 更新请求图表
            this.updateChart(this.charts.requests, data.request_count);
            
            // 更新其他状态信息
            this.updateStatusInfo(data);
        } catch (error) {
            console.error('Error updating charts:', error);
        }
    }
    
    updateChart(chart, value) {
        const now = new Date().toLocaleTimeString();
        
        chart.data.labels.push(now);
        chart.data.datasets[0].data.push(value);
        
        if (chart.data.labels.length > 20) {
            chart.data.labels.shift();
            chart.data.datasets[0].data.shift();
        }
        
        chart.update();
    }
    
    updateStatusInfo(data) {
        document.getElementById('active-users').textContent = data.active_users;
        document.getElementById('error-count').textContent = data.error_count;
        document.getElementById('database-size').textContent = this.formatSize(data.database_size);
        document.getElementById('disk-usage').textContent = this.formatSize(data.disk_usage);
    }
    
    formatSize(bytes) {
        const units = ['B', 'KB', 'MB', 'GB', 'TB'];
        let size = bytes;
        let unitIndex = 0;
        
        while (size >= 1024 && unitIndex < units.length - 1) {
            size /= 1024;
            unitIndex++;
        }
        
        return `${size.toFixed(2)} ${units[unitIndex]}`;
    }
}

// 错误监控
class ErrorMonitor {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupErrorHandling();
    }
    
    setupErrorHandling() {
        window.onerror = (message, source, lineno, colno, error) => {
            this.logError('error', message, source, lineno, colno, error);
            return false;
        };
        
        window.onunhandledrejection = (event) => {
            this.logError('unhandledrejection', event.reason);
        };
    }
    
    async logError(level, message, source = null, lineno = null, colno = null, error = null) {
        try {
            await fetch('/api/error/log', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    level,
                    message,
                    source,
                    lineno,
                    colno,
                    stack: error ? error.stack : null
                })
            });
        } catch (error) {
            console.error('Error logging error:', error);
        }
    }
}

// 初始化
document.addEventListener('DOMContentLoaded', () => {
    new SystemMonitor();
    new ErrorMonitor();
}); 