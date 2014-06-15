<?php
//---------------------------------------------------------------
// 利用方法
// (1) #1 sessionに入れられるkeyを適宜変更する。
// (2) #2 はedit8.js と処理を合わせること！
//---------------------------------------------------------------
// #1 セッション初期化
function initSession() {
    startSession();
    $_SESSION['edt8_delete_member_id'] = '';
    $_SESSION['edt8_modify_member_id'] = '';
    $_SESSION['edt8_name'] = '';
    $_SESSION['edt8_note'] = '';
}
// #2 入力値チェック
function validateForm($edt8_subject, $edt8_body){
    $canpost = 1;
    $spaces = " \b\t\v\n\r\f\'\"\\\0";
    $err = '';
    
    if(isNullOrInvalid($edt8_subject, $spaces)){
        $canpost = 0;
        $err .= "お名前は必須項目です。<br />\r\n";
    }
    if(isNullOrInvalid($edt8_body, $spaces)){
        $canpost = 0;
        $err .= "メールアドレスは必須項目です。<br />\r\n";
    }
    elseif(! isValidMailAddress($edt8_body)){
        $canpost = 0;
        $err .= "メールアドレスをご確認ください。<br />\r\n";
    }

    return $err;
}
?>