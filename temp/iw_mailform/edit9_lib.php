<?php
//---------------------------------------------------------------
// 利用方法
// (1) #1 sessionに入れられるkeyを適宜変更する。
// (2) #2 はedit1.js と処理を合わせること！
//---------------------------------------------------------------
// #1 セッション初期化
function initSession() {
    startSession();
    $_SESSION['edt9_category_id'] = '';
    $_SESSION['edt9_type'] = '';
    $_SESSION['edt9_template_id'] = '';
}
// #2 入力値チェック
function validateForm($category_id, $type, $template_id){
    $canpost = 1;
    $spaces = " \b\t\v\n\r\f\'\"\\\0";
    $numbers = " 0123456789";
    $err = '';
    if(isNullOrNotFollow($category_id, $numbers) || $category_id < 1){
        $canpost = 0;
        $err .= "カテゴリーが未選択です。<br />\r\n";
    }
    if(isNullOrNotFollow($type, $numbers) || $type < 1){
        $canpost = 0;
        $err .= "宛先が未選択です。<br />\r\n";
    }
    if(isNullOrNotFollow($template_id, $numbers) || $template_id < 1){
        $canpost = 0;
        $err .= "メールテンプレートが未選択です。<br />\r\n";
    }
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