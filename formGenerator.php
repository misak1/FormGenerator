<?php
define(_SESSIONNAME_, 'IWMF');
define(_IWMFLOG_, 'iwmf.log' );

date_default_timezone_set('Asia/Tokyo');

function startSession(){
    session_name(_SESSIONNAME_);
    session_start();
}
function genKEY(){
    return mt_rand();
}
function genTAG(){
    return 'key' . mt_rand(1000, 9999);
}
function getKEYTAG(){
    $key = genKEY();
    $tag = genTAG();
    $_SESSION['tagname'] = $tag;
    $_SESSION['keyvalue'] = $key;
    return '<input type="hidden" name="' . $tag . '" value="' . $key . '" />';
}
function isValidPage(){
    $ret = 0;
    if(isset($_SESSION['tagname'])){
        $t = $_SESSION['tagname'];

        writeLog ("tagname=$t");
        writeLog("keyvalue=". $_SESSION['keyvalue']);
        writeLog("_POST[" . $t . "]=". $_POST[$t]);
        writeLog (($_POST[$t] == $_SESSION['keyvalue']));

        if(isset($_POST[$t]) && isset($_SESSION['keyvalue']) && $_POST[$t] == $_SESSION['keyvalue']){
            $ret = 1;
        }
    }
    return $ret;
}
function doRedirect($path){
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: ${path}");
}
?>
<?php
function isNullOrInvalid($s, $killtargets) {
	// 空文字列か、killtargets だけからなる文字列なら 1 を、それ以外は 0 を返す
	$ret = 1;

	if ($s === '') {
		$ret = 1;
	} else {
		$all_invalid = 1;
		for ($p = 0; $p < mb_strlen($s); $p++) {
			$c = mb_substr($s, $p, 1);
			if (mb_strpos($killtargets, $c) === FALSE) {
				// killtargetsではない文字を含むから all_invalid ではない
				$all_invalid = 0;
				break;
			}
		}
		$ret = $all_invalid;
	}
	return $ret;
}

function isNullOrNotFollow($s, $allowtargets) {
	// 空文字列か、allowtargets 以外を含む文字列なら 1 を、それ以外は 0 を返す
	$ret = 1;
	if ($s === '') {
		$ret = 1;
	} else {
		$all_valid = 1;
		for ($p = 0; $p < mb_strlen($s); $p++) {
			$c = mb_substr($s, $p, 1);
			if (mb_strpos($allowtargets, $c) === FALSE) {
				// allowtargets ではない文字を含むから all_valid ではない
				$all_valid = 0;
				break;
			}
		}
		$ret = ($all_valid == 1) ? 0 : 1;
	}
	return $ret;
}

function isValidLocalPart($s) {
	// てきとーな addr-spec の local-part 適合性チェック
	// -1 ... 空文字列か、64文字を超えている
	//  0 ... local-part ではない
	//  1 ... dot-atom な local-part
	//  2 ... quoted-string な local-part
	//  3 ... obs-local-part な local-part
	//  4 ... quasi-obs-local-part な local-part
	$ret = 0;

	if ($s !== '' && mb_strlen($s) <= 64) {
		if (mb_ereg("^[!#-'*+\-/-9=?A-Z^-~]+(\.[!#-'*+\-/-9=?A-Z^-~]+)*$", $s)) {
			// (1) dot-atom なら local-part
			$ret = 1;
		} elseif (mb_ereg('^\"([ \t]|[!#-\[\]-~]|\\.)*[ \t]*\"$', $s)) {
			// (2) quoted-string なら local-part
			$ret = 2;
		} else if (mb_ereg("^([!#-'*+\-/-9=?A-Z^-~]+|\"([ \t]|[!#-\[\]-~]|\\.)*[ \t]*\")(\.([!#-'*+\-/-9=?A-Z^-~]+|\"([ \t]|[!#-\[\]-~]|\\.)*[ \t]*\"))*$", $s)) {
			// (3) obs-local-part なら local-part
			$ret = 3;
		} else if (mb_ereg("^([.!#-'*+\-/-9=?A-Z^-~]+|\"([ \t]|[!#-\[\]-~]|\\.)*[ \t]*\")+$", $s)) {
			// (4) rfc違反だが、docomo アドレス対応
			$ret = 4;
		}
	} else {
		$ret = -1;
	}
	return $ret;
}

