<?php
/**
 * 收支明细
**/
include("../includes/common.php");
$title='收支明细';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

$todaySumMoney = $DB->getColumn("SELECT COALESCE(SUM(point), 0) FROM shua_points WHERE fufei=1 AND action='消费' AND DATE(addtime) = CURDATE()");
$todaySumMoneyd = $DB->getColumn("SELECT COALESCE(SUM(point), 0) FROM shua_points WHERE duanju=1 AND action='消费' AND DATE(addtime) = CURDATE()");
$yesterdaySumMoney = $DB->getColumn("SELECT COALESCE(SUM(point), 0) FROM shua_points WHERE fufei=1 AND action='消费' AND DATE(addtime) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
$yesterdaySumMoneyd = $DB->getColumn("SELECT COALESCE(SUM(point), 0) FROM shua_points WHERE duanju=1 AND action='消费' AND DATE(addtime) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");

$todaySumMoney1 = $DB->getColumn("SELECT COALESCE(SUM(point), 0) FROM shua_points WHERE fufei=1 AND action='提成' AND DATE(addtime) = CURDATE()");
$todaySumMoney1d = $DB->getColumn("SELECT COALESCE(SUM(point), 0) FROM shua_points WHERE duanju=1 AND action='提成' AND DATE(addtime) = CURDATE()");
$yesterdaySumMoney1 = $DB->getColumn("SELECT COALESCE(SUM(point), 0) FROM shua_points WHERE fufei=1 AND action='提成' AND DATE(addtime) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
$yesterdaySumMoney1d = $DB->getColumn("SELECT COALESCE(SUM(point), 0) FROM shua_points WHERE duanju=1 AND action='提成' AND DATE(addtime) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
$ss = $todaySumMoney - $todaySumMoney1;
$ssd = $todaySumMoneyd - $todaySumMoney1d;
$ss2 = $yesterdaySumMoney - $yesterdaySumMoney1;
$ss2d = $yesterdaySumMoneyd - $yesterdaySumMoney1d;
?>
 <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
    <title>统计页面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 15px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight:500;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="container" align="center">
          <h4>短剧收益<h4>
           <h5>
        <table>
            <tr>
                <th>类目</th>
                <th>金额</th>
                <th>利润</th>
            </tr>
            
            
            <tr>
                <td>今日总收</td>
                <td><?php echo $todaySumMoneyd?></td>
                <td>今日利润</td>
            </tr>
             <tr>
                <td>今日支出</td>
                <td><?php echo $todaySumMoney1d?></td>
                <td><?php echo $ssd?></td>
            </tr>
            
            
            <tr>
                <td>昨日总收</td>
                <td><?php echo $yesterdaySumMoneyd?></td>
                 <td>昨日利润</td>
            </tr>
           
            <tr>
                <td>昨日支出</td>
                <td><?php echo $yesterdaySumMoney1d?></td>
                 <td><?php echo $ss2d?></td>
            </tr>
        </table>
        <h5>
    </div>
    
    <div class="container" align="center">
      <h4>群聊收益<h4>
       <h5>
        <table>
            <tr>
                <th>类目</th>
                <th>金额</th>
                <th>利润</th>
            </tr>
            
            
            <tr>
                <td>今日总收</td>
                <td><?php echo $todaySumMoney?></td>
                <td>今日利润</td>
            </tr>
             <tr>
                <td>今日支出</td>
                <td><?php echo $todaySumMoney1?></td>
                <td><?php echo $ss?></td>
            </tr>
            
            
            <tr>
                <td>昨日总收</td>
                <td><?php echo $yesterdaySumMoney?></td>
                 <td>昨日利润</td>
            </tr>
           
            <tr>
                <td>昨日支出</td>
                <td><?php echo $yesterdaySumMoney1?></td>
                 <td><?php echo $ss2?></td>
            </tr>
        </table>
        <h5>
    </div>
</body>

</html>