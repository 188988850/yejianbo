<?php
/*数据库配置*/
$dbconfig=array(
    "host" => "localhost", //数据库服务器
    "port" => 3306, //数据库端口
    "user" => "xinfaka", //数据库用户名
    "pwd" => "82514a97de548852", //数据库密码
    "dbname" => "xinfaka", //数据库名
    "dbqz" => "shua" //数据表前缀
);

/*系统配置*/
$config=array(
    "site_url" => "http://localhost", //站点URL
    "collect_interval" => 5, //采集间隔(秒)
    "max_retry" => 3, //最大重试次数
    "timeout" => 30, //请求超时时间
    "user_agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
    "log_path" => __DIR__ . "/../data/logs/", //日志路径
    "image_path" => __DIR__ . "/../data/images/", //图片保存路径
    "resource_path" => __DIR__ . "/../data/resources/", //资源保存路径
    "max_memory" => "256M", //最大内存限制
    "max_execution_time" => 3600, //最大执行时间(秒)
    "batch_size" => 50, //每批处理数量
    "retry_interval" => 10, //重试间隔(秒)
    "proxy" => array( //代理设置
        "enabled" => false,
        "host" => "",
        "port" => "",
        "username" => "",
        "password" => ""
    )
);

/*过滤规则*/
$filter_rules = array(
    "keywords" => array(
        "replace" => array(
            "原关键词1" => "替换关键词1",
            "原关键词2" => "替换关键词2",
            "本站" => "本站",
            "原创" => "原创",
            "独家" => "独家",
            "首发" => "首发"
        ),
        "remove" => array(
            "要删除的关键词1",
            "要删除的关键词2",
            "广告",
            "推广",
            "联系方式",
            "QQ",
            "微信",
            "电话"
        )
    ),
    "content" => array(
        "remove_html" => true, //是否移除HTML标签
        "remove_script" => true, //是否移除脚本标签
        "remove_style" => true, //是否移除样式标签
        "remove_comments" => true, //是否移除注释
        "remove_ads" => true, //是否移除广告
        "remove_links" => true, //是否移除链接
        "remove_images" => false, //是否移除图片
        "remove_tables" => true, //是否移除表格
        "remove_forms" => true, //是否移除表单
        "remove_iframes" => true, //是否移除iframe
        "remove_objects" => true, //是否移除object标签
        "remove_embeds" => true, //是否移除embed标签
        "remove_metas" => true, //是否移除meta标签
        "remove_attributes" => array( //要移除的属性
            "onclick",
            "onload",
            "onerror",
            "onmouseover",
            "onmouseout",
            "onkeydown",
            "onkeyup",
            "onkeypress",
            "onblur",
            "onfocus",
            "onchange",
            "onsubmit",
            "onreset"
        )
    ),
    "urls" => array(
        "remove_query" => true, //是否移除URL参数
        "remove_fragment" => true, //是否移除URL片段
        "convert_relative" => true, //是否转换相对URL
        "allowed_domains" => array( //允许的域名
            "localhost",
            "127.0.0.1"
        )
    ),
    "images" => array(
        "download" => true, //是否下载图片
        "max_size" => 5242880, //最大图片大小(5MB)
        "allowed_types" => array( //允许的图片类型
            "jpg",
            "jpeg",
            "png",
            "gif",
            "webp"
        ),
        "rename" => true, //是否重命名图片
        "quality" => 80 //图片质量
    )
);

/*资源处理规则*/
$resource_rules = array(
    "title" => array(
        "max_length" => 100, //最大长度
        "remove_special" => true, //移除特殊字符
        "remove_emoji" => true //移除表情符号
    ),
    "description" => array(
        "max_length" => 500, //最大长度
        "remove_html" => true, //移除HTML
        "remove_links" => true //移除链接
    ),
    "content" => array(
        "max_length" => 10000, //最大长度
        "remove_duplicates" => true, //移除重复内容
        "remove_empty_lines" => true, //移除空行
        "remove_extra_spaces" => true //移除多余空格
    ),
    "hidden_resources" => array(
        "download" => true, //是否下载隐藏资源
        "max_size" => 104857600, //最大文件大小(100MB)
        "allowed_types" => array( //允许的文件类型
            "zip",
            "rar",
            "7z",
            "pdf",
            "doc",
            "docx",
            "xls",
            "xlsx",
            "txt"
        )
    )
);
?> 