function isValidDomainPart($s) {
	// てきとーな addr-spec の domain-part 適合性チェック
	//   4 ... gTLD なサブドメイン
	//   3 ... ccTLD なサブドメイン
	//   2 ... gTLD なトップドメイン
	//   1 ... ccTLD なトップドメイン
	//   0 ... 空文字列か 255文字以上
	//  -1 ... '.' がなく、64文字以上
	//  -2 ... '.' がなく、ccTLD でも gTLD でもない
	//  -3 ... '.' で分割して要素が２つ未満... あるはずない
	//  -4 ... '.' で分割して空の要素がある
	//  -5 ... '.' で分割して63文字を超える
	//  -6 ... '.' で分割して rfc1035 な <label> にマッチしない

	$ret = 0;
	if ($s !== '' && mb_strlen($s) <= 255) {
		if (mb_strpos($s, '.') !== FALSE) {
			$labels = mb_split('\\.', $s);
			if (2 <= count($labels)) {
				$failed = 0;
				foreach ($labels as $seg) {
					if ($seg === '') {
						// 空要素なら即座に判定終了
						$failed = 1;
						$ret = -4;
						break;
					} elseif (63 < mb_strlen($seg)) {
						// label が 63文字を超えてはならない
						$failed = 1;
						$ret = -5;
						break;
					} elseif (!mb_ereg("^[a-zA-Z]+[\\-a-zA-Z0-9]*[a-zA-Z0-9]*$", $seg)) {
						// rfc1035 な <label> でないなら判定終了
						$failed = 1;
						$ret = -6;
						break;
					}
				}
				if (!$failed) {
					// 一応 TLD であるかどうかは確認しよう
					$r = isTLD($labels[count($labels) - 1]);
					if ($r === "ccTLD") {
						$ret = 3;
					} elseif ($r === "gTLD") {
						$ret = 4;
					}
				}
			} else {
				$ret = -3;
			}
		} elseif (mb_strlen($s) < 64) {
			// 今のところは cctld か gtld じゃないとダメとしておこう
			$r = isTLD($s);
			if ($r === "ccTLD") {
				$ret = 1;
			} elseif ($r === "gTLD") {
				$ret = 2;
			} else {
				$ret = -2;
			}
		} else {
			$ret = -1;
		}
	}
	return $ret;
}

function isValidMailAddress($s) {
	// てきとーなメールアドレスチェック
	$ret = 0;
	if (mb_strlen($s) <= 256 && mb_strpos($s, '@') !== FALSE) {
		// '@' で分割して要素は二つでないといけないことにする
		$lst = mb_split('@', $s);
		if (count($lst) == 2 && $lst[0] !== '' && $lst[1] !== '' && mb_strlen($lst[0]) <= 64 && mb_strlen($lst[1]) <= 255) {
			$islp = isValidLocalPart($lst[0]);
			$isdp = isValidDomainPart($lst[1]);
			if (0 < $islp && 0 < $isdp) {
				$ret = 1;
			}
		}
	}
	return $ret;
}

function isTLD($s) {
	// 文字列 s が TLD かどうかを返す
	// gTLD なら「gTLD」、sTLD なら「sTLD」、ccTLD なら「ccTLD」、arpa なら「arpa」を返す
	// 2013/03/07 wikipedia で確認
	$arpa = 'arpa';
	$cctld = '|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|' . '|ba|bb|bd|be|bf|bg|bh|bi|bj|bl|bm|bn|bo|br|bs|bt|bu|bv|bw|by|bz|' . '|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|' . '|dd|de|dg|dj|dk|dm|do|dz|' . '|ec|ee|eg|eh|er|es|et|eu|' . '|fi|fj|fk|fm|fo|fr|' . '|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|' . '|hk|hm|hn|hr|ht|hu|' . '|id|ie|il|im|in|io|iq|ir|is|it|' . '|je|jm|jo|jp|' . '|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|' . '|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|' . '|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|' . '|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|' . '|om|' . '|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|' . '|qa|' . '|re|ro|rs|ru|rw|' . '|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|ss|st|su|sv|sy|sz|' . '|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|' . '|ua|ug|uk|um|us|uy|uz|' . '|va|vc|ve|vg|vi|vn|vu|' . '|wf|ws|' . '|ye|yt|yu|' . '|za|zm|zw|';
	$gtld = '|aero|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|xxx|';
	$rtld = '|example|invalid|localhost|test|';

	$ss = '|' . mb_strtolower($s) . '|';
	$ret = 'NO';
	if (mb_strpos($cctld, $ss) !== FALSE) {
		$ret = 'ccTLD';
	} elseif (mb_strpos($gtld, $ss) !== FALSE) {
		$ret = 'gTLD';
	} elseif ($ss === $arpa) {
		$ret = 'arpa';
	}
	return $ret;
}

