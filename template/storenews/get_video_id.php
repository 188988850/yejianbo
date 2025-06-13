// get_video_id.php
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['num']) && isset($_POST['pid'])) {
        $num = intval($_POST['num']);
        $pid = intval($_POST['pid']);
        
        $video = $DB->getRow("SELECT id FROM `shua_video` WHERE num='$num' AND pid='$pid' LIMIT 1");
        
        if ($video) {
            echo json_encode(['success' => true, 'id' => $video['id']]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
}
?>