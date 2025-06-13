<?php

start();

function start(){
   $ids = file_get_contents(dirname(__FILE__)."/log.php");
   if(empty($ids)){
       echo '未获取到待执行任务,执行重新获取！';
       get_list();
       start();
   }else{
       echo '检查到待执行任务,开始执行！';
       $arr = explode(',', $ids);
       $tasksToExecute = array_slice($arr, 0, 10, true); 
       if(count($arr) > 10){
           $remainingTasks = array_slice($arr, 10, null, true);
           $str = implode(',',$remainingTasks);
           file_put_contents(dirname(__FILE__)."/log.php",$str);
       }
       
       caiji($tasksToExecute);
   } 
}


function get_list(){
    $jsonData = curlGet('http://127.0.0.1:6666/video_api/shua_videolist.php');
    $videos = json_decode($jsonData, true);
    $ids = '';
    foreach($videos as $k=>$v){
        if(empty($ids)){
            $ids = $v['xingya_id'];
        }else{
            $ids .= ','.$v['xingya_id'];
        }
    }

    file_put_contents(dirname(__FILE__)."/log.php",$ids);
}


// set_time_limit(0);
function curlGet($url) {
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $response = curl_exec($ch); 
    curl_close($ch); 
    return $response;
}

function caiji($ids){
    echo '执行开始！';
    $startTime = microtime(true);  
    foreach ($ids as $xingyaId) {
        if ($xingyaId != '0') {
            echo "正在处理 xingya_id: {$xingyaId} 的视频。\n";
            $detailJson = curlGet("http://127.0.0.1:6666/video_api/get_id.php?theater_parent_id={$xingyaId}");
            $details = json_decode($detailJson, true);
            echo "获取到了详细的视频信息。\n";
            if(isset($details['data']['theaters']) && !empty($details['data']['theaters'])){
                foreach ($details['data']['theaters'] as $theater) {
                    $num = $theater['num'];
                    $videoUrl = $theater['son_video_url'];
                    $newUrl = "http://127.0.0.1:6666/video_api/upurl_video.php?xingya_id={$xingyaId}&num={$num}&url=" . urlencode($videoUrl);
                    $updateResponse = curlGet($newUrl);
                    echo "正在更新视频链接: xingya_id={$xingyaId}, num={$num}, url={$videoUrl}\n";
                    echo "更新视频链接的结果: " . $updateResponse . "\n";
                }
            }else{
                echo "未获取到视频剧集: xingya_id={$xingyaId}\n";
            }
            
        }
    }
    
    $endTime = microtime(true);  
    $executionTime = $endTime - $startTime;  
    file_put_contents(dirname(__FILE__)."/log3.php",var_export([$executionTime],true)); 
    
}


?>