<?php
// ソースチェック用
//header('Content-type: text/plain; charset=UTF-8');
//header('Content-Transfer-Encoding: binary');

ini_set('display_errors',1);

require_once('config.php');

define("mf_sendmail", '/usr/sbin/sendmail');
define("mf_nkf", '/usr/local/bin/nkf');

define("mf_mheader", dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mheader.tpl');
define("mf_mbody", dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mbody.tpl');
define("mf_message_file", dirname(__FILE__) . DIRECTORY_SEPARATOR . 'internal_message.txt');

define("mf_session_name", 'iiw_contact');
define("mf_db_server", 'blcsv41.iadi.co.jp');
define("mf_dbname", 'imageworks_mf_dev');
define("mf_dbuser", 'mshibata');
define("mf_dbpasswd", 'mshibata');

// echo "<pre>";
// var_dump($_SERVER);
// echo "</pre>";

// Global Function
define("gucchi_log", dirname(__FILE__) . DIRECTORY_SEPARATOR . 'log/contact.log');
require_once('gucchi/globalFunction.php');
require_once('gucchi/validateFunction.php');
require_once('gucchi/formAssistFunction.php');
require_once('mshiba/globalFunction.php');

// ログの出力方法は２通り
//$testMsg = "Log TEST";
//writeLog($testMsg);
//$log->info($testMsg);

// Static Class
require_once('lang.php');
require_once('classDb.php');
require_once('classHtml.php');
require_once('classFunction.php');
// Static Class lib
require_once('Util.class.php');
require_once('UtilDate.class.php');
require_once('UtilDebug.class.php');
require_once('UtilTemplate.class.php');
require_once('UtilValidate.class.php');

// Dynamic Class
require_once('category.class.php');

/*
$c = new category();
$s1 = array("name" => "category", "id" => "", "class" => "");
echo $c -> makeSelect($s1, 0);
//var_dump($c->selectIdNameSc());
//var_dump($c->selectIdName());
 */
?>
