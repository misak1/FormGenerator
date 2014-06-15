<?php
//---------------------------------------------------------------
// 利用方法
// (1) #1 sessionに入れられるkeyを適宜変更する。
// (2) #2 はedit1.js と処理を合わせること！
//---------------------------------------------------------------
// #1 セッション初期化
function initSession() {
    startSession();
    $_SESSION['edt4_delete_replycategorymember_id'] = '';
    $_SESSION['edt4_modify_replycategorymember_id'] = '';
    $_SESSION['edt4_reply_id'] = '';
    $_SESSION['edt4_category_id'] = '';
    $_SESSION['edt4_member_id'] = '';
}
// #2 入力値チェック
function validateForm($reply_id, $category_id, $member_id){
    $canpost = 1;
    $spaces = " \b\t\v\n\r\f\'\"\\\0";
    $numbers = "0123456789";
    $err = '';
    if(isNullOrNotFollow($reply_id, $numbers) || $reply_id < 1){
        $canpost = 0;
        $err .= "メールアドレスが未選択です。<br />\r\n";
    }
    if(isNullOrNotFollow($category_id, $numbers) || $category_id < 1){
        $canpost = 0;
        $err .= "カテゴリーが未選択です。<br />\r\n";
    }
	$flag = false;
	foreach($member_id as $eachmember){
	    if(isNullOrNotFollow($eachmember, $numbers)){
	        $canpost = 0;
	        $err .= "メンバーが未選択です。<br />\r\n";
			break;
	    } else {
			$flag = true;
		}
	}
	if(!$flag){
		$canpost = 0;
		$err .= "メンバーが未選択です。<br />\r\n";
	}
    return $err;
}
?>