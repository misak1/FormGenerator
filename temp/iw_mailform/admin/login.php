<?php
// -- 必須 -- //
require_once ('../boot.php');
require_once ('admin.class.php');
// -- /必須 -- //

//session_name($GLOBALS['config_ini']['SESSION']['name1']);
session_name(mf_session_name);
session_start();
//UtilDebug::showParam();

$message_title = "";
$message_body = "";
$massageType = 0;
$isHtmlAlertShow = false;

if (isset($_POST["action"]) && $_POST["action"] === "ログイン") {

	$_SESSION["LOGIN_ID"] = $_POST["login_id"];
	$_SESSION["PASSWORD"] = md5($_POST["password"]);

//    echo "<pre>";
//    var_dump($_POST);
//    echo "</pre>";
	$admin = new admin();
//    var_dump($admin -> checkPassword($_POST["login_id"], $_POST["password"]));
	if ((bool)$admin -> checkPassword($_POST["login_id"], $_POST["password"])) {//パスワード確認
		//暗号化してセッションに保存
		//Logger::getLogger('adminLog') -> info('ログインしました。 ' . 'login_user=' . $_POST["login_id"] . ' password=' . $_POST["password"]);
		header("Location:dashboard.php");
	} else {
		session_destroy();
		//セッション破棄
		$message_title = "Failed";
        $message_body = "IDまたはパスワードが違います";
        $massageType = 2;
        $isHtmlAlertShow = true;
	}
//    echo "<pre>";
//    var_dump($_SESSION);
//    echo "</pre>";
}
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title><?php echo _L('LOGIN'); ?> - <?php  echo _L('SITENAME'); ?></title>
		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<!-- CSS -->
		<link href="./css/bootstrap.min.css" rel="stylesheet">
		<!-- JS -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<!-- カスタマイズ用CSS -->
		<link href="./css/style.css" rel="stylesheet">
	</head>
	<body>
		<!-- ヘッダー -->
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href=""><?php echo _L('SITENAME'); ?></a>
				</div>
			</div>
		</div>
		<!-- //ヘッダー -->
		
		<div class="container">

		    <!-- アラート -->
		    <div class="alert_login">
            <?php include ('HtmlAlert.php'); ?>
            </div>
            <!-- //アラート-->
		    
			<div class="login">
				<form action="" method="post" class="well form-inline">
					<div class="control-group">
						<label><?php echo _L('LOGINID'); ?></label>
						<div class="controls">
							<input type="text" name="login_id" placeholder="ID">
						</div>
					</div>
					<div class="control-group">
						<label><?php echo _L('PASSWORD'); ?></label>
						<div class="controls">
							<input type="password" name="password" placeholder="Password">
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<input type="submit" name="action" value="ログイン" class="btn btn-primary">
						</div>
					</div>
				</form>
			</div>
			<div id="footer">
				<?php echo _L('COPYWRITE'); ?>
			</div>
		</div>
		<!-- 下記のJSは必須 -->
		<script src="./js/bootstrap.min.js"></script>
	</body>
</html>
