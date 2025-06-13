$(document).ready(function() {
    // 检测设备类型
    var isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

    if (isIOS) {
        $('.ios-only').show(); // 显示 iOS 设备的内容
    } else {
        $('.ios-only').hide(); // 隐藏其他设备的内容
    }

    var page = 1;
    var loading = false;
    var type = '<?=$get['type']?>'; // Ensure this variable is defined in your PHP code

    // 四宫格内容数据
    var gridItems = [
        { title: "短剧1", description: "这是短剧1的简介", image: "https://img1.baidu.com/it/u=3075276504,3443869891&fm=253&fmt=auto&app=138&f=JPEG?w=630&h=318", url: "./page1.php" },
        { title: "短剧2", description: "这是短剧2的简介", image: "https://img1.baidu.com/it/u=410177692,2993954610&fm=253&fmt=auto&app=138&f=JPEG?w=889&h=500", url: "./page2.php" },
        { title: "短剧3", description: "这是短剧3的简介", image: "https://img1.baidu.com/it/u=2212281332,3442342214&fm=253&fmt=auto&app=138&f=JPEG?w=600&h=337", url: "./page3.php" },
        { title: "短剧4", description: "这是短剧4的简介", image: "https://img2.baidu.com/it/u=1286533134,3948294194&fm=253&fmt=auto&app=138&f=JPEG?w=889&h=500", url: "./page4.php" }
    ];

    // 加载四宫格内容
    function loadGridItems() {
        gridItems.forEach(function(item) {
            var gridItemHtml = `
                <div class="grid-item">
                    <a href="${item.url}" class="grid-link">
                        <div class="item-inner">
                            <img src="${item.image}" alt="${item.title}">
                            <div class="item-info">
                                <h3>${item.title}</h3>
                                <p>${item.description}</p>
                            </div>
                        </div>
                    </a>
                </div>
            `;
            $('.grid-container').append(gridItemHtml);
        });
    }

    loadGridItems(); // 加载四宫格内容

    function loadMoreData() {
        if (loading) return;
        loading = true;
        $.ajax({
            url: './ajax.php?act=duanju',
            type: 'GET',
            data: { page: page, type: type },
            success: function(data) {
                if (data.code == 0) {
                    $('.drama-display').append(data.data);
                    page++;
                    loading = false;
                }
            }
        });
    }

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
            loadMoreData();
        }
    });

    $('.category-tabs button').click(function() {
        var type = $(this).data('type');
        if (type) {
            window.location.href = '?mod=duanju&type=' + type;
        }
    });

    // 回到顶部的功能
    $("#scrollTop").click(function() {
        $("html, body").animate({ scrollTop: 0 }, 500);
    });

    // 返回上一页的功能
    $("#goBack").click(function() {
        window.history.back();
    });

    function search() {
        var keyword = $('#keyword').val();
        var page = 1;
        var type = '<?=$get['type']?>'; 
        $.ajax({
            url: './ajax.php?act=duanju',
            type: 'GET',
            data: { keyword: keyword, page: page, type: type },
            success: function(data) {
                if (data.code == 0) {
                    $('.drama-display').html(data.data);
                }
            }
        });
    }

    // Search button event
    $('#searchButton').click(function() {
        search();
    });

    // Load initial data for drama display
    loadMoreData();
});