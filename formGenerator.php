<?php
define(_SESSIONNAME_, 'IWMF');
define(_IWMFLOG_, 'iwmf.log' );
define(_LOOKFILE_, 'form.html' );

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
/**
 * 前後のダブルクォート削除
 */
function trim_d($v) {
	$v = preg_replace('/^\"/i', '', $v);
	$v = preg_replace('/\"$/i', '', $v);
	return $v;
}
/**
 * トークンチェック
 */
function formHandler() {
	foreach ($_POST as $k => $v) {
		writeLog("_POST[" . $k . "]=$v");
	}
	foreach ($_SESSION as $k => $v) {
		writeLog("_SESSION[" . $k . "]=$v");
	}

	if (!isValidPage()) {
		writeLog("key not match, redirect to index");
		// inputへ飛ばす
		doRedirect("./");
	}
}
/**
 * 短縮タグ解析
 */
function shortTagParser($shortTag) {
	$v = preg_replace('/^\[iwmf/i', '', $shortTag);
	$v = preg_replace('/\]$/i', '', $v);
	$tmp_att = explode(' ', $v);
	// 空配列を除去
	$tmp_att = array_filter($tmp_att);

	// shortTagAttribute
	foreach ($tmp_att AS $v2) {
		list($k, $v) = explode('=', $v2);
		$att[$k] = $v;
	}

	$list = array();
	// TODO list関数で変数に格納する為、処理順には気をつける
	$list[] = (isset($att['type'])) ? trim_d($att['type']) : '';
	$list[] = (isset($att['name'])) ? trim_d($att['name']) : '';
	$list[] = (isset($att['value'])) ? trim_d($att['value']) : '';
	// TODO ラベルセパレータ付き
	$list[] = (isset($att['label'])) ? trim_d($att['label']) . '：' : '';
	$list[] = (isset($att['validity'])) ? trim_d($att['validity']) : '';
	return $list;
}

function replace_main($aryShortTags) {
	$dst = array();

	// 入力内容検証
	$isError = FALSE;
	$aryError = array();
	if (isset($_POST['pagemode'])) {
		// index以外
		foreach ($aryShortTags AS $shortTag) {
			list($type, $name, $value, $label, $validity) = shortTagParser($shortTag);
			$errorMsg = validateForm($name, $validity);
			if ($errorMsg !== '') {
				$isError |= TRUE;
			}
			$aryError[$name] = $errorMsg;
		}
	}

	// 置換文字列を作成
	foreach ($aryShortTags AS $shortTag) {
		list($type, $name, $value, $label, $validity) = shortTagParser($shortTag);

		// confirm
		// ex) 入力内容<input type="hidden" name="name" value="value">
		if (!$isError && $_POST['pagemode'] === 'confirm') {
			$parts = array();
			foreach ($_POST[$name] as $vv) {
				$parts[] .= $vv . '<input type="hidden" name="' . $name . '[]" value="' . $vv . '"/>';
			}
			$dst[] = $label . implode(', ', $parts);
			continue;
		}

		$outhtmlChild = array();
		if($type === 'textarea'){
			// textarea
			$tag = build_textarea($type, $name, $value, $label, $validity);
			$dst[] = $tag;
			continue;
			
		}else if ( $type === 'select'){
			// select
			$tag = build_select($type, $name, $value, $label, $validity);
			$dst[] = $tag;
			continue;
		}else{
			// text, checkbox, radio 
			$outhtmlChild[] = 'type="' . $type . '"';
			
		}
		$outhtmlChild[] = 'name="' . $name . '[]"';

		// 内容に問題がある場合 or 再編集
		if ($isError || $_POST['pagemode'] === 'edit') {
			// edit.php

			$parts = $label;
			$aryVal = explode(',', $value);
			foreach ($aryVal AS $v) {
				$t_ary = $outhtmlChild;

				if ($type === 'text') {
					// text
					$t_ary[] = 'value="' . $_POST[$name][0] . '"';

				} else if ($type === 'radio') {
					// radio
					if ($v === $_POST[$name][0]) {
						$t_ary[] = 'value="' . $v . '" checked';
					} else {
						$t_ary[] = 'value="' . $v . '"';
					}

				} else if ($type === 'checkbox') {
					// checkbox(複数あり)
					$postAryVal = $_POST[$name];
					if (in_array($v, $postAryVal, true)) {
						$t_ary[] = 'value="' . $v . '" checked';
					} else {
						$t_ary[] = 'value="' . $v . '"';
					}
					$parts .= '<label>' . $v . '<input ' . implode(' ', $t_ary) . ' /></label>';

					continue;

				} else {
					$t_ary[] = 'value="' . $v . '"';
				}
				if ($type === 'radio' || $type === 'checkbox') {
					$parts .= '<label>' . $v . '<input ' . implode(' ', $t_ary) . ' /></label>';
				} else {
					$parts = '<label>' . $label . '<input ' . implode(' ', $t_ary) . ' /></label>';
				}
			}
			if ($isError) {
				$dst[] = $parts . '<span style="color:#f00;">' . $aryError[$name] . '</span>';
			} else {
				$dst[] = $parts;
			}

		} else {
			// index.php

			// 前後のダブルクォートを削除
			$parts = $label;
			$aryVal = explode(',', $value);
			foreach ($aryVal AS $v) {
				$t_ary = $outhtmlChild;
				$t_ary[] = 'value="' . $v . '"';
				if ($type === 'radio' || $type === 'checkbox') {
					// 複数
					$parts .= '<label>' . $v . '<input ' . implode(' ', $t_ary) . ' /></label>';
				} else {
					// 単一(label上書き)
					$parts = '<label>' . $label . '<input ' . implode(' ', $t_ary) . ' /></label>';
				}
			}
			$dst[] = $parts;
		}
	}
	//return $dst;
	return array($isError, $dst);
}
function build_textarea($type, $name, $value, $label, $validity){
	$t_ary[] = 'name="' . $name . '[]"';
	$tag = '<label>' . $label . '<textarea ' . implode(' ', $t_ary) . ' >'.$value.'</textarea></label>';
	return $tag;
}

