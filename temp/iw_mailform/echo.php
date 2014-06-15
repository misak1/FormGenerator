<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-Transfer-Encoding: binary');

require_once ('boot.php');

// $from,$senderは１要素
$from[] = "shibata.misaki@imagica-imageworks.co.jp";
$sender[] = "iiw-contact@imagica-imageworks.co.jp";
// $toは複数可
$to[] = "shibata.misaki@imagica-imageworks.co.jp";
$to[] = "misaki.pink@gmail.com";
$to[] = "misaki.pink@hotmail.co.jp";
$subject = 'PHP/不等号やアンパサンドなどのエスケープ関数・htmlspecialchars';

//echo classFunction::concat_meil($to);
//echo "<br/>";
$mail_from = classFunction::concat_meil($from);
$mail_sender  = classFunction::concat_meil($sender);
$mail_to = classFunction::concat_meil($to);

//echo 'Subject: '.classFunction::subject_encode($subject);
$mail_subject = classFunction::subject_encode($subject);

$mheader = file_get_contents('mheader.tpl');
$mheader = implode($mail_from    , mb_split('%MAIL_FROM%'  , $mheader));
$mheader = implode($mail_sender  , mb_split('%MAIL_SENDER%', $mheader));
$mheader = implode($mail_to      , mb_split('%MAIL_TO%'    ,  $mheader));
$mheader = implode($mail_subject , mb_split('%SUBJECT%'    , $mheader));

// 改行統一
$mheader = classFunction::toCrlf($mheader);

// テストコード
echo $mheader;
echo "\n";
echo "\n";
echo classFunction::binary_dump($mheader);


$category = "テストカテゴリ";
$company = "テスト会社";
$username= "テストゆーざー";
$email= "テストメール";
$tel = "テスト電話番号";
$content= "テスト問い合わせ内容";
$body = file_get_contents('mbody.tpl');

$body = implode($categoryName, mb_split('%CATEGORY%',  $body));
$body = implode($company,      mb_split('%COMPANY%',   $body));
$body = implode($username,     mb_split('%USERNAME%',  $body));
$body = implode($email,        mb_split('%EMAIL%',     $body));
$body = implode($tel,          mb_split('%TEL%',       $body));
$body = implode($content,      mb_split('%CONTENT%',   $body));
$body = implode($ts,           mb_split('%TIMESTAMP%', $body));
$body = classFunction::toCrlf($body);

echo "\n";
echo "\n";

echo $body;

phpinfo();
?>