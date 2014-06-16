<?php
require_once ('formGenerator.php');
startSession();
formHandler();
$keytag = getKEYTAG();
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>none</title>
</head>
<body>
<!-- <?php echo __FILE__; ?> -->
<!-- index.php -->
<!-- initSession セッションの初期化 -->
<form method="post" action="confirm.php" name="toiawase">
<input type="hidden" name="pagemode" value="confirm" />
<?php echo $keytag; ?>

<input type="text" name="name" value="<?php echo ""; ?>"/>
<input type="button" value="send" onclick="javascript:submit();"/>
</form>
<!-- /index.php -->
</body>
</html>
