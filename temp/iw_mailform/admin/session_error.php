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
					<a class="brand" href="./dashboard.php"><?php echo _L('SITENAME'); ?></a>
				</div>
			</div>
		</div>
		<!-- //ヘッダー -->
		<div class="container">
			<div class="login">
				セッションエラー
			</div>
			<div id="footer">
				<?php echo _L('COPYWRITE'); ?>
			</div>
		</div>
		<!-- 下記のJSは必須 -->
		<script src="./js/bootstrap.min.js"></script>
	</body>
</html>