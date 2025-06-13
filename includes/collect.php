<?php
if(!defined('IN_CRONLITE'))exit();

function collectGoods($tid) {
    global $DB;
    
    $url = 'https://web.aimallol.com/api.php';
    $data = array(
        'user' => '18888955100',
        'pass' => 'wangji520',
        'act' => 'goodsdetail',
        'tid' => $tid
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    if($result['code'] == 0){
        $goods = $result['data'];
        
        // 检查商品是否已存在
        $row = $DB->getRow("SELECT * FROM shua_goods WHERE tid=:tid limit 1", array(':tid'=>$tid));
        if($row){
            return array('code'=>-1, 'msg'=>'该商品已存在');
        }
        
        // 插入商品数据
        $data = array(
            'tid' => $goods['tid'],
            'name' => $goods['name'],
            'price' => $goods['price'],
            'cost' => $goods['cost'],
            'class' => $goods['class'],
            'content' => $goods['content'],
            'addtime' => date("Y-m-d H:i:s")
        );
        
        if($DB->insert('goods', $data)){
            return array('code'=>0, 'msg'=>'采集成功');
        }else{
            return array('code'=>-1, 'msg'=>'采集失败');
        }
    }else{
        return array('code'=>-1, 'msg'=>$result['msg']);
    }
}

function collectClass($cid) {
    global $DB;
    
    $url = 'https://web.aimallol.com/api.php';
    $data = array(
        'user' => '18888955100',
        'pass' => 'wangji520',
        'act' => 'classdetail',
        'cid' => $cid
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    if($result['code'] == 0){
        $class = $result['data'];
        
        // 检查分类是否已存在
        $row = $DB->getRow("SELECT * FROM shua_class WHERE cid=:cid limit 1", array(':cid'=>$cid));
        if($row){
            return array('code'=>-1, 'msg'=>'该分类已存在');
        }
        
        // 插入分类数据
        $data = array(
            'cid' => $class['cid'],
            'name' => $class['name'],
            'sort' => $class['sort'],
            'addtime' => date("Y-m-d H:i:s")
        );
        
        if($DB->insert('class', $data)){
            return array('code'=>0, 'msg'=>'采集成功');
        }else{
            return array('code'=>-1, 'msg'=>'采集失败');
        }
    }else{
        return array('code'=>-1, 'msg'=>$result['msg']);
    }
} 