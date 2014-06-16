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

			// 対象文字列を配列として取り出す。
			preg_match_all('[[\[](.*?)[\]]]', $html, $src);

			$frm = array();
			// 置換前文字列
			$dst = array();
			// 置換後文字列

			//echo "<pre>";var_dump($src[0]);echo "</pre>";echo "<hr/>";

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

					//echo "<pre>";var_dump($tmp_att);echo "</pre>";echo "<hr/>";
					// key,valueに変換
					$att = array();
					foreach ($tmp_att AS $v2) {
						list($k, $v) = explode('=', $v2);
						$att[$k] = $v;
					}
					//echo "<pre>";var_dump($att);echo "</pre>";echo "<hr/>";

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
						foreach ($aryVal AS $v) {
							$t_ary = $outhtmlChild;
							$t_ary[] = 'value="' . $v . '"';
							#$outhtml[$v] = $t_ary;
							$parts .= '<label>' . $v . '<input ' . implode(' ', $t_ary) . ' /></label>';
						}
						$dst[] = $parts;

					}
					//echo "<pre>";var_dump($outhtml);echo "</pre>";echo "<hr/>";
					//foreach($outhtml AS $k => $v){
					//    $dst[] = '<label>'.$k.'<input '.implode(' ',$v).' /></label>';
					//}

				}

			}

			replace_f($ary);

			foreach ($src[0] as $s) {// 取り出した配列を元に置換前後の文字列を配列にする

				$frm[] = '!' . preg_quote($s) . '!u';
				// デリミタの追加とクォート（念の為）
			}

			//echo "<pre>";var_dump($frm);echo "</pre>";echo "<hr/>";
			//echo "<pre>";var_dump($dst);echo "</pre>";echo "<hr/>";
			$html = preg_replace($frm, $dst, $html);

			var_dump($html);
		?>
<input type="submit" value="send" />
		</form>
	</body>
</html>
