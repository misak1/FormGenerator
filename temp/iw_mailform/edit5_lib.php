<?php
//---------------------------------------------------------------
// 利用方法
// (1) #1 sessionに入れられるkeyを適宜変更する。
// (2) #2 はedit1.js と処理を合わせること！
//---------------------------------------------------------------
// #1 セッション初期化
function initSession() {
    startSession();
    $_SESSION['edt5_name'] = '';
    $_SESSION['edt5_note'] = '';
}
// #2 入力値チェック
function validateForm($edt5_subject, $edt5_body){
    $canpost = 1;
    $spaces = " \b\t\v\n\r\f\'\"\\\0";
    $err = '';
    /*
    if(isNullOrInvalid($category, $spaces)){
        $canpost = 0;
        $err .= "カテゴリは必須項目です。<br />\r\n";
    }
    if(isNullOrInvalid($username, $spaces)){
        $canpost = 0;
        $err .= "お名前は必須項目です。<br />\r\n";
    }
    if(isNullOrInvalid($email, $spaces)){
        $canpost = 0;
        $err .= "メールアドレスは必須項目です。<br />\r\n";
    }
    elseif(! isValidMailAddress($email)){
        $canpost = 0;
        $err .= "メールアドレスをご確認ください。<br />\r\n";
    }
    
    if(isNullOrInvalid($content, $spaces)){
        $canpost = 0;
        $err .= "お問い合わせ内容は必須項目です。<br />\r\n";
    }
    if(!$policy){
        $canpost = 0;
        $err .= "プライバシー・ステートメントにチェックしてください。<br />\r\n";
    }
     */
    return $err;
}
?>