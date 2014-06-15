<?php
//---------------------------------------------------------------
// 利用方法
// (1) #1 sessionに入れられるkeyを適宜変更する。
// (2) #2 はedit8.js と処理を合わせること！
//---------------------------------------------------------------
// #1 セッション初期化
function initSession() {
    startSession();
    $_SESSION['edt10_delete_reply_id'] = '';
    $_SESSION['edt10_modify_reply_id'] = '';
    $_SESSION['edt10_mail_address'] = '';
    $_SESSION['edt10_mail_note'] = '';
}
// #2 入力値チェック
function validateForm($edt10_mail_address, $edt10_mail_note){
    $canpost = 1;
    $spaces = " \b\t\v\n\r\f\'\"\\\0";
    $err = '';
    
    if(isNullOrInvalid($edt10_mail_address, $spaces)){
        $canpost = 0;
        $err .= "メールアドレスは必須項目です。<br />\r\n";
    }
    elseif(! isValidMailAddress($edt10_mail_address)){
        $canpost = 0;
        $err .= "メールアドレスをご確認ください。<br />\r\n";
    }

    return $err;
}
?>