<?php
##################################################
# php.ini ローカル設定                            
##################################################

//内部文字コードを変更
//mb_language("uni");
//mb_internal_encoding("utf-8");
//mb_http_input("auto");
//mb_http_output("utf-8");
// デフォルトの言語
ini_set("mbstring.language", "Japanese");
// 内部エンコーディング
ini_set("mbstring.internal_encoding", "utf-8");
//  HTTP 入力エンコーディング変換
ini_set("mbstring.http_input", "pass");
//  HTTP 出力エンコーディング変換
ini_set("mbstring.http_output", "pass");
// HTTP 入力変換
ini_set("mbstring.encoding_translation", "off");
// デフォルトの文字エンコーディング検出順序
ini_set('mbstring.detect_order', 'UTF-8,SJIS,EUC-JP,JIS,ASCII');
// 出力バッファリング
ini_set("output_buffering", "Off");
// 時刻調整
ini_set('date.timezone', 'Asia/Tokyo');
// PHPメモリ上限
ini_set('memory_limit', '512M');
// MySQLタイムアウト
ini_set('mssql.connect_timeout', '180');
// E_NOTICE 以外の全てのエラーを表示する
//ini_set('error_reporting', "E_ALL");
ini_set('error_reporting', "E_ALL ^ E_NOTICE");
// PHPの最終更新時刻を送信  //デバッグ時にはoffにした方がよい
ini_set('last_modified', "on");
//クライアント切断時に処理を実行するように指定
//ignore_user_abort(true);

//スクリプトを強制終了させるまでの許容する最大時間(単位:秒)
set_time_limit(180);

// 毎回読み込むphpを記述
// ini_set('auto_prepend_file','');
// 実行後に読み込むファイル
// ini_set('auto_append_file,'');

// インクルードパス
//$my_path = $_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR ."lib";
$my_path = dirname(__FILE__). DIRECTORY_SEPARATOR ."lib". DIRECTORY_SEPARATOR;
$pear_path = dirname(__FILE__). DIRECTORY_SEPARATOR ."PEAR". DIRECTORY_SEPARATOR;
ini_set("include_path",get_include_path() . PATH_SEPARATOR . $my_path . PATH_SEPARATOR . $pear_path);

##################################################
# セッション設定                                  
##################################################
//セッションの保存先を指定します
//session_save_path("/home/Imageworks/tmp");
//ini_set('session.gc_maxlifetime', 10); // 10秒
//ini_set('session.gc_maxlifetime', 600);
//ガベージコレクトを毎回行うようにします
//ini_set('session.gc_probability', 1);
//ini_set('session.gc_divisor', 1);

##################################################
# 設定読み込み                                    
##################################################
define("MF_SITE_CONF", dirname(__FILE__). DIRECTORY_SEPARATOR . 'config.ini');
$_C = parse_ini_file(MF_SITE_CONF, true);

##################################################
# log4php                                         
##################################################
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR .'lib/log4php/Logger.php');
Logger::configure(dirname(__FILE__). DIRECTORY_SEPARATOR .'lib/log4php/config.xml');
//$log = Logger::getLogger('default');
$log = Logger::getLogger('debugLogger');

?>