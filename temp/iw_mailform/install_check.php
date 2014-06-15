<?php
//内部文字コードを変更
mb_language("uni");
mb_internal_encoding("utf-8");
mb_http_input("auto");
mb_http_output("utf-8");

//http://blog.layer8.sh/ja/2012/03/31/nkf_command_option/
$b = "チーサポ メールフォームからのお問い合わせ";
$cmd = 'echo "' . $b . '" | /usr/local/bin/nkf -jM';
//$cmd = 'echo "'.$b.'" | nkf -jM';
$a = array();
exec($cmd, $a, $status);
if (!$status) {
    echo "nkf Command " . str_repeat("-", 40) . " OK" . "<br/>";
    print("<pre>");
    print_r($a);
    print("</pre>");
} else {
    echo "nkf Command " . str_repeat("-", 40) . " NG" . "<br/>";
}

echo "<br/>";

$sendmail = '/usr/sbin/sendmail -t ';
// MTA の起動
$sm = popen($sendmail, 'w');
if ($sm !== FALSE) {
    echo "sendmail Command " . str_repeat("-", 40) . " OK" . "<br/>";
    pclose($sm);
} else {
    echo "sendmail Command " . str_repeat("-", 40) . " NG" . "<br/>";
}
?>