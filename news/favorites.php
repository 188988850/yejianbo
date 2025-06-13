<?php
ini_set('session.cookie_domain', '.wamsg.cn');
session_set_cookie_params(['path' => '/', 'domain' => '.wamsg.cn']);
session_start();
require_once('../config.php');
// 收藏夹页面，无需PHP逻辑，全部前端渲染
?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>我的收藏夹</title>
    <style>
    body { background: #f7f7f7; font-family: Arial, sans-serif; margin: 0; }
    .fav-header { background: #fff; padding: 18px 16px 10px 16px; font-size: 1.3rem; font-weight: bold; color: #222; box-shadow: 0 2px 8px #eee; display: flex; align-items: center; justify-content: space-between; }
    .fav-header .back-btn { background: #eaf4fb; color: #2980b9; border: none; border-radius: 50%; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; cursor: pointer; box-shadow: 0 2px 8px #e0e0e0; margin-right: 10px; }
    .fav-list { max-width: 600px; margin: 24px auto 0 auto; padding: 0 8px; }
    .fav-item { background: #fff; border-radius: 14px; box-shadow: 0 2px 8px #e0e0e0; display: flex; align-items: center; margin-bottom: 18px; overflow: hidden; transition: box-shadow 0.2s; }
    .fav-item:hover { box-shadow: 0 6px 24px #ff880044; }
    .fav-img { width: 90px; height: 70px; object-fit: cover; border-radius: 10px; margin: 12px; background: #eee; flex-shrink: 0; }
    .fav-info { flex: 1; display: flex; flex-direction: column; justify-content: center; min-width: 0; }
    .fav-title { font-size: 1.08rem; font-weight: 600; color: #222; margin: 0 0 8px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .fav-link { text-decoration: none; color: inherit; display: flex; align-items: center; width: 100%; }
    .fav-remove { background: #fff0f0; color: #ff4444; border: none; border-radius: 8px; padding: 6px 14px; font-size: 0.98rem; margin: 0 16px 0 0; cursor: pointer; transition: background 0.2s; }
    .fav-remove:hover { background: #ffdddd; }
    .fav-empty { text-align: center; color: #aaa; font-size: 1.1rem; margin: 60px 0 0 0; }
    @media (max-width: 600px) {
        .fav-header { font-size: 1.05rem; padding: 12px 8px 8px 8px; }
        .fav-list { margin-top: 12px; }
        .fav-img { width: 60px; height: 44px; margin: 8px; border-radius: 7px; }
        .fav-title { font-size: 0.98rem; }
        .fav-remove { font-size: 0.92rem; padding: 4px 10px; margin-right: 8px; }
    }
    </style>
</head>
<body>
    <div class="fav-header">
        <button class="back-btn" onclick="history.back()" title="返回"><span style="font-size:1.2rem;">&#8592;</span></button>
        我的收藏夹
        <span></span>
    </div>
    <div class="fav-list" id="favList"></div>
    <div class="fav-empty" id="favEmpty" style="display:none;">暂无收藏内容</div>
    <script>
    function getFavList(){
        try{ return JSON.parse(localStorage.getItem('favNewsList')||'[]'); }catch(e){ return []; }
    }
    function setFavList(list){
        localStorage.setItem('favNewsList', JSON.stringify(list));
    }
    function removeFav(id){
        var list = getFavList().filter(function(item){ return item.id!=id; });
        setFavList(list);
        renderFavList();
    }
    function renderFavList(){
        var list = getFavList();
        var favList = document.getElementById('favList');
        var favEmpty = document.getElementById('favEmpty');
        favList.innerHTML = '';
        if(!list || list.length===0){
            favEmpty.style.display = '';
            return;
        }
        favEmpty.style.display = 'none';
        list.forEach(function(item){
            var div = document.createElement('div');
            div.className = 'fav-item';
            var img = document.createElement('img');
            img.className = 'fav-img';
            img.src = item.img || '/static/news/default.jpg';
            img.alt = item.title;
            var info = document.createElement('div');
            info.className = 'fav-info';
            var a = document.createElement('a');
            a.className = 'fav-link';
            a.href = item.url;
            a.target = '_blank';
            var title = document.createElement('span');
            title.className = 'fav-title';
            title.innerText = item.title;
            a.appendChild(title);
            info.appendChild(a);
            var btn = document.createElement('button');
            btn.className = 'fav-remove';
            btn.innerText = '取消收藏';
            btn.onclick = function(){ removeFav(item.id); };
            div.appendChild(img);
            div.appendChild(info);
            div.appendChild(btn);
            favList.appendChild(div);
        });
    }
    renderFavList();
    </script>
</body>
</html> 