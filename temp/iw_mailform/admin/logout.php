<?php

//認証確認用セッション開始
//$session_name = $GLOBALS['config_ini']['SESSION']['name1'];
//session_name($session_name);
session_name(mf_session_name);
session_start();

$_SESSION = array();

if (isset($_COOKIE[$session_name])) {
    setcookie($session_name, '', time() - 1800, '/');
}

session_destroy();

// ログイン画面へリダイレクト
header('Location: login.php');

exit;
?>