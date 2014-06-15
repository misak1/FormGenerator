<?php
// 対になるライブラリをrequireする。
require_once ('edit1_lib.php');
$and_action_href = 'edit1_index.php';
startSession();
require_once ('reply.class.php');
require_once ('member.class.php');
require_once ('maillog.class.php');
require_once ('replycategorymember.class.php');

$replycategorymember = new replycategorymember();

$edt1_to      = isset($_SESSION['edt1_to'])      ? $_SESSION['edt1_to']      : '';
$edt1_replay  = isset($_SESSION['edt1_replay'])  ? $_SESSION['edt1_replay']  : '';
$edt1_subject = isset($_SESSION['edt1_subject']) ? $_SESSION['edt1_subject'] : '';
$edt1_body    = isset($_SESSION['edt1_body'])    ? $_SESSION['edt1_body']    : '';

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
        $edt1_to      = (isset($_POST['edt1_to']))      ? $_POST['edt1_to'] : 0;
        $edt1_replay  = (isset($_POST['edt1_replay']))  ? $_POST['edt1_replay'] : 0;
        $edt1_subject = (isset($_POST['edt1_subject'])) ? $_POST['edt1_subject'] : '';
        $edt1_body    = (isset($_POST['edt1_body']))    ? $_POST['edt1_body']    : '';
        $_SESSION['edt1_to']      = $edt1_to;
        $_SESSION['edt1_replay']  = $edt1_replay;
        $_SESSION['edt1_subject'] = $edt1_subject;
        $_SESSION['edt1_body']    = $edt1_body;

        $err = validateForm($edt1_to, $edt1_replay, $edt1_subject, $edt1_body);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit1_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit1_edit.html');
        }
    } elseif ($pagemode === 'edit') {
        writeLog("pagemode = edit, edt1_subject=$edt1_subject");
        include ('edit1_edit.html');
    } elseif ($pagemode === 'finish') {
        $r = new reply();
        $m = new member();
        $mlog = new maillog();
        
        $fromAddress = $r->getAddressById($edt1_replay);
        $from = $fromAddress;
        $sender = $fromAddress;
        $aryTo[] = $edt1_to;

		$dataHs = $replycategorymember->getReplyCategoryMember();
		$bccLs = array();
		$replyLs = $r->selectIdReply();
		$memberLs = $m->selectIdName();
		foreach($dataHs as $data){
			if($replyLs[$data['reply_id']] === $from){
				$add_bcc_addressLs = explode(",",$data['member_id']);
				foreach($add_bcc_addressLs as $each_bcc_address){
					if(!in_array($each_bcc_address,$bccLs)){
						$bccLs[] = $each_bcc_address;
					}
				}
			}
		}
		$bccAddressLs = array();
		foreach($bccLs as $bccno){
			$bccAddressLs[] = $memberLs[$bccno];
		}
//		$bcc = implode(',',$bccAddressLs);
        $header = classFunction::create_mailHeader($from, $sender, $aryTo, $edt1_subject);
        classFunction::sendMail($header, $edt1_body);
        $mlog->insertMailLog($from, $sender, $edt1_to, $edt1_subject, $header, $edt1_body, 1);
        
        include ('edit1_finish.html');
    }
}
?>