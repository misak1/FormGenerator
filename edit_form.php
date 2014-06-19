<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="utf-8" />
		<title>none</title>
	</head>
	<body>
<?php
function writeFile($filename, $str) {
        clearstatcache();
        $fp=@fopen($filename, 'wb');
        if($fp){
            fwrite($fp, $str . "\n");
            fclose($fp);
            echo "更新しました";
        }
        echo "更新出来ませんでした";
}

if(isset($_POST['form'])){
   writeFile('form.html', $_POST['form']); 
}
?>
<form method="post">
<textarea name="form" style="width:800px;height:400px">
<?php echo file_get_contents('form.html'); ?>
</textarea>
<p>
<input type="button" onclick="submit();" value="更新"/>
</p>
</form>
	</body>
</html>
