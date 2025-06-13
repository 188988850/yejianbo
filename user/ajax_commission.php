$lastrow=$DB->getRow("select * from shua_withdraws where zid='{$userrow['zid']}' order by addtime desc limit 1");
$row=$DB->getRow("select * from shua_site where zid='{$userrow['zid']}' limit 1"); 