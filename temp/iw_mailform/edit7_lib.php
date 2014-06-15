<?php
//---------------------------------------------------------------
// 利用方法
// (1) #1 sessionに入れられるkeyを適宜変更する。
// (2) #2 はedit1.js と処理を合わせること！
//---------------------------------------------------------------
// #1 セッション初期化
function initSession() {
    startSession();
    $_SESSION['edt7_delete_category_id'] = '';
    $_SESSION['edt7_modify_category_id'] = '';
    $_SESSION['edt7_content_id'] = '';
    $_SESSION['edt7_prioryty_no'] = '';
    $_SESSION['edt7_category_name'] = '';
    $_SESSION['edt7_eflg'] = '';
}
// #2 入力値チェック
function validateForm($edt7_content_id, $edt7_prioryty_no, $edt7_category_name, $edt7_eflg){
    $canpost = 1;
    $spaces = " \b\t\v\n\r\f\'\"\\\0";
	$numbers = '0123456789';
    $err = '';
    if(!isNullOrInvalid($edt7_content_id, $numbers)){
        $canpost = 0;
        $err .= "コンテンツIDは数字で入力してください。<br />\r\n";
    }
    if(!isNullOrInvalid($edt7_prioryty_no, $numbers)){
        $canpost = 0;
        $err .= "重要度は数字で入力してください。<br />\r\n";
    }
    if(isNullOrInvalid($edt7_category_name, $spaces)){
        $canpost = 0;
        $err .= "カテゴリ名は必須項目です。<br />\r\n";
    }
    return $err;
}
?>