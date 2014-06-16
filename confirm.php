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
<hr/>
<?php echo $_POST['name']; ?>
<hr/>

<!-- confirm.php -->
<!-- 再編集 -->
<form id="confirm" method="post" action="edit.php" class="left">
<input type="hidden" name="pagemode" value="edit" />
<?php echo $keytag; ?>
<input type="button" value="return" onclick="javascript:submit();"/>
</form>

<!-- 確定 -->
<form method="post" action="finish.php" class="right">
<input type="hidden" name="pagemode" value="finish" />
<?php echo $keytag; ?>
<input type="button" value="done" onclick="javascript:submit();"/>
</form>
<!-- /confirm.php -->
</body>
</html>
