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
<?php
$html = file_get_contents('form.html');
_formGenerator($html);
?>
<hr/>

<!-- confirm.php -->
<!-- 再編集 -->
<form name="confirm_form" method="post" action="edit.php">
<input type="hidden" name="pagemode" value="edit" />
<?php echo $keytag; ?>
<input type="button" value="return" onclick='javascript:document.confirm_form.action="edit.php";submit();'/>

<!-- 確定 -->
<input type="hidden" name="pagemode" value="finish" />
<?php echo $keytag; ?>
<input type="button" value="done" onclick='javascript:document.confirm_form.action="finish.php";submit();'/>
</form>
<!-- /confirm.php -->
</body>
</html>
