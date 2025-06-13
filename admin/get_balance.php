<?php
include("../includes/common.php");
if($islogin==1){
    $userrow = $DB->getRow("SELECT rmb FROM shua_site WHERE zid='{$admin_zid}' limit 1");
    echo $userrow['rmb'];
} else {
    echo '0';
}
?> 