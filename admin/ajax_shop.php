<?php
include("../includes/common.php");
if ($islogin == 1) {
} else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$act = isset($_GET['act']) ? daddslashes($_GET['act']) : null;

@header('Content-Type: application/json; charset=UTF-8');

if (!checkRefererHost()) exit('{"code":403}');

switch ($act) {
    case 'getTool':
        $tid  = intval($_GET['tid']);
        $rows = $DB->getRow("select * from shua_tools where tid='$tid' limit 1");
        if (!$rows)
            exit('{"code":-1,"msg":"商品不存在"}');
        $scriptpath   = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $scriptpath   = substr($scriptpath, 0, strrpos($scriptpath, '/'));
        $scriptpath   = substr($scriptpath, 0, strrpos($scriptpath, '/'));
        $siteurl      = (is_https() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $scriptpath . '/';
        $rows['link'] = $siteurl . '?cid=' . $rows['cid'] . '&tid=' . $rows['tid'];
        $result       = array("code" => 0, "msg" => "succ", "data" => $rows);
        exit(json_encode($result));
        break;
    case 'getPrice':
        $tid  = intval($_GET['tid']);
        $rows = $DB->getRow("select * from shua_tools where tid='$tid' limit 1");
        if (!$rows)
            exit('{"code":-1,"msg":"商品不存在"}');
        if ($_SESSION['priceselect']) {
            $priceselect = $_SESSION['priceselect'];
        } else {
            $rs          = $DB->query("SELECT * FROM shua_price order by id asc");
            $priceselect = '<option value="0">不使用加价模板</option>';
            while ($res = $rs->fetch()) {
                $kind        = $res['kind'] == 1 ? '元' : '倍';
                $priceselect .= '<option value="' . $res['id'] . '" kind="' . $res['kind'] . '" p_2="' . $res['p_2'] . '" p_1="' . $res['p_1'] . '" p_0="' . $res['p_0'] . '" >' . $res['name'] . '(' . $res['p_2'] . $kind . '|' . $res['p_1'] . $kind . '|' . $res['p_0'] . $kind . ')</option>';
            }
        }
        $data   = '<div class="form-group"><div class="input-group"><div class="input-group-addon">成本价格</div><input type="text" id="price" value="' . $rows['price'] . '" class="form-control" required onkeyup="changePrice()" disabled/></div></div>
	<div class="form-group"><div class="input-group"><div class="input-group-addon">加价模板</div><select class="form-control" id="prid" onchange="changePrice()">' . $priceselect . '</select></div></div>
<table class="table table-striped table-bordered table-condensed">
<tbody>
<tr align="center"><td>销售价格</td><td>分站站长价格</td><td>顶级合伙人价格</td></tr>
<tr>
<td><input type="text" id="price_s" value="' . $rows['price'] . '" class="form-control input-sm" disabled/></td>
<td><input type="text" id="cost_s" value="' . $rows['cost'] . '" class="form-control input-sm" disabled/></td>
<td><input type="text" id="cost2_s" value="' . $rows['cost2'] . '" class="form-control input-sm" disabled/></td>
</tr>
</table>
	<input type="submit" id="save" onclick="editPrice(' . $tid . ')" class="btn btn-primary btn-block" value="保存">
	<script>$("#prid").val(' . $rows['prid'] . ');</script>';
        $result = array("code" => 0, "msg" => "succ", "data" => $data);
        exit(json_encode($result));
        break;
    case 'editPrice':
        adminpermission('shop', 2);
        $tid  = intval($_POST['tid']);
        $rows = $DB->getRow("select * from shua_tools where tid='$tid' limit 1");
        if (!$rows)
            exit('{"code":-1,"msg":"商品不存在"}');
        $prid = intval($_POST['prid']);
        if ($prid == 0) {
            $price = $_POST['price_s'];
            $cost  = $_POST['cost_s'];
            $cost2 = $_POST['cost2_s'];
        } else {
            $price = $_POST['price'];
            $cost  = 0;
            $cost2 = 0;
        }
        if ($DB->exec("UPDATE `shua_tools` SET `price`='$price',`cost`='$cost',`cost2`='$cost2',`prid`='$prid' WHERE `tid`='{$tid}'") !== false)
            exit('{"code":0,"msg":"succ"}');
        else
            exit('{"code":-1,"msg":"修改商品失败！' . $DB->error() . '"}');
        break;
    case 'getAllPrice':
        if ($_SESSION['priceselect']) {
            $priceselect = $_SESSION['priceselect'];
        } else {
            $rs          = $DB->query("SELECT * FROM shua_price order by id asc");
            $priceselect = '<option value="0">不使用加价模板</option>';
            while ($res = $rs->fetch()) {
                $kind        = $res['kind'] == 1 ? '元' : '倍';
                $priceselect .= '<option value="' . $res['id'] . '" kind="' . $res['kind'] . '" p_2="' . $res['p_2'] . '" p_1="' . $res['p_1'] . '" p_0="' . $res['p_0'] . '" >' . $res['name'] . '(' . $res['p_2'] . $kind . '|' . $res['p_1'] . $kind . '|' . $res['p_0'] . $kind . ')</option>';
            }
        }
        $data   = '<div class="form-group"><div class="input-group"><select class="form-control" name="prid_n">' . $priceselect . '</select></div></div>
	<input type="submit" id="save" onclick="editAllPrice()" class="btn btn-primary btn-block" value="保存">';
        $result = array("code" => 0, "msg" => "succ", "data" => $data);
        exit(json_encode($result));
        break;
    case 'editAllPrice':
        adminpermission('shop', 2);
        $prid     = intval($_POST['prid']);
        $checkbox = $_POST['checkbox'];
        $i        = 0;
        foreach ($checkbox as $tid) {
            $DB->exec("update shua_tools set prid={$prid},`cost`='0',`cost2`='0' where tid='$tid' limit 1");
            $i++;
        }
        exit('{"code":0,"msg":"成功改变' . $i . '个商品"}');
        break;
    case 'reset_sort':
        $cid = intval($_POST['cid']);
        $sds = $DB->exec("UPDATE shua_tools SET sort=tid WHERE cid='$cid'");
        if ($sds !== false) {
            exit('{"code":0,"msg":"重置成功"}');
        } else {
            exit('{"code":-1,"msg":"重置失败' . $DB->error() . '"}');
        }
        break;
    case 'change_shopname':
        $oldName = trim($_POST['oldName']);
        $newName = trim($_POST['newName']);
        if (!$newName || !$oldName) exit('{"code":-1,"msg":"不能为空"}');
        $sds = $DB->exec("UPDATE shua_tools SET name=replace(name,:oldName,:newName) WHERE 1", [':oldName' => $oldName, ':newName' => $newName]);
        if ($sds !== false) {
            exit('{"code":0,"msg":"批量替换成功"}');
        } else {
            exit('{"code":-1,"msg":"批量替换失败' . $DB->error() . '"}');
        }
        break;
    case 'change_inputs':
        $oldName = trim($_POST['oldName']);
        $newName = trim($_POST['newName']);
        if (!$newName || !$oldName) exit('{"code":-1,"msg":"不能为空"}');
        if ($oldName == '下单账号')
            $sds = $DB->exec("UPDATE shua_tools SET input=replace(input,:oldName,:newName) WHERE 1", [':oldName' => $oldName, ':newName' => $newName]);
        $sds2 = $DB->exec("UPDATE shua_tools SET inputs=replace(inputs,:oldName,:newName) WHERE 1", [':oldName' => $oldName, ':newName' => $newName]);
        if ($sds !== false && $sds2 !== false) {
            exit('{"code":0,"msg":"批量替换成功"}');
        } else {
            exit('{"code":-1,"msg":"批量替换失败' . $DB->error() . '"}');
        }
        break;
    case 'shop_move':
        adminpermission('shop', 2);
        $cid = intval($_POST['cid']);
        if (!$cid) exit('{"code":-1,"msg":"请选择分类"}');
        $checkbox = $_POST['checkbox'];
        $i        = 0;
        foreach ($checkbox as $tid) {
            $DB->exec("update shua_tools set cid='$cid' where tid='$tid' limit 1");
            $i++;
        }
        exit('{"code":0,"msg":"成功移动' . $i . '个商品"}');
        break;
    case 'shop_change':
        adminpermission('shop', 2);
        $aid      = $_POST['aid'];
        $checkbox = $_POST['checkbox'];
        $i        = 0;
        foreach ($checkbox as $tid) {
            if ($aid == 1) {
                $DB->exec("update shua_tools set active=1 where tid='$tid' limit 1");
            } elseif ($aid == 2) {
                $DB->exec("update shua_tools set active=0 where tid='$tid' limit 1");
            } elseif ($aid == 3) {
                $DB->exec("update shua_tools set close=0 where tid='$tid' limit 1");
            } elseif ($aid == 4) {
                $DB->exec("update shua_tools set close=1 where tid='$tid' limit 1");
            } elseif ($aid == 5) {
                $DB->exec("DELETE FROM shua_tools WHERE tid='$tid' limit 1");
            } elseif ($aid == 6) {
                $DB->exec("insert into `shua_tools` (`cid`,`name`,`price`,`cost`,`cost2`,`prid`,`prices`,`input`,`inputs`,`desc`,`alert`,`shopimg`,`value`,`is_curl`,`curl`,`shequ`,`goods_id`,`goods_type`,`goods_param`,`repeat`,`multi`,`min`,`max`,`validate`,`valiserv`,`sort`,`active`) select `cid`,`name`,`price`,`cost`,`cost2`,`prid`,`prices`,`input`,`inputs`,`desc`,`alert`,`shopimg`,`value`,`is_curl`,`curl`,`shequ`,`goods_id`,`goods_type`,`goods_param`,`repeat`,`multi`,`min`,`max`,`validate`,`valiserv`,`sort`,`active` from `shua_tools` where `tid` = '$tid'");
            }
            $i++;
        }
        exit('{"code":0,"msg":"成功改变' . $i . '个商品"}');
        break;
    case 'delTool':
        adminpermission('shop', 2);
        $tid = intval($_GET['tid']);
        $sql = "DELETE FROM shua_tools WHERE tid='$tid' limit 1";
        if ($DB->exec($sql) !== false) {
            $DB->exec("DELETE FROM shua_orders WHERE tid='$tid'");
            exit('{"code":0,"msg":"删除商品成功！"}');
        } else
            exit('{"code":-1,"msg":"删除商品失败！' . $DB->error() . '"}');
        break;
     case 'zhiding':
    adminpermission('shop', 2);
    $tid = intval($_GET['tid']);
    $toptime = date('Y-m-d H:i:s');
   $sql = "UPDATE shua_duanju SET sort=999, addtime='$toptime' WHERE tid='$tid' limit 1";
    if ($DB->exec($sql)!== false) {
        exit('{"code":0,"msg":"置顶短剧成功！"}');
    } else
        exit('{"code":-1,"msg":"置顶短剧失败！'. $DB->error(). '"}');
    break;
          case 'delTool2':
        adminpermission('shop', 2);
        $tid = intval($_GET['tid']);
        $sql = "DELETE FROM shua_duanju WHERE tid='$tid' limit 1";
        if ($DB->exec($sql) !== false) {
           
            exit('{"code":0,"msg":"删除短剧成功！"}');
        } else
            exit('{"code":-1,"msg":"删除短剧失败！' . $DB->error() . '"}');
        break;
    case 'setTools': //商品上下架
        adminpermission('shop', 2);
        $tid = intval($_GET['tid']);
        if (isset($_GET['active'])) {
            $active = intval($_GET['active']);
            $DB->exec("update shua_tools set active='$active' where tid='{$tid}'");
        } else {
            $close = intval($_GET['close']);
            $DB->exec("update shua_tools set close='$close' where tid='{$tid}'");
        }
        exit('{"code":0,"msg":"succ"}');
        break;
         case 'setTools2': //取消置顶
        adminpermission('shop', 2);
        $tid = intval($_GET['tid']);
      
        $sql = "UPDATE shua_duanju SET sort=10  WHERE tid='$tid' limit 1";
    if ($DB->exec($sql)!== false) {
        exit('{"code":0,"msg":"取消置顶！"}');
    } else
        exit('{"code":-1,"msg":"取消置顶失败！'. $DB->error(). '"}');
        break;
    case 'setToolSort': //排序操作
        adminpermission('shop', 2);
        $cid  = intval($_GET['cid']);
        $tid  = intval($_GET['tid']);
        $sort = intval($_GET['sort']);
        if (setToolSort($cid, $tid, $sort)) {
            exit('{"code":0,"msg":"succ"}');
        } else {
            exit('{"code":-1,"msg":"失败"}');
        }
        break;
    case 'setStock': //设置商品库存
        adminpermission('shop', 2);
        $tid = intval($_POST['tid']);
        $num = trim($_POST['num']);
        if ($num == '') {
            $DB->exec("update shua_tools set stock=NULL where tid='{$tid}'");
        } else {
            $num = intval($num);
            $DB->exec("update shua_tools set stock='$num' where tid='{$tid}'");
        }
        exit('{"code":0,"msg":"设置库存成功"}');
        break;
    case 'editAllStock': //批量设置商品库存
        adminpermission('shop', 2);
        $num      = trim($_POST['stock']);
        $checkbox = $_POST['checkbox'];
        $i        = 0;
        foreach ($checkbox as $tid) {
            if ($num == '') {
                $DB->exec("update shua_tools set stock=NULL where tid='{$tid}' and is_curl!=4");
            } else {
                $num = intval($num);
                $DB->exec("update shua_tools set stock='$num' where tid='{$tid}' and is_curl!=4");
            }
            $i++;
        }
        exit('{"code":0,"msg":"成功改变' . $i . '个商品"}');
        break;


    case 'getClassList': //获取对接分类列表
        $shequ = intval($_POST['shequ']);
        $row   = $DB->getRow("select * from shua_shequ where id='$shequ' limit 1");
        $list  = third_call($row['type'], $row, 'class_list');
        if (!is_array($list)) $result = array('code' => -1, 'msg' => $list);
        else $result = array('code' => 0, 'msg' => 'succ', 'type' => $row['type'], 'data' => $list);
        exit(json_encode($result));
        break;
    case 'getGoodsList': //获取对接商品列表
        $shequ   = intval($_POST['shequ']);
        $classid = intval($_POST['classid']);
        $row     = $DB->getRow("select * from shua_shequ where id='$shequ' limit 1");
        $list    = third_call($row['type'], $row, 'goods_list', [
            $classid ,
            intval($_POST['page'] ?? 1) ,
            intval($_POST['limit'] ?? 50),
            daddslashes($_POST['term'] ?? ''),
        ]);
        if (!is_array($list)) $result = array('code' => -1, 'msg' => $list);
        else $result = array('code' => 0, 'msg' => 'succ', 'type' => $row['type'], 'data' => $list);

        exit(json_encode($result));
        break;
    case 'getGoodsParam': //获取对接参数名
        $shequ   = intval($_POST['shequ']);
        $goodsid = intval($_POST['goodsid']);
        $row     = $DB->getRow("select * from shua_shequ where id='$shequ' limit 1");
        $result  = third_call($row['type'], $row, 'goods_info', [$goodsid]);
        if (!is_array($result)) {
            $error          = $result;
            $result         = array();
            $result['code'] = -1;
            $result['msg']  = $error;
        } else {
            $result['code'] = 0;
        }
        exit(json_encode($result));
        break;


    case 'addPriceRule': //添加加价模板
        adminpermission('price', 2);
        $name = trim(daddslashes($_POST['name']));
        $kind = intval($_POST['kind']);
        $p_2  = trim(daddslashes($_POST['p_2']));
        $p_1  = trim(daddslashes($_POST['p_1']));
        $p_0  = trim(daddslashes($_POST['p_0']));
        if ($name == null || $p_2 == null || $p_1 == null || $p_0 == null) {
            exit('{"code":-1,"msg":"请确保各项不能为空！"}');
        } elseif ($p_2 > $p_1) {
            exit('{"code":-1,"msg":"专业版加价不能高于普及版加价"}');
        } elseif ($p_2 > $p_0) {
            exit('{"code":-1,"msg":"专业版加价不能高于普通用户加价"}');
        } elseif ($p_1 > $p_0) {
            exit('{"code":-1,"msg":"普及版加价不能高于普通用户加价"}');
        } elseif ($DB->getRow("select * from shua_price where name='$name' limit 1")) {
            exit('{"code":-1,"msg":"模板名称已存在"}');
        }
        $sql = "insert into `shua_price` (`kind`,`name`,`p_0`,`p_1`,`p_2`) values ('" . $kind . "','" . $name . "','" . $p_0 . "','" . $p_1 . "','" . $p_2 . "')";
        if ($DB->exec($sql) !== false) {
            $CACHE->clear('pricerules');
            exit('{"code":0,"msg":"添加加价模板成功！"}');
        } else {
            exit('{"code":-1,"msg":"添加加价模板失败！' . $DB->error() . '"}');
        }
        break;
    case 'editPriceRule': //修改加价模板
        adminpermission('price', 2);
        $id   = intval($_POST['prid']);
        $name = trim(daddslashes($_POST['name']));
        $kind = intval($_POST['kind']);
        $p_2  = trim(daddslashes($_POST['p_2']));
        $p_1  = trim(daddslashes($_POST['p_1']));
        $p_0  = trim(daddslashes($_POST['p_0']));
        if ($name == null || $p_2 == null || $p_1 == null || $p_0 == null) {
            exit('{"code":-1,"msg":"请确保各项不能为空！"}');
        } elseif ($p_2 > $p_1) {
            exit('{"code":-1,"msg":"专业版加价不能高于普及版加价"}');
        } elseif ($p_2 > $p_0) {
            exit('{"code":-1,"msg":"专业版加价不能高于普通用户加价"}');
        } elseif ($p_1 > $p_0) {
            exit('{"code":-1,"msg":"普及版加价不能高于普通用户加价"}');
        } elseif ($DB->getRow("select * from shua_price where id!=$id and name='$name' limit 1")) {
            exit('{"code":-1,"msg":"模板名称已存在"}');
        }
        $sql = "update shua_price set kind='$kind',name='$name',p_2='$p_2',p_1='$p_1',p_0='$p_0' where id='{$id}'";
        if ($DB->exec($sql) !== false) {
            $CACHE->clear('pricerules');
            exit('{"code":0,"msg":"修改加价模板成功！"}');
        } else {
            exit('{"code":-1,"msg":"修改加价模板失败！' . $DB->error() . '"}');
        }
        break;
    case 'getPriceRule':
        $id          = intval($_GET['id']);
        $row         = $DB->getRow("select * from shua_price where id='$id' limit 1");
        $row['code'] = 0;
        exit(json_encode($row));
        break;
    case 'delPriceRule':
        adminpermission('price', 2);
        $id  = intval($_GET['id']);
        $sql = "DELETE FROM shua_price WHERE id='$id' limit 1";
        if ($DB->exec($sql) !== false) {
            $CACHE->clear('pricerules');
            exit('{"code":0,"msg":"删除成功！"}');
        } else {
            exit('{"code":-1,"msg":"删除失败！' . $DB->error() . '"}');
        }
        break;
    case 'changePriceRule':
        adminpermission('price', 2);
        $id   = intval($_POST['id']);
        $cids = $_POST['cids'];
        $sqls = '';
        foreach ($cids as $cid) {
            $sqls .= $cid . ',';
        }
        $sqls = trim($sqls, ',');
        $sql  = "UPDATE shua_tools SET prid='$id' WHERE cid IN ($sqls) AND price>0";
        if ($count = $DB->exec($sql)) {
            exit('{"code":0,"msg":"成功更改' . $count . '个商品的加价模板"}');
        } else {
            exit('{"code":-1,"msg":"更改失败！' . $DB->error() . '"}');
        }
        break;

   case 'goodslistbycid':
    $shequ = intval($_POST['shequ']);
    $cid   = isset($_POST['cid'])? intval($_POST['cid']) : 0;
    $row   = $DB->getRow("select * from shua_shequ where id='$shequ' limit 1");
    if ($row['type'] == 'daishua') {
        $rows = third_call('daishua', $row, 'goods_list_by_cid', [$cid]);
        if (is_array($rows)) {
            foreach ($rows as &$item) {
                // 查询数据表 shua_tools 的 goods_id 是否等于当前 item 的 tid
                $tid = $item['tid'];
                $checkQuery = "SELECT COUNT(*) as count FROM shua_tools WHERE goods_id = $tid";
                $checkResult = $DB->getRow($checkQuery);
                $item['added_flag'] = $checkResult['count'] > 0? 1 : 0;
            }
            $result['code'] = 0;
            $result['msg'] = 'succ';
            $result['data'] = $rows;
        } else {
            $result['code'] = -1;
            $result['msg'] = $rows;
        }
    } else {
        exit('{"code":-1,"msg":"该对接网站类型不支持批量添加商品"}');
    }
    exit(json_encode($result));
    break;


    case 'batchaddgoods':
        $shequ = isset($_POST['shequ']) ? intval($_POST['shequ']) : exit('{"code":-1,"msg":"no shequ"}');
        $mcid  = isset($_POST['mcid']) ? intval($_POST['mcid']) : exit('{"code":-1,"msg":"no mcid"}');
        $prid  = isset($_POST['prid']) ? intval($_POST['prid']) : exit('{"code":-1,"msg":"no prid"}');
        if (count($_POST['list']) == 0) exit('{"code":-1,"msg":"请至少选中一个商品"}');
        if ($_POST['mcid'] == 'new') {
            $sort = $DB->getColumn("select sort from shua_class order by sort desc limit 1");
            $sql  = "insert into `shua_class` (`name`,`shopimg`,`sort`,`active`) values (:name,:shopimg,:sort,1)";
            if (!$DB->exec($sql, [':name' => $_POST['cname'], ':shopimg' => $_POST['cimg'], ':sort' => $sort + 1])) {
                exit('{"code":-1,"msg":"新建分类失败！' . $DB->error() . '"}');
            }
            $mcid = $DB->lastInsertId();
        }
        $add_success    = 0;
        $update_success = 0;
        foreach ($_POST['list'] as $res) {
            $row = json_decode($res, true);
            if (!$row || !$row['tid']) continue;
            $tool = $DB->getRow("SELECT * FROM shua_tools WHERE shequ=:shequ AND goods_id=:goods_id LIMIT 1", [':shequ' => $shequ, ':goods_id' => $row['tid']]);
            if ($tool) {
                $sql  = "UPDATE `shua_tools` SET `cid`=:cid,`name`=:name,`price`=:price,`prid`=:prid,`cost`=:cost,`cost2`=:cost2,`prices`=:prices,`input`=:input,`inputs`=:inputs,`desc`=:desc,`alert`=:alert,`shopimg`=:shopimg,`value`=:value,`is_curl`=:is_curl,`curl`=:curl,`shequ`=:shequ,`goods_id`=:goods_id,`goods_type`=:goods_type,`goods_param`=:goods_param,`repeat`=:repeat,`multi`=:multi,`min`=:min,`max`=:max,`validate`=:validate,`valiserv`=:valiserv,`close`=:close WHERE `tid`=:tid";
                $data = [':cid' => $mcid, ':name' => $row['name'], ':price' => $row['price'], ':cost' => 0, ':cost2' => 0, ':prid' => $prid, ':prices' => '', ':input' => $row['input'], ':inputs' => $row['inputs'], ':desc' => $row['desc'], ':alert' => $row['alert'], ':shopimg' => $row['shopimg'], ':value' => 1, ':is_curl' => 2, ':curl' => null, ':shequ' => $shequ, ':goods_id' => $row['tid'], ':goods_type' => $row['isfaka'] ? '1' : '0', ':goods_param' => null, ':repeat' => $row['repeat'], ':multi' => $row['multi'], ':min' => $row['min'], ':max' => $row['max'], ':validate' => $row['validate'], ':valiserv' => $row['valiserv'], ':close' => $row['close'], ':tid' => $tool['tid']];
                $DB->exec($sql, $data);
                $update_success++;
            } else {
                $sql  = "INSERT INTO `shua_tools` (`cid`,`name`,`price`,`cost`,`cost2`,`prid`,`prices`,`input`,`inputs`,`desc`,`alert`,`shopimg`,`value`,`is_curl`,`curl`,`shequ`,`goods_id`,`goods_type`,`goods_param`,`repeat`,`multi`,`min`,`max`,`validate`,`valiserv`,`close`,`active`,`addtime`) VALUES (:cid,:name,:price,:cost,:cost2,:prid,:prices,:input,:inputs,:desc,:alert,:shopimg,:value,:is_curl,:curl,:shequ,:goods_id,:goods_type,:goods_param,:repeat,:multi,:min,:max,:validate,:valiserv,:close,:active,NOW())";
                $data = [':cid' => $mcid, ':name' => $row['name'], ':price' => $row['price'], ':cost' => 0, ':cost2' => 0, ':prid' => $prid, ':prices' => '', ':input' => $row['input'], ':inputs' => $row['inputs'], ':desc' => $row['desc'], ':alert' => $row['alert'], ':shopimg' => $row['shopimg'], ':value' => 1, ':is_curl' => 2, ':curl' => null, ':shequ' => $shequ, ':goods_id' => $row['tid'], ':goods_type' => $row['isfaka'] ? '1' : '0', ':goods_param' => null, ':repeat' => $row['repeat'], ':multi' => $row['multi'], ':min' => $row['min'], ':max' => $row['max'], ':validate' => $row['validate'], ':valiserv' => $row['valiserv'], ':close' => $row['close'], ':active' => 1];
                $DB->exec($sql, $data);
                $add_success++;
            }
        }
        $result = ['code' => 0, 'msg' => '成功添加' . $add_success . '个商品，更新' . $update_success . '个商品！'];
        exit(json_encode($result));
        break;

    default:
        exit('{"code":-4,"msg":"No Act"}');
        break;
}