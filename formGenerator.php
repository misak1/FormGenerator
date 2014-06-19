<?php
define(_SESSIONNAME_, 'IWMF');
define(_IWMFLOG_, 'iwmf.log' );
define(_LOOKFILE_, 'form.html' );
date_default_timezone_set('Asia/Tokyo');

// doAction
// 例) formGenerator.php?do=scrape_tags
if(isset($_REQUEST['do'])){
	$function_name= $_REQUEST['do'];
	$function_name();
}

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
function HSC($var){
	return htmlspecialchars($var);
}
function p_dump($var){
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
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
	$list[] = $att; // その他
	return $list;
}

function replace_main($aryShortTags) {
	$dst = array();

	// 入力内容検証
	$isError = FALSE;
	$aryError = array();
	// p_dump($aryShortTags);
	if (isset($_POST['pagemode'])) {
		// index以外
		foreach ($aryShortTags AS $shortTag) {
			list($type, $name, $value, $label, $validity, $att) = shortTagParser($shortTag);
			$errorMsg = validateForm($name, $validity);
			if ($errorMsg !== '') {
				$isError |= TRUE;
			}
			$aryError[$name] = $errorMsg;
		}
	}
	//p_dump($aryError);
	//p_dump($_POST);

	// 置換文字列を作成
	foreach ($aryShortTags AS $shortTag) {
		list($type, $name, $value, $label, $validity, $att) = shortTagParser($shortTag);

		// confirm
		// example) 入力内容<input type="hidden" name="name" value="value">
		if (!$isError && $_POST['pagemode'] === 'confirm') {
			$parts = array();
			foreach ($_POST[$name] as $vv) {
				$parts[] .= '<span class="confirm_text">'. nl2br(HSC($vv)) . '</span><input type="hidden" name="' . $name . '[]" value="' . HSC($vv) . '"/>';
			}
			$dst[] = $label . implode(', ', $parts);
			continue;
		}

		$outhtmlChild = array();
		if($type === 'textarea'){
			// textarea
			$tag = build_textarea($type, $name, $value, $label, $validity, $att);
			$dst[] = $tag;
			continue;
			
		}else if ( $type === 'select'){
			// select
			$tag = build_select($type, $name, $value, $label, $validity, $aryAtt);
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
function build_textarea($type, $name, $value, $label, $validity, $att){
	$t_ary[] = 'name="' . $name . '[]"';
	if ($_POST['pagemode'] === 'edit') {
		// edit用のタグ作成
		$t_ary[] = (isset($att['cols'])) ?  'cols="'.trim_d($att['cols']).'"' : '';
		$t_ary[] = (isset($att['rows'])) ?  'rows="'.trim_d($att['rows']).'"' : '';
		$tag = '<label>' . $label . '<textarea ' . implode(' ', $t_ary) . ' >'. HSC($_POST[$name][0]) .'</textarea></label>';
		return $tag;
	}
	// index用のタグ作成
	$t_ary[] = (isset($att['cols'])) ?  'cols="'.trim_d($att['cols']).'"' : '';
	$t_ary[] = (isset($att['rows'])) ?  'rows="'.trim_d($att['rows']).'"' : '';
	$tag = '<label>' . $label . '<textarea ' . implode(' ', $t_ary) . ' >'.$value.'</textarea></label>';
	return $tag;
}
function build_select($type, $name, $value, $label, $validity, $att){
	$t_ary[] = 'name="' . $name . '[]"';
	$aryVal = explode(',', $value);
	//p_dump($att);
	if ($_POST['pagemode'] === 'edit') {
		// edit用のタグ作成
		$t_ary[] = (isset($att['cols'])) ?  'cols="'.trim_d($att['cols']).'"' : '';
		$t_ary[] = (isset($att['rows'])) ?  'rows="'.trim_d($att['rows']).'"' : '';
		$tag = '<label>' . $label . '<select ' . implode(' ', $t_ary) . ' >';
		$selectValue = $_POST[$name][0];
		foreach($aryVal AS $v){
			$selected = "";
			if($selectValue === $v){
				$selected = 'selected';
			}
			$tag .= '<option value="'.HSC($v).'" '.$selected.'>'.HSC($v).'</option>';
		}
		$tag .= '</label>';
		return $tag;
	}
	// index用のタグ作成
	$t_ary[] = (isset($att['cols'])) ?  'cols="'.trim_d($att['cols']).'"' : '';
	$t_ary[] = (isset($att['rows'])) ?  'rows="'.trim_d($att['rows']).'"' : '';
	$tag = '<label>' . $label . '<select ' . implode(' ', $t_ary) . ' >';
	foreach($aryVal AS $v){
		$tag .= '<option value="'.HSC($v).'">'.HSC($v).'</option>';
	}
	$tag .= '</label>';
	return $tag;
}
/*
// テンプレート
function build_XXX($type, $name, $value, $label, $validity, $att){
	if ($_POST['pagemode'] === 'edit') {
		// edit用のタグ作成
		return $tag;
	}
	// index用のタグ作成
	return $tag;
}
*/
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
	//preg_match_all('[[\[](.*?)[\]]]', $html, $src);
	preg_match_all('[\[iwmf(.*?)[\]]]', $html, $src);

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
		sendmail($_POST['email'][0]);
		finishForm($html);
	}
}
/**
 * ショートタグの抜き出し
 */
function scrape_tags(){
	// textで表示
	header("Content-Type: text/plain; charset=UTF-8");
	$html = file_get_contents(_LOOKFILE_);
	echo strip_tags($html);
}
/**
 * ショートタグの抜き出し
 */
function get_src(){
	// textで表示
	header("Content-Type: text/plain; charset=UTF-8");
	echo file_get_contents(_LOOKFILE_);
}
function file_writer($url){
	clearstatcache();
	$fp=@fopen($file_name, 'ab');
	if($f){
	    fwrite($fp, date("Y/m/d H:i:s") . " " . $msg . "\n");
	    fclose($fp);
	    return true;
	}
	return false;
}
// 複数の処理をまとめただけ
function trim_main ($str) {
    $str = space_trim($str);
    $str = lf_trim($str);
    return $str;
}
// 改行コードの削除
function lf_trim($email) {
    // mail header injection 対策で、念のため $email から改行コードを削除しておく
    $email = implode('', mb_split('\r\n', $email));
    $email = implode('', mb_split('\r', $email));
    $email = implode('', mb_split('\n', $email));
    return $email;
}
// 全角・半角スペースの削除
function space_trim ($str) {
    // 先頭の半角・全角スペース削除
    //$str = preg_replace('/^[ 　]+/u', '', $str);
    // 末尾の半角・全角スペース削除
    //$str = preg_replace('/[ 　]+$/u', '', $str);
	// 文字中の全角・半角スペースを削除
    $str = preg_replace('/[ 　]+/u', '', $str);
    return $str;
}
/**
 * 改行コードが複数存在する可能性も踏まえて、2種類の改行コード（\r\n、\r）を
 * htmlでよく使う改行（\n）に統一した後、\nを区切りに配列へ入れていきます
 */
function lf_filter($txt) {
    // テキストエリアの値を取得
    $cr = array("\r\n", "\r");
    // 改行コード置換用配列を作成しておく
    // 文頭文末の空白を削除
    $txt = trim($txt);
    // 改行コードを統一
    //str_replace ("検索文字列", "置換え文字列", "対象文字列");
    $txt = str_replace($cr, "\n", $txt);
    //改行コードで分割（結果は配列に入る）
    $lines_array = explode("\n", $txt);
    return $lines_array;
}
function toCrlf($txt) {
    // 一旦 改行コードを揃えてから行分割する
    $lines_array = lf_filter($txt);
    $txt = implode("\r\n", $lines_array);
    return $txt;
}
// example)　<メールアドレス1>,<メールアドレス2>,<メールアドレス3>
function concat_meil($aryMailTo) {
    $a = array();
    $s = "";
    foreach ($aryMailTo as $address) {
        $address = trim_main($address);
        $a[] = mb_encode_mimeheader(mb_convert_encoding($address, 'JIS','UTF-8')). '<' . $address . '>';
    }
    $s = implode(',', $a);
    return $s;
}
function create_mailHeader($from, $sender){
    $aryfrom[] = $from;
    $arySender[] = $sender;
    $from    = concat_meil($aryfrom);
    $mail_sender  = concat_meil($arySender);
    $header = toCrlf("From:".$from . "\n". "Sender:".$sender);
    return $header;
}

function create_mailBody($onetime_url=""){
    //$ts = date('Y年m月d日 H時i分s秒');
    // TODO
    $html = file_get_contents(_LOOKFILE_);
    $html = strip_tags($html);
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
	 replace_main($aryShortTags);


		// index以外
		foreach ($aryShortTags AS $shortTag) {
			list($type, $name, $value, $label, $validity, $att) = shortTagParser($shortTag);

			$parts = array();
			foreach ($_POST[$name] as $vv) {
				$parts[] .= $vv;
			}
			$dst[] = $label . implode(', ', $parts);
			continue;
		}
		
		$body = preg_replace($frm, $dst, $html);
		$body = mb_convert_encoding($body, 'JIS','UTF-8');

    return $body;
}
function sendmail($email){
	$from = "shibata@imagica-imageworks.co.jp";
	$sender = "shibata@imagica-imageworks.co.jp";
    $header = create_mailHeader($from, $sender);
    $body = create_mailBody();
    $aryTo[] = $email;
    $subject = "test sendmail.";
    $to = concat_meil($aryTo);
    
mb_language('Japanese');
ini_set('mbstring.detect_order', 'auto');
ini_set('mbstring.http_input'  , 'auto');
ini_set('mbstring.http_output' , 'pass');
ini_set('mbstring.internal_encoding', 'UTF-8');
ini_set('mbstring.script_encoding'  , 'UTF-8');
ini_set('mbstring.substitute_character', 'none');
mb_regex_encoding('UTF-8');

    mb_send_mail($to, $subject, $body, $header);
}
/**
 * @public
 */ 
function sendmail_test(){
	$from = "shibata@imagica-imageworks.co.jp";
	$sender = "shibata@imagica-imageworks.co.jp";
    $header = create_mailHeader($from, $sender);
    $body = create_mailBody();
    $aryTo[] = "shibata@imagica-imageworks.co.jp";
    $subject = "test sendmail.";
    $to = concat_meil($aryTo);
    mb_send_mail($to, $subject, $body, $header);
}
?>
<?php /**** FORM TEMPLATE ****/ ?> 
<?php function indexForm($keytag, $html){ ?>
	<!-- index.php -->
	<!-- initSession セッションの初期化 -->
	<form id="iwmf_confirm_form" method="post">
	<input id="iwmf_page_mode" type="hidden" name="pagemode"/>
	<?php echo $keytag; ?>
	<?php echo $html; ?>
	<input id="iwmf_send" type="button" value="send" onclick='javascript:action_f("confirm");'/>
	</form>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript">
		var $j172 = $.noConflict();
		$j172(function() {
			// ページ離脱イベント設定
			$j172(window).on("beforeunload", function() {
				return ("このページを離れようとしています。");
			});
		})
		var action_f = function (to){
			$j172('#iwmf_page_mode').val(to);
			var fm = $j172('#iwmf_confirm_form');
			fm.attr('action', to +'.php');
			// ページ離脱イベント解除
			$j172(window).off('beforeunload');
			fm.submit();
		}
	</script>
	<!-- /index.php -->
<?php } ?>
<?php function editForm($keytag, $html){ ?>
	<!-- edit.php -->
	<form id="iwmf_confirm_form" method="post">
	<input id="iwmf_page_mode" type="hidden" name="pagemode"/>
	<?php echo $keytag; ?>
	<?php echo $html; ?>
	<input id="iwmf_send" type="button" value="send" onclick='javascript:action_f("confirm");'/>
	</form>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript">
		var $j172 = $.noConflict();
		$j172(function() {
			// ページ離脱イベント設定
			$j172(window).on("beforeunload", function() {
				return ("このページを離れようとしています。");
			});
		})
		var action_f = function (to){
			$j172('#iwmf_page_mode').val(to);
			var fm = $j172('#iwmf_confirm_form');
			fm.attr('action', to +'.php');
			// ページ離脱イベント解除
			$j172(window).off('beforeunload');
			fm.submit();
		}
	</script>
	<!-- /edit.php -->
<?php } ?>
<?php function confirmForm($keytag, $html){ ?>
	<!-- confirm.php -->
	<form id="iwmf_confirm_form" method="post">
	<?php echo $keytag; ?>
	<?php echo $html; ?>
	<input id="iwmf_page_mode" type="hidden" name="pagemode"/>
	<input id="iwmf_prev" type="button" value="return" onclick='javascript:action_f("edit");' /><!-- 再編集 -->
	<input id="iwmf_next" type="button" value="done"   onclick='javascript:action_f("finish");' /><!-- 確定 -->
	</form>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript">
		var $j172 = $.noConflict();
		$j172(function() {
			// ページ離脱イベント設定
			$j172(window).on("beforeunload", function() {
				return ("このページを離れようとしています。");
			});
		})
		var action_f = function (to){
			$j172('#iwmf_page_mode').val(to);
			var fm = $j172('#iwmf_confirm_form');
			fm.attr('action', to +'.php');
			// ページ離脱イベント解除
			$j172(window).off('beforeunload');
			fm.submit();
		}
	</script>
	<!-- /confirm.php -->
<?php } ?>
<?php function finishForm($keytag, $html){ ?>
	<!-- finish.php -->
	Complite!
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript">
	    var $j172 = $.noConflict();
	    $j172(function() {
	    })
	</script>
	<!-- /finish.php -->
<?php } ?>
<?php /**** end. FORM TEMPLATE ****/ ?> 