<?php
if (!defined('IN_CRONLITE')) die();

$title = '福袋';
include TEMPLATE_ROOT.'storenews/header.php';
?>

<div class="lucky-bag-container">
    <div class="lucky-bag">
        <div class="lucky-bag-close" onclick="closeLuckyBag()">×</div>
        <div class="lucky-bag-title">手气福袋</div>
        <div class="lucky-bag-rules">
            <div class="rule-item">
                <i class="fa fa-gift"></i>
                <span>未消费用户：0.1-1元随机金额</span>
            </div>
            <div class="rule-item">
                <i class="fa fa-gift"></i>
                <span>已消费用户：0.1-10元随机金额</span>
            </div>
            <div class="rule-item">
                <i class="fa fa-users"></i>
                <span>10人同时抢包，手气最佳者额外奖励</span>
            </div>
            <div class="rule-item">
                <i class="fa fa-clock-o"></i>
                <span>每天最多可参与3次</span>
            </div>
        </div>
        <div class="prize-pool">
            <div class="prize-pool-title">当前奖池</div>
            <div class="prize-pool-amount" id="prizePool">50.00元</div>
        </div>
        <button class="lucky-bag-open" onclick="startGrabbing()">打开福袋</button>
        <div class="lucky-bag-countdown" id="countdown" style="display: none;">10人抢包中...</div>
        <div class="lucky-bag-progress" style="display: none;">
            <div class="lucky-bag-progress-bar" id="progressBar"></div>
        </div>
        <div class="lucky-bag-amount" id="luckyBagAmount" style="display: none;">0.00元</div>
        <div class="lucky-bag-desc" style="display: none;">已自动存入您的账户</div>
        <div class="lucky-bag-rank" id="rankList" style="display: none;"></div>
    </div>
</div>

<script>
let luckyBagShown = 0;
const maxLuckyBags = 3;
let isGrabbing = false;
let grabCount = 0;
const maxGrabbers = 10;
let rankList = [];

// 虚拟用户数据
const fakeUsers = [
    { name: '用户***', avatar: 'A' },
    { name: '用户***', avatar: 'B' },
    { name: '用户***', avatar: 'C' },
    { name: '用户***', avatar: 'D' },
    { name: '用户***', avatar: 'E' },
    { name: '用户***', avatar: 'F' },
    { name: '用户***', avatar: 'G' },
    { name: '用户***', avatar: 'H' },
    { name: '用户***', avatar: 'I' },
    { name: '用户***', avatar: 'J' }
];

function showLuckyBag() {
    if (luckyBagShown >= maxLuckyBags) {
        alert('今天的福袋已经领完啦，明天再来吧！');
        return;
    }
    
    if (isGrabbing) return;
    
    const container = document.querySelector('.lucky-bag-container');
    const bag = document.querySelector('.lucky-bag');
    
    container.style.display = 'flex';
    bag.style.display = 'block';
    bag.style.animation = 'growBag 0.5s ease-out forwards';
}

function startGrabbing() {
    const bag = document.querySelector('.lucky-bag');
    const countdownElement = document.getElementById('countdown');
    const progressBar = document.getElementById('progressBar');
    const amountElement = document.getElementById('luckyBagAmount');
    const descElement = document.getElementById('luckyBagAmount').nextElementSibling;
    const rankListElement = document.getElementById('rankList');
    
    // 隐藏规则和打开按钮
    bag.querySelector('.lucky-bag-rules').style.display = 'none';
    bag.querySelector('.prize-pool').style.display = 'none';
    bag.querySelector('.lucky-bag-open').style.display = 'none';
    
    // 显示抢包相关元素
    countdownElement.style.display = 'block';
    progressBar.parentElement.style.display = 'block';
    amountElement.style.display = 'block';
    descElement.style.display = 'block';
    rankListElement.style.display = 'block';
    
    isGrabbing = true;
    grabCount = 0;
    rankList = [];
    
    // 开始抢包倒计时
    let countdown = 5;
    const countdownInterval = setInterval(() => {
        countdownElement.textContent = `${countdown}秒后开始抢包...`;
        countdown--;
        if (countdown < 0) {
            clearInterval(countdownInterval);
            startGrabbingProcess();
        }
    }, 1000);
}

