<?php
if(!defined('IN_CRONLITE'))die();

$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

if($islogin2==1){
    $price_obj = new \lib\Price($userrow['zid'],$userrow);
}elseif($is_fenzhan == true){
    $price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
    $price_obj = new \lib\Price(1);
}

$uid = $userrow['uid'];

switch($act){
    case 'get_orders': //获取订单列表
        $page = isset($_GET['page'])?intval($_GET['page']):1;
        $limit = 10;
        $offset = ($page-1)*$limit;
        
        $where = "uid='$uid'";
        if(isset($_GET['status'])){
            $status = intval($_GET['status']);
            $where .= " AND status='$status'";
        }
        
        $total = $DB->getColumn("SELECT COUNT(*) FROM shua_orders WHERE $where");
        $orders = $DB->getAll("SELECT * FROM shua_orders WHERE $where ORDER BY id DESC LIMIT $offset,$limit");
        
        $data = array();
        foreach($orders as $order){
            $img = '';
            $name = '';
            $vid = null;
            $product_url = '';
            // 影视订单
            if($order['tid'] == -4){
                $inputArr = explode('|', $order['input']);
                $vid = intval($inputArr[0]);
                $video = $DB->getRow("SELECT * FROM shua_videolist WHERE id='$vid' LIMIT 1");
                if($video){
                    $img = $video['img'];
                    $name = $video['name'];
                    $product_url = '/index.php?mod=video&id=' . $vid;
                }
            }else{
                // 普通商品
                $tool = $DB->getRow("SELECT name,shopimg,tid FROM shua_tools WHERE tid='{$order['tid']}' LIMIT 1");
                if($tool){
                    $img = $tool['shopimg'];
                    $name = $tool['name'];
                    $product_url = '/?mod=buy2&tid=' . $tool['tid'];
                }
            }
            // 图片字段处理
            if(empty($img)){
                $img = '/assets/store/picture/error_img.png';
            }elseif(strpos($img, 'http') === 0){
                // 远程图片
            }else{
                $img = '/uploads/' . ltrim($img, '/');
            }
            $data[] = array(
                'id' => $order['id'],
                'order_no' => $order['order_no'],
                'amount' => number_format($order['amount'],2),
                'commission' => number_format($order['commission'],2),
                'status' => $order['status'],
                'status_text' => $order['status']==0?'处理中':'已完成',
                'addtime' => date('Y-m-d H:i:s',$order['addtime']),
                'img' => $img,
                'name' => $name,
                'vid' => $vid,
                'tid' => $order['tid'],
                'product_url' => $product_url
            );
        }
        
        exit(json_encode(array(
            'code'=>0,
            'msg'=>'success',
            'data'=>array(
                'total'=>$total,
                'list'=>$data,
                'page'=>$page,
                'limit'=>$limit
            )
        )));
        break;

    case 'get_order_detail': //获取订单详情
        $id = intval($_GET['id']);
        $order = $DB->getRow("SELECT * FROM shua_orders WHERE id='$id' AND uid='$uid'");
        if(!$order){
            exit('{"code":-1,"msg":"订单不存在"}');
        }
        
        $data = array(
            'id' => $order['id'],
            'order_no' => $order['order_no'],
            'amount' => number_format($order['amount'],2),
            'commission' => number_format($order['commission'],2),
            'status' => $order['status'],
            'status_text' => $order['status']==0?'处理中':'已完成',
            'addtime' => date('Y-m-d H:i:s',$order['addtime']),
            'remark' => $order['remark']
        );
        
        exit(json_encode(array('code'=>0,'msg'=>'success','data'=>$data)));
        break;

    default:
        exit('{"code":-1,"msg":"未知操作"}');
        break;
}
?> 