function writeLog($msg) {
	if (!file_exists(_IWMFLOG_)) {
		// Apacheの書込権限がある場合はファイルが作成される
		touch(_IWMFLOG_);
	}
	$fp = fopen(_IWMFLOG_, 'ab');
	if ($fp) {
		fwrite($fp, date("Y/m/d H:i:s") . " " . $msg . "\n");
	}
	fclose($fp);
}

function replace_f($ary) {
	$dst = array();
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

		// confirm
		if ($_POST['pagemode'] === 'confirm') {
			if (isset($att['name'])) {
				$v = $att['name'];
				$v = preg_replace('/^\"/i', '', $v);
				$v = preg_replace('/\"$/i', '', $v);
				$parts = array();
				foreach ($_POST[$v] as $vv) {
					$parts[] .= $vv . '<input type="hidden" name="' . $v . '[]" value="' . $vv . '"/>';
				}
				$dst[] = implode(', ',$parts);
			}
			continue;
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

			// index.php confirm.php用
			foreach ($aryVal AS $v) {
				$t_ary = $outhtmlChild;
				$t_ary[] = 'value="' . $v . '"';
				$parts .= '<label>' . $v . '<input ' . implode(' ', $t_ary) . ' /></label>';
			}

			$dst[] = $parts;
		}
	}
	return $dst;
}

function formHandler() {
	foreach ($_POST as $k => $v) {
		writeLog("_POST[" . $k . "]=$v");
	}
	foreach ($_SESSION as $k => $v) {
		writeLog("_SESSION[" . $k . "]=$v");
	}

	echo("err=$err" . "<br/>");
	if (!isValidPage()) {
		writeLog ("key not match, redirect to index");
		// inputへ飛ばす
		doRedirect("./");
	} else {
		writeLog ("key not match, redirect to index2");
		$pagemode = (isset($_POST['pagemode'])) ? $_POST['pagemode'] : 'index';
			writeLog("key matches: pagemode=" . $pagemode);
		if ($pagemode === 'confirm') {
			writeLog("pagemode is confirm");

			//$err = validateForm($category, $company, $username, $email, $tel, $content, $policy);
			echo("err=( $err )");
			if ($err === '') {
				//include ('confirm.php');
			} else {
				writeLog("問題があるから差し戻す");
				//include ('edit.php');
			}
		} elseif ($pagemode === 'edit') {
			echo("pagemode = edit, username=$username");
			//include ('edit.php');
		} elseif ($pagemode === 'finish') {
			echo "[[[]]]";
			//		include ('finish.html');
		}
	}
}

function _formGenerator($html) {
	// 対象文字列を配列として取り出す。
	preg_match_all('[[\[](.*?)[\]]]', $html, $src);

	// 置換前文字列
	$frm = array();
	// 置換後文字列
	$dst = array();

	$ary = $src[0];
	/*
	 if($_POST['pagemode'] === 'confirm'){
	 // confirm
	 $dst = replace_f2($ary);
	 }else{
	 */
	// index edit
	$dst = replace_f($ary);
	//}

	foreach ($src[0] as $s) {
		// 取り出した配列を元に置換前後の文字列を配列にする
		$frm[] = '!' . preg_quote($s) . '!u';
	}

	$html = preg_replace($frm, $dst, $html);

	echo $html;
}