/**
 * 送信データのチェック （優先度の低いものから処理する。（エラーメッセージの上書き））
 */
function validateForm($name, $validity) {
	$errorMsg = '';
	$aryValidity = explode(',' , $validity);
	
	if (in_array("email", $aryValidity)) {
		$v = implode('', $_POST[$name]);
		if (!isValidMailAddress($v)) {
			$errorMsg = 'メールアドレスを入力して下さい。';
		}
		writeLog('validateForm() ' . $name . '="' . $v . '" $result="' . $errorMsg . '"');
	}
	
	if (in_array("require", $aryValidity)) {
		$v = implode('', $_POST[$name]);
		if (empty($v)) {
			$errorMsg = '必須項目なので入力してください。';
		}
		writeLog('validateForm() ' . $name . '="' . $v . '" $result="' . $errorMsg . '"');
	}

	return $errorMsg;
}
function _formGenerator() {
	$html = file_get_contents(_LOOKFILE_);
	// 対象文字列を配列として取り出す。
	preg_match_all('[[\[](.*?)[\]]]', $html, $src);

	// 置換前文字列
	$frm = array();
	// 置換後文字列
	$dst = array();


	foreach ($src[0] as $s) {
		// 取り出した配列を元に置換前後の文字列を配列にする
		$frm[] = '!' . preg_quote($s) . '!u';
	}
	
	$aryShortTags = $src[0];
	//$dst = replace_main($aryShortTags);
	list($isError, $dst) = replace_main($aryShortTags);

	$html = preg_replace($frm, $dst, $html);

	//echo $html;
	// index/edit/confirm
	$keytag = getKEYTAG();
	
	if (!isset($_POST['pagemode'])) {
		// index
		indexForm($keytag, $html);
	}else if ($_POST['pagemode'] === 'edit') {
		editForm($keytag, $html);
	}else if ($_POST['pagemode'] === 'confirm') {
		if($isError){
			editForm($keytag, $html);
		}else{
			confirmForm($keytag, $html);
		}
	}else if ($_POST['pagemode'] === 'finish') {
		finishForm($html);
	}
}
?>
<?php /**** FORM TEMPLATE ****/ ?> 
<?php function indexForm($keytag, $html){ ?>
	<!-- index.php -->
	<!-- initSession セッションの初期化 -->
	<form method="post" action="confirm.php" name="toiawase">
	<input type="hidden" name="pagemode" value="confirm" />
	<?php echo $keytag; ?>
	<?php echo $html; ?>
	<input type="button" value="send" onclick="javascript:submit();"/>
	</form>
	<!-- /index.php -->
<?php } ?>
<?php function editForm($keytag, $html){ ?>
	<!-- edit.php -->
	<form method="post" action="confirm.php" name="toiawase">
	<input type="hidden" name="pagemode" value="confirm" />
	<?php echo $keytag; ?>
	<?php echo $html; ?>
	<input type="button" value="send" onclick="javascript:submit();"/>
	</form>
	<!-- /edit.php -->
<?php } ?>
<?php function confirmForm($keytag, $html){ ?>
	<!-- confirm.php -->
	<form name="confirm_form" method="post">
	<?php echo $keytag; ?>
	<?php echo $html; ?>
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
<?php } ?>
<?php function finishForm($keytag, $html){ ?>
	<!-- finish.php -->
	Complite!
	<!-- /finish.php -->
<?php } ?>
<?php /**** end. FORM TEMPLATE ****/ ?> 