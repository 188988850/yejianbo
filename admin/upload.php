<?php
require_once '../admin_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $targetDir = '../static/news/';
    $fileName = basename($_FILES['file']['name']);
    $targetFile = $targetDir . $fileName;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        echo "上传成功: <a href='/static/news/$fileName' target='_blank'>$fileName</a>";
    } else {
        echo "上传失败";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>素材上传</title>
</head>
<body>
    <h1>素材上传</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">上传</button>
    </form>
</body>
</html>