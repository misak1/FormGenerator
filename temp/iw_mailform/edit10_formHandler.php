<?php
// 対になるライブラリをrequireする。
require_once ('edit10_lib.php');
$and_action_href = 'edit10_index.php';
startSession();
require_once ('reply.class.php');

$edt10_delete_reply_id = isset($_SESSION['edt10_delete_reply_id']) ? $_SESSION['edt10_delete_reply_id'] : '';
$edt10_modify_reply_id    = isset($_SESSION['edt10_modify_reply_id'])    ? $_SESSION['edt10_modify_reply_id']    : '';
$edt10_mail_address = isset($_SESSION['edt10_mail_address']) ? $_SESSION['edt10_mail_address'] : '';
$edt10_mail_note    = isset($_SESSION['edt10_mail_note'])    ? $_SESSION['edt10_mail_note']    : '';

foreach ($_POST as $k => $v) {
    writeLog("_POST[" . $k . "]=$v");
}
foreach ($_SESSION as $k => $v) {
    writeLog("_SESSION[" . $k . "]=$v");
}
if (!isValidPage()) {
    writeLog("key not match, redirect to index");
    doRedirect("./");
} else {
    $pagemode = (isset($_POST['pagemode'])) ? $_POST['pagemode'] : 'index';
    writeLog("key matches: pagemode=" . $pagemode);
    if ($pagemode === 'confirm') {
        writeLog("pagemode is confirm");
        $edt10_mail_address = (isset($_POST['edt10_mail_address'])) ? $_POST['edt10_mail_address'] : '';
        $edt10_mail_note    = (isset($_POST['edt10_mail_note'])) ? $_POST['edt10_mail_note'] : '';
        $_SESSION['edt10_mail_address'] = $edt10_mail_address;
        $_SESSION['edt10_mail_note']    = $edt10_mail_note;
        $err = validateForm($edt10_mail_address, $edt10_mail_note);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit10_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit10_edit.html');
        }
    } elseif ($pagemode === 'edit') {
        writeLog("pagemode = edit, edt10_id=$edt10_modify_reply_id");
        include ('edit10_edit.html');
    } elseif ($pagemode === 'modify') {
        $edt10_modify_reply_id  = (isset($_POST['reply_id']))  ? $_POST['reply_id'] : $edt10_modify_reply_id;
        $_SESSION['edt10_modify_reply_id'] = $edt10_modify_reply_id;
		if($_POST['reply_id']){
			//選択されたIDから、データ取得
	        $r = new reply();
			$replyLs = $r->getReplys();
			foreach($replyLs as $eachReply){
				if($eachReply['reply_id'] === $edt10_modify_reply_id){
					$edt10_mail_address = $eachReply['mail_address'];
					$edt10_mail_note = $eachReply['mail_note'];
					break;
				}
			}
		}
        writeLog("pagemode = modify, edt10_id=$edt10_modify_reply_id");
        include ('edit10_modify.html');
	} elseif ($pagemode === 'modify_confirm'){
        writeLog("pagemode is modify_confirm");
        $edt10_mail_address    = (isset($_POST['edt10_mail_address']))    ? $_POST['edt10_mail_address']    : '';
        $edt10_mail_note    = (isset($_POST['edt10_mail_note']))    ? $_POST['edt10_mail_note'] : '';
        $_SESSION['edt10_mail_address']    = $edt10_mail_address;
        $_SESSION['edt10_mail_note']    = $edt10_mail_note;

        $err = validateForm($edt10_mail_address, $edt10_mail_note);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit10_modify_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit10_edit.html');
        }
    } elseif ($pagemode === 'modify_finish') {
        $r = new reply();
        $r->updateReply($edt10_modify_reply_id,$edt10_mail_address,$edt10_mail_note);
        include ('edit10_modify_finish.html');
    } elseif ($pagemode === 'delete') {
        $edt10_delete_reply_id  = (isset($_POST['reply_id']))  ? $_POST['reply_id'] : $edt10_delete_reply_id;
        $_SESSION['edt10_delete_reply_id'] = $edt10_delete_reply_id;
		//選択されたIDから、データ取得
        $r = new reply();
		$replyLs = $r->getReplys();
		foreach($replyLs as $eachReply){
			if($eachReply['reply_id'] === $edt10_delete_reply_id){
				$edt10_mail_address = $eachReply['mail_address'];
				$edt10_mail_note = $eachReply['mail_note'];
				break;
			}
		}
        writeLog("pagemode = delete, edt10_id=$edt10_delete_reply_id");
        include ('edit10_delete.html');
    } elseif ($pagemode === 'delete_finish') {
        $r = new reply();
        $r->deleteReply($edt10_delete_reply_id);
        include ('edit10_delete_finish.html');
    } elseif ($pagemode === 'finish') {
        
        $r = new reply();
        $r->insertReply($edt10_mail_address, $edt10_mail_note);
        //$header = classFunction::create_mailHeader($from, $sender, $aryTo, $edt2_subject);
        //classFunction::sendMail($header, $edt2_body);
        
        include ('edit10_finish.html');
    }
}
?>