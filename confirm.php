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
<form name="confirm_form" method="post">
<input type="hidden" name="pagemode" />
<?php echo $keytag; ?>
<input type="button" value="return" onclick='action_f("edit");'/>

<!-- 確定 -->
<?php echo $keytag; ?>
<input type="button" value="done" onclick='action_f("finish");'/>
</form>
<script type="text/javascript">
function action_f(to){
var fm = document.confirm_form;
fm.action = to +".php";
fm.pagemode = to;
fm.submit();
}
</script>
<!-- /confirm.php -->
</body>
</html>
