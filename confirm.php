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
<!-- confirm.php -->

<form name="confirm_form" method="post">

<?php
$html = file_get_contents('form.html');
_formGenerator($html);
?>

<?php echo $keytag; ?>

<input type="hidden" name="pagemode" />
<input type="button" value="return" onclick='action_f("edit");' /><!-- 再編集 -->
<input type="button" value="done" onclick='action_f("finish");' /><!-- 確定 -->
</form>
<script type="text/javascript">
function action_f(to){
	var fm = document.confirm_form;
	fm.action = to +".php";
	fm.pagemode.value = to;
	fm.submit();
}
</script>
<!-- /confirm.php -->
</body>
</html>
