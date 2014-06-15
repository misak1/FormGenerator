<?php
// 対になるライブラリをrequireする。
require_once ('edit4_lib.php');
$and_action_href = 'edit4_index.php';
startSession();
require_once ('category.class.php');
require_once ('reply.class.php');
require_once ('member.class.php');
require_once ('replycategorymember.class.php');

$edt4_delete_replycategorymember_id = isset($_SESSION['edt4_delete_replycategorymember_id']) ? $_SESSION['edt4_delete_replycategorymember_id'] : '';
$edt4_modify_replycategorymember_id = isset($_SESSION['edt4_modify_replycategorymember_id']) ? $_SESSION['edt4_modify_replycategorymember_id'] : '';
$edt4_category_id = isset($_SESSION['edt4_category_id']) ? $_SESSION['edt4_category_id'] : '';
$edt4_reply_id = isset($_SESSION['edt4_reply_id']) ? $_SESSION['edt4_reply_id'] : '';
$edt4_member_id = isset($_SESSION['edt4_member_id']) ? $_SESSION['edt4_member_id'] : '';

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
        $edt4_reply_id    = (isset($_POST['edt4_reply_id']))    ? $_POST['edt4_reply_id']    : '';
        $edt4_category_id    = (isset($_POST['edt4_category_id']))    ? $_POST['edt4_category_id'] : '';
        $edt4_member_id    = (isset($_POST['edt4_member_id']))    ? $_POST['edt4_member_id']    : '';
        $_SESSION['edt4_category_id'] = $edt4_category_id;
        $_SESSION['edt4_reply_id']    = $edt4_reply_id;
        $_SESSION['edt4_member_id']    = $edt4_member_id;

        $err = validateForm($edt4_category_id, $edt4_reply_id, $edt4_member_id);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit4_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit4_edit.html');
        }
    } elseif ($pagemode === 'edit') {
        writeLog("pagemode = edit, edt4_id=$edt4_replycategorymember_id");
        include ('edit4_edit.html');
    } elseif ($pagemode === 'modify') {
        $edt4_modify_replycategorymember_id  = (isset($_POST['replycategorymember_id']))  ? $_POST['replycategorymember_id'] : $edt4_modify_replycategorymember_id;
        $_SESSION['edt4_modify_replycategorymember_id'] = $edt4_modify_replycategorymember_id;
		if($_POST['replycategorymember_id']){
			//選択されたIDから、データ取得
	        $r = new replycategorymember();
			$replycategorymemberLs = $r->getReplyCategoryMember();
			foreach($replycategorymemberLs as $eachreplycategorymember){
				if($eachreplycategorymember['replycategorymember_id'] === $edt4_modify_replycategorymember_id){
					$edt4_reply_id = $eachreplycategorymember['reply_id'];
					$edt4_category_id = $eachreplycategorymember['category_id'];
					$edt4_member_id = explode(',',$eachreplycategorymember['member_id']);
					break;
				}
			}
		}
        writeLog("pagemode = modify, edt4_id=$edt4_modify_replycategorymember_id");
        include ('edit4_modify.html');
	} elseif ($pagemode === 'modify_confirm'){
        writeLog("pagemode is modify_confirm");
        $edt4_reply_id    = (isset($_POST['edt4_reply_id']))    ? $_POST['edt4_reply_id'] : '';
        $edt4_category_id    = (isset($_POST['edt4_category_id']))    ? $_POST['edt4_category_id']    : '';
        $edt4_member_id    = (isset($_POST['edt4_member_id']))    ? $_POST['edt4_member_id'] : '';
        $_SESSION['edt4_reply_id']    = $edt4_reply_id;
        $_SESSION['edt4_category_id']    = $edt4_category_id;
        $_SESSION['edt4_member_id']    = $edt4_member_id;

        $err = validateForm($edt4_reply_id, $edt4_category_id, $edt4_member_id);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit4_modify_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit4_edit.html');
        }
    } elseif ($pagemode === 'modify_finish') {
        $r = new replycategorymember();
        $r->updateReplyCategoryMember($edt4_modify_replycategorymember_id,$edt4_reply_id,$edt4_category_id,$edt4_member_id);
        include ('edit4_modify_finish.html');
    } elseif ($pagemode === 'delete') {
        $edt4_delete_replycategorymember_id  = (isset($_POST['replycategorymember_id']))  ? $_POST['replycategorymember_id'] : $edt4_delete_replycategorymember_id;
        $_SESSION['edt4_delete_replycategorymember_id'] = $edt4_delete_replycategorymember_id;
		//選択されたIDから、データ取得
        $r = new replycategorymember();
		$replycategorymemberLs = $r->getReplyCategoryMember();
		foreach($replycategorymemberLs as $eachreplycategorymember){
			if($eachreplycategorymember['replycategorymember_id'] === $edt4_delete_replycategorymember_id){
				$edt4_reply_id = $eachreplycategorymember['reply_id'];
				$edt4_category_id = $eachreplycategorymember['category_id'];
				$edt4_member_id = $eachreplycategorymember['member_id'];
				break;
			}
		}
        writeLog("pagemode = delete, edt4_id=$edt4_delete_replycategorymember_id");
        include ('edit4_delete.html');
    } elseif ($pagemode === 'delete_finish') {
        $r = new replycategorymember();
        $r->deleteReplyCategoryMember($edt4_delete_replycategorymember_id);
        include ('edit4_delete_finish.html');
    } elseif ($pagemode === 'finish') {
        
        $r = new replycategorymember();
        $r->insertReplyCategoryMember($edt4_reply_id, $edt4_category_id, $edt4_member_id);
        //$header = classFunction::create_mailHeader($from, $sender, $aryTo, $edt4_category_id);
        //classFunction::sendMail($header, $edt4_reply_id);
        
        include ('edit4_finish.html');
    }
}
?>