function startGrabbingProcess() {
    const countdownElement = document.getElementById('countdown');
    const progressBar = document.getElementById('progressBar');
    const amountElement = document.getElementById('luckyBagAmount');
    const rankListElement = document.getElementById('rankList');
    
    countdownElement.textContent = '10人抢包中...';
    progressBar.style.width = '0%';
    
    // 模拟抢包过程
    const grabInterval = setInterval(() => {
        if (grabCount >= maxGrabbers) {
            clearInterval(grabInterval);
            showResults();
            return;
        }
        
        grabCount++;
        progressBar.style.width = `${(grabCount / maxGrabbers) * 100}%`;
        
        // 随机选择一个虚拟用户
        const randomUser = fakeUsers[Math.floor(Math.random() * fakeUsers.length)];
        let amount;
        <?php if($userrow['money'] > 0) { ?>
            amount = (Math.random() * 9.9 + 0.1).toFixed(2);
        <?php } else { ?>
            amount = (Math.random() * 0.9 + 0.1).toFixed(2);
        <?php } ?>
        
        // 添加到排名列表
        rankList.push({
            name: randomUser.name,
            avatar: randomUser.avatar,
            amount: amount
        });
        
        // 更新排名显示
        updateRankList();
        
        // 如果是最后一个，显示用户自己的结果
        if (grabCount === maxGrabbers) {
            // 调用接口获取真实金额
            fetch(`ajax.php?act=lucky_bag&uid=<?php echo $userrow['uid']; ?>`)
                .then(response => response.json())
                .then(data => {
                    if(data.code === 0) {
                        amountElement.textContent = data.amount + '元';
                        luckyBagShown++;
                        // 更新本地存储
                        localStorage.setItem('luckyBagShown', luckyBagShown.toString());
                    } else {
                        alert(data.msg);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('领取失败，请稍后重试');
                });
        }
    }, 500);
}

function updateRankList() {
    const rankListElement = document.getElementById('rankList');
    rankListElement.innerHTML = '';
    // 按金额排序
    const sortedRank = [...rankList].sort((a, b) => b.amount - a.amount);
    sortedRank.forEach((item, index) => {
        const rankItem = document.createElement('div');
        rankItem.className = 'rank-item';
        rankItem.innerHTML = `
            <div class="rank-user">
                <div class="rank-avatar">${item.avatar}</div>
                <div class="rank-name">${item.name}</div>
            </div>
            <div class="rank-amount">${item.amount}元</div>
        `;
        rankListElement.appendChild(rankItem);
    });
}

function showResults() {
    const countdownElement = document.getElementById('countdown');
    countdownElement.textContent = '抢包结束';
    isGrabbing = false;
    luckyBagShown++;
}

function closeLuckyBag() {
    const container = document.querySelector('.lucky-bag-container');
    container.style.animation = 'scaleOut 0.5s ease-out';
    setTimeout(() => {
        container.style.display = 'none';
        container.style.animation = '';
    }, 500);
}

// 页面加载完成后初始化福袋状态
window.addEventListener('load', () => {
    const today = new Date().toDateString();
    const lastShownDate = localStorage.getItem('luckyBagLastShownDate');
    if (lastShownDate !== today) {
        localStorage.setItem('luckyBagLastShownDate', today);
        localStorage.setItem('luckyBagShown', '0');
        luckyBagShown = 0;
    } else {
        luckyBagShown = parseInt(localStorage.getItem('luckyBagShown') || '0');
    }
    
    // 页面加载后自动显示福袋
    showLuckyBag();
});
</script>

<style>
.lucky-bag-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.lucky-bag {
    width: 420px;
    background: #fff;
    border-radius: 20px;
    padding: 30px;
    text-align: center;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}
.lucky-bag-title {
    font-size: 28px;
    color: #ff4d4f;
    margin-bottom: 25px;
    font-weight: bold;
}
.lucky-bag-rules {
    margin: 20px 0;
    padding: 20px;
    background: #fff9f9;
    border-radius: 15px;
    text-align: left;
}
.rule-item {
    margin: 15px 0;
    font-size: 16px;
    color: #666;
    display: flex;
    align-items: center;
}
.prize-pool {
    margin: 20px 0;
    padding: 20px;
    background: #fff9f9;
    border-radius: 15px;
}
.prize-pool-title {
    font-size: 20px;
    color: #ff4d4f;
    margin-bottom: 15px;
    font-weight: bold;
}
.prize-pool-amount {
    font-size: 32px;
    color: #ff4d4f;
    font-weight: bold;
}
.lucky-bag-open {
    background: linear-gradient(45deg, #ff4d4f, #ff7875);
    color: white;
    border: none;
    padding: 15px 50px;
    border-radius: 30px;
    font-size: 22px;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 25px;
    box-shadow: 0 5px 15px rgba(255,77,79,0.4);
}
.lucky-bag-open:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(255,77,79,0.6);
}
.lucky-bag-close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    color: #999;
    cursor: pointer;
}
.lucky-bag-rank {
    margin-top: 15px;
    max-height: 200px;
    overflow-y: auto;
    border-top: 1px solid #eee;
    padding-top: 15px;
}
.rank-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f5f5f5;
}
.rank-user {
    display: flex;
    align-items: center;
}
.rank-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    margin-right: 8px;
    background: #ff4d4f;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}
.rank-name {
    font-size: 14px;
    color: #333;
}
.rank-amount {
    font-size: 14px;
    color: #ff4d4f;
    font-weight: bold;
}
.lucky-bag-countdown {
    font-size: 18px;
    color: #ff4d4f;
    margin: 10px 0;
    font-weight: bold;
}
.lucky-bag-progress {
    width: 100%;
    height: 8px;
    background: #f5f5f5;
    border-radius: 4px;
    margin: 10px 0;
    overflow: hidden;
}
.lucky-bag-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #ff4d4f, #ff7875);
    width: 0%;
    transition: width 0.3s ease;
}
@media (max-width: 600px) {
  .lucky-bag {
    width: 98vw;
    max-width: 98vw;
    border-radius: 0;
    padding: 0 0 18vw 0;
  }
  .lucky-bag-title {
    font-size: 20px;
    margin-bottom: 18px;
  }
  .lucky-bag-rules,
  .prize-pool {
    font-size: 15px;
    padding: 12px 6px;
    border-radius: 10px;
    width: 94vw;
    max-width: 94vw;
  }
  .prize-pool-amount {
    font-size: 24px;
  }
  .lucky-bag-open {
    font-size: 18px;
    padding: 12px 0;
    border-radius: 20px;
    width: 94vw;
    max-width: 94vw;
  }
  .lucky-bag-progress, .lucky-bag-rank {
    width: 94vw;
    max-width: 94vw;
  }
}
</style>

<?php include TEMPLATE_ROOT.'storenews/footer.php';?> 
<?php include TEMPLATE_ROOT.'storenews/footer.php';?> 