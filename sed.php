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
[ahoge]
[ahoge]
[iwmf type="checkbox" value="りんご,バナナ" label="果物" name="fruits"]<br/>[iwmf type="text" value="ボックス4" name="hoge"]
</p>
TAG;

			// 対象文字列を配列として取り出す。
			preg_match_all('[\[iwmf(.*?)[\]]]', $html, $src);

			echo "<pre>";
			var_dump($src);
			echo "</pre>";
			exit();
			// 置換前文字列
			$frm = array();
			// 置換後文字列
			$dst = array();

			$ary = $src[0];
			$dst = array();
			function replace_f($ary) {
				global $dst;
				foreach ($ary AS $v) {
					// 前後の余分なテキストを削除
					$v = preg_replace('/^\[iwmf/i', '', $v);
					$v = preg_replace('/\]$/i', '', $v);

					$tmp_att = explode(' ', $v);
					// 空配列は消す
					$tmp_att = array_filter($tmp_att);

					$att = array();
					foreach ($tmp_att AS $v2) {
						list($k, $v) = explode('=', $v2);
						$att[$k] = $v;
					}

					$outhtml = array();
					$outhtmlChild = array();

					if (isset($att['type'])) {
						// 前後のダブルクォートを削除
						$v = $att['type'];
						$v = preg_replace('/^\"/i', '', $v);
						$v = preg_replace('/\"$/i', '', $v);
						$outhtmlChild[] = 'type="' . $v . '"';
					}
					if (isset($att['name'])) {
						// 前後のダブルクォートを削除
						$v = $att['name'];
						$v = preg_replace('/^\"/i', '', $v);
						$v = preg_replace('/\"$/i', '', $v);
						$outhtmlChild[] = 'name="' . $v . '[]"';
					}
					if (isset($att['value'])) {
						// 前後のダブルクォートを削除
						$v = $att['value'];
						$v = preg_replace('/^\"/i', '', $v);
						$v = preg_replace('/\"$/i', '', $v);

						$aryVal = explode(',', $v);
						$parts = "";
						// index.php用
						foreach ($aryVal AS $v) {
							$t_ary = $outhtmlChild;
							$t_ary[] = 'value="' . $v . '"';
							$parts .= '<label>' . $v . '<input ' . implode(' ', $t_ary) . ' /></label>';
						}
						// 
						$dst[] = $parts;
					}
				}
			}

			replace_f($ary);

			foreach ($src[0] as $s) {
				// 取り出した配列を元に置換前後の文字列を配列にする
				$frm[] = '!' . preg_quote($s) . '!u';
			}

			$html = preg_replace($frm, $dst, $html);

			var_dump($html);
		?>
<input type="submit" value="send" />
		</form>
	</body>
</html>
