<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="utf-8" />
		<title>none</title>
	</head>
	<body>
		<form action="http://iw01.sakura.ne.jp/param_check.php" method="post">

			<?php
			$html = <<< TAG
<p>
[iwmf label="チェックボックス１" type="radio" name="sex" value="男,女"]<br/>

[iwmf  label="てきすとボックス2"   type="text" value="メールアドレスを入力" name="email"]<br/>

[iwmf type="checkbox" value="りんご,バナナ" label="果物" name="fruits"]<br/>[iwmf type="text" value="ボックス4" name="hoge"]
</p>
TAG;

			// メールテンプレート
			var_dump(strip_tags($html));
		?>
<input type="submit" value="send" />
		</form>
	</body>
</html>

<?php
function http_file_exists($url) {
	clearstatcache();
	$fp = @fopen($file_name, 'ab');
	if ($f) {
		fwrite($fp, date("Y/m/d H:i:s") . " " . $msg . "\n");
		fclose($fp);
	}
	return false;
}
?>