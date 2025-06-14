<?php
// 勿动 备档
// 数据库连接信息
$host = 'localhost'; // 数据库主机
$dbname = 'xinfaka'; // 数据库名称
$username = 'xinfaka'; // 数据库用户名
$password = '82514a97de548852'; // 数据库密码

try {
    // 创建数据库连接
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 步骤 1: 删除指定的 goods_id 对应的数据行，前提是 shequ 字段为 24
    $goods_ids_to_delete = [
                6221,6222,6226,6227,6233,6245,6249,6293,6346,6347,6348,6350,6351,6352,6353,6355,6357,6359,6360,6361,6364,6365,6366,6367,6375,6377,6382,6383,6385,6387,6388,35816,35818,36081,36082,36084,36165,36166,36167,36168,36174,36176,36179,36203,36207,36209,36213,36215,36217,36218,36260,36262,36264,36265,36267,36271,36272,36274,36275,36279,36280,36283,36284,36285,36286,36290,36340,36341,36390,36391,36393,36398,36400,36402,36403,36404,36406,36407,36408,36409,36412,36413,36414,36422,36423,36425,36431,36433,36434,36440,36441,36487,36490,36494,36496,36500,36510,36511,36514,36533,36604,36617,36618,36624,36625,36626,36638,36640,36642,36643,36652,36658,36661,36673,36674,36675,36677,36680,36681,36685,36624,36705,36710,36711,36715,36719,36724,36728,36737,36738,36752,36762,36763,36767,36768,36774,36777,36778,36780,36783,36786,36788,36790,36795,36798,36724,36805,36807,36812,36813,36816,36818,36823,36837,36838,36866,36877,36887,36898,36946,36961,36965,36977,36242,36243,37005,37013,37020,36952,36934,36933,36931,36886,36880,36851,36831,36821,36810,36806,36769,36753,36735,36734,36731,36730,36729,36727,36726,36723,36722,36720,36712,36708,36704,36703,36702,36694,36693,36692,36691,36689,36688,36687,36684,36683,36682,36669,36660,36609,36530,36448,36726,1
    ];

    // 每次执行的数量限制
    $limit = 50;

    // 分批删除
    while (count($goods_ids_to_delete) > 0) {
        $ids_to_delete = array_splice($goods_ids_to_delete, 0, $limit);
        $ids_placeholder = implode(',', array_fill(0, count($ids_to_delete), '?'));
        
        $stmt = $pdo->prepare("DELETE FROM shua_tools WHERE goods_id IN ($ids_placeholder) AND shequ = 28");
        $stmt->execute($ids_to_delete);
        echo "删除了 " . $stmt->rowCount() . " 行数据。\n";
    }

    // 步骤 2: 更新 shequ 字段为 24 且 price 为 0.1 的数据行，变更为 2.5
    $stmt = $pdo->prepare("UPDATE shua_tools SET price = 2.5 WHERE shequ = 28 AND price = 0.01");
    $stmt->execute();
    echo "更新了 " . $stmt->rowCount() . " 行数据的价格。\n";

    // 步骤 3: 更新 goods_id
    $tid_updates = [
    6219 => 2,
    6220 => 3,
    6223 => 4,
    6224 => 5,
    6225 => 6,
    6244 => 7,
    6250 => 9,
    6251 => 10,
    6294 => 12,
    6295 => 13,
    6349 => 14,
    6354 => 18,
    6356 => 20,
    6358 => 21,
    6362 => 25,
    6363 => 26,
    6368 => 31,
    6369 => 32,
    6372 => 33,
    6373 => 34,
    6374 => 35,
    6376 => 36,
    6378 => 38,
    6379 => 39,
    6380 => 40,
    6381 => 41,
    6384 => 44,
    6386 => 45,
    6389 => 47,
    35545 => 48,
    35546 => 49,
    35548 => 50,
    35815 => 51,
    35819 => 53,
    35820 => 54,
    35935 => 55,
    36085 => 58,
    36170 => 60,
    36175 => 63,
    36178 => 65,
    36204 => 69,
    36205 => 70,
    36206 => 72,
    36208 => 74,
    36634 => 75,
    36212 => 76,
    36216 => 78,
    36239 => 79,
    36247 => 80,
    36261 => 82,
    36263 => 83,
    36266 => 86,
    36269 => 87,
    36270 => 88,
    36277 => 89,
    36278 => 90,
    36281 => 91,
    36282 => 92,
    36960 => 94,
    36287 => 95,
    36288 => 96,
    36289 => 97,
    36291 => 98,
    36292 => 24,
    36342 => 101,
    36517 => 102,
    36370 => 103,
    36371 => 104,
    36392 => 106,
    36394 => 108,
    36395 => 109,
    36396 => 110,
    36397 => 111,
    36324 => 112,
    36401 => 113,
    36410 => 120,
    36411 => 121,
    36417 => 125,
    36419 => 126,
    36421 => 127,
    36424 => 130,
    36426 => 131,
    36427 => 132,
    36430 => 133,
    36432 => 135,
    36435 => 137,
    36436 => 138,
    36437 => 139,
    36438 => 140,
    36439 => 141,
    36442 => 143,
    36443 => 144,
    36444 => 145,
    36445 => 146,
    36447 => 147,
    36449 => 149,
    36450 => 150,
    36451 => 151,
    36452 => 152,
    36453 => 153,
    36454 => 154,
    36456 => 155,
    36459 => 156,
    36461 => 157,
    36462 => 158,
    36463 => 159,
    36464 => 160,
    36465 => 161,
    36466 => 162,
    36467 => 163,
    36468 => 164,
    36469 => 165,
    36470 => 166,
    36471 => 167,
    36473 => 168,
    36474 => 169,
    36475 => 170,
    36476 => 171,
    36477 => 172,
    36478 => 173,
    36479 => 174,
    36482 => 175,
    36484 => 176,
    36486 => 177,
    36488 => 179,
    36489 => 180,
    36491 => 182,
    36492 => 183,
    36493 => 184,
    36498 => 186,
    36424 => 187,
    36501 => 188,
    36502 => 189,
    36504 => 190,
    36505 => 191,
    36506 => 192,
    36507 => 193,
    36508 => 194,
    36509 => 195,
    36405 => 198,
    36518 => 605,
    36519 => 606,
    36521 => 607,
    36528 => 7057,
    36532 => 7079,
    36531 => 7081,
    36529 => 7082,
    36603 => 7093,
    36605 => 7098,
    36513 => 7187,
    36608 => 7188,
    36668 => 7190,
    36632 => 7194,
    36631 => 7195,
    36629 => 7196,
    36628 => 7198,
    36635 => 7124,
    36627 => 7200,
    36639 => 7201,
    36633 => 7202,
    36641 => 7203,
    36637 => 7204,
    36636 => 7205,
    36630 => 7206,
    36648 => 7210,
    36647 => 7211,
    36649 => 7213,
    36650 => 7214,
    36651 => 7215,
    36653 => 7217,
    36646 => 7219,
    36644 => 7220,
    36645 => 7221,
    36654 => 7222,
    36655 => 7223,
    36619 => 7224,
    36663 => 7228,
    36657 => 7229,
    36662 => 7230,
    36659 => 7231,
    36656 => 7232,
    36665 => 7233,
    36666 => 7234,
    36670 => 7237,
    36700 => 7250,
    36686 => 7255,
    36698 => 7258,
    36690 => 7261,
    36696 => 7262,
    36701 => 7263,
    36695 => 7264,
    36709 => 7266,
    36717 => 7269,
    36714 => 7271,
    36713 => 7272,
    36721 => 7273,
    36725 => 7274,
    36739 => 7730,
    36741 => 7731,
    36742 => 7734,
    36716 => 7736,
    36748 => 7740,
    36747 => 7741,
    36746 => 7742,
    36750 => 7746,
    36751 => 7747,
    36555 => 7748,
    36755 => 7750,
    36757 => 7756,
    36756 => 7762,
    36761 => 7777,
    36764 => 7778,
    36765 => 7779,
    36772 => 7811,
    36776 => 7818,
    36775 => 7819,
    36770 => 7820,
    36779 => 7829,
    36785 => 7836,
    36784 => 7837,
    36782 => 7844,
    36789 => 7846,
    36791 => 7857,
    36793 => 7859,
    36794 => 7866,
    36796 => 7869,
    36797 => 7870,
    36800 => 7878,
    36801 => 7884,
    36802 => 7885,
    36809 => 7892,
    36808 => 7893,
    36814 => 7900,
    36815 => 7903,
    36817 => 7904,
    36758 => 7906,
    36819 => 7907,
    36820 => 7908,
    36824 => 7914,
    36825 => 7915,
    36826 => 7918,
    36829 => 7920,
    36830 => 7921,
    36834 => 7922,
    36835 => 7923,
    36856 => 7927,
    36853 => 7929,
    36861 => 7932,
    36854 => 7934,
    36863 => 7936,
    36864 => 7939,
    36865 => 7943,
    36875 => 7948,
    36878 => 7987,
    36873 => 7988,
    36874 => 7241,
    36879 => 7244,
    36247 => 2453,
    36989 => 10018,
    36246 => 10023,
    36249 => 10028,
    36248 => 10036,
    37003 => 10038,
    37000 => 10040,
    36957 => 10045,
    37011 => 10047,
    37019 => 10049,
    37012 => 10052,
    36988 => 10061,
    36982 => 10062,
    36979 => 10066,
    36976 => 10073,
    36241 => 10075,
    36973 => 10076,
    37001 => 10091,
    36985 => 10092,
    36964 => 10109,
    37008 => 10111,
    37007 => 10112,
    37009 => 10122,
    37014 => 10139,
    37016 => 10140,
    36983 => 10160,
    36972 => 10161,
    37017 => 10163,
    37021 => 10169,
    37023 => 10171,
    37024 => 10172,
    37026 => 10175,
    37025 => 10176,
    37027 => 10183,
    37028 => 10185,
    37029 => 10195,
    36181 => 55,
    36211 => 75,
    36344 => 102,
    36606 => 7024,
    36664 => 7227,
    36676 => 7240,
    36745 => 7733,
    36760 => 7770,
    36554 => 7906,
    36822 => 7912,
    36857 => 7928,
    36860 => 7940,
    36870 => 7947,
    36981 => 10065,
    36678 => 7241,
    36718 => 7267,
    36828 => 7919,
    36523 => 10109,
    36679 => 7243,
    36852 => 7938,
    36855 => 7941,
    36862 => 38,
    36867 => 7941,
    36872 => 7986,
    36876 => 7244,
    36744 => 7738,
    36859 => 7935,
    36927 => 10019,
    36944 => 10029,
    36244 => 10085,
    37006 => 10110,
    37015 => 10080,
    37004 => 10093,
    36245 => 10090,
    36240 => 10084,
    36980 => 10072,
    36978 => 10082,
    36974 => 10078,
    36962 => 10027,
    36959 => 10026,
    36956 => 10038,
    36955 => 10042,
    36942 => 7892,
    36929 => 7946,
    36885 => 9826,
    36882 => 9817,
    36869 => 7946,
    36858 => 7946,
    36811 => 7898,
    36792 => 7858,
    36787 => 7802,
    36781 => 7828,
    36773 => 7810,
    36771 => 7724,
    36766 => 7786,
    36759 => 7769,
    36754 => 7752,
    36749 => 7745,
    36743 => 7735,
    36667 => 7235,
    36607 => 7087,
    37033 => 10182,
    37032 => 10198,
    37034 => 10203,
    37035 => 10204,
    37036 => 10205,
    37041 => 10220,
    ];

    // 分批更新
    foreach (array_chunk($tid_updates, $limit, true) as $chunk) {
        foreach ($chunk as $old_goods_id => $new_goods_id) {
            $stmt = $pdo->prepare("UPDATE shua_tools SET goods_id = :new_goods_id WHERE goods_id = :old_goods_id AND shequ = 28");
            $stmt->bindParam(':new_goods_id', $new_goods_id, PDO::PARAM_INT);
            $stmt->bindParam(':old_goods_id', $old_goods_id, PDO::PARAM_INT);
            $stmt->execute();
            echo "将 goods_id $old_goods_id 更新为 $new_goods_id。\n";
        }
    }

} catch (PDOException $e) {
    echo "数据库错误: " . $e->getMessage();
}

// 关闭数据库连接
$pdo = null;
?>