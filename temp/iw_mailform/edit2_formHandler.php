<?php
// 対になるライブラリをrequireする。
require_once ('edit2_lib.php');
$and_action_href = 'edit2_index.php';
startSession();
require_once ('template.class.php');

$edt2_modify_template_id = isset($_SESSION['edt2_modify_template_id']) ? $_SESSION['edt2_modify_template_id'] : '';
$edt2_delete_template_id = isset($_SESSION['edt2_delete_template_id']) ? $_SESSION['edt2_delete_template_id'] : '';
$edt2_subject = isset($_SESSION['edt2_subject']) ? $_SESSION['edt2_subject'] : '';
$edt2_body    = isset($_SESSION['edt2_body'])    ? $_SESSION['edt2_body']    : '';
$edt2_eflg    = isset($_SESSION['edt2_eflg'])  ? $_SESSION['edt2_eflg']  : '';

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
        $edt2_subject = (isset($_POST['edt2_subject'])) ? $_POST['edt2_subject'] : '';
        $edt2_body    = (isset($_POST['edt2_body']))    ? $_POST['edt2_body']    : '';
        $edt2_eflg    = (isset($_POST['edt2_eflg']))    ? $_POST['edt2_eflg'] : 0;
        $_SESSION['edt2_subject'] = $edt2_subject;
        $_SESSION['edt2_body']    = $edt2_body;
        $_SESSION['edt2_eflg']    = $edt2_eflg;

        $err = validateForm($edt2_subject, $edt2_body, $edt2_eflg);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit2_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit2_edit.html');
        }
    } elseif ($pagemode === 'edit') {
        writeLog("pagemode = edit, edt2_subject=$edt2_subject");
        include ('edit2_edit.html');
    } elseif ($pagemode === 'modify') {
        $edt2_modify_template_id  = (isset($_POST['template_id']))  ? $_POST['template_id'] : $edt2_modify_template_id;
        $_SESSION['edt2_modify_template_id'] = $edt2_modify_template_id;
		if($_POST['template_id']){
			//選択されたIDから、データ取得
	        $r = new template();
			$templateLs = $r->getTemplate();
			foreach($templateLs as $eachTemplate){
				if($eachTemplate['template_id'] === $edt2_modify_template_id){
					$edt2_subject = $eachTemplate['mail_subject'];
					$edt2_body = $eachTemplate['mail_body'];
					$edt2_view_eflg = ($eachTemplate['effective_flg'] == 1) ? 'checked' : '';
					break;
				}
			}
		} else {
			$edt2_view_eflg = ($edt2_eflg) ? 'checked' : '';
		}
        writeLog("pagemode = modify, edt2_id=$edt2_modify_template_id");
        include ('edit2_modify.html');
	} elseif ($pagemode === 'modify_confirm'){
        writeLog("pagemode is modify_confirm");
        $edt2_subject    = (isset($_POST['edt2_subject']))    ? $_POST['edt2_subject']    : '';
        $edt2_body    = (isset($_POST['edt2_body']))    ? $_POST['edt2_body'] : '';
		$edt2_eflg    = (isset($_POST['edt2_eflg']))  ? $_POST['edt2_eflg']  : '';
        $_SESSION['edt2_subject']    = $edt2_subject;
        $_SESSION['edt2_body']    = $edt2_body;
        $_SESSION['edt2_eflg']    = $edt2_eflg;
		$edt2_view_eflg = (isset($_POST['edt2_eflg']))  ? '有効'  : '無効';

        $err = validateForm($edt2_subject, $edt2_body,$edt2_eflg);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit2_modify_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit2_edit.html');
        }
    } elseif ($pagemode === 'modify_finish') {
		if($edt2_eflg == ""){ $edt2_eflg = 0; }
        $r = new template();
        $r->updateTemplate($edt2_modify_template_id,$edt2_subject,$edt2_body,$edt2_eflg);
        include ('edit2_modify_finish.html');
    } elseif ($pagemode === 'delete') {
        $edt2_delete_template_id  = (isset($_POST['template_id']))  ? $_POST['template_id'] : $edt2_delete_template_id;
        $_SESSION['edt2_delete_template_id'] = $edt2_delete_template_id;
		//選択されたIDから、データ取得
        $r = new template();
		$templateLs = $r->getTemplate();
		foreach($templateLs as $eachTemplate){
			if($eachTemplate['template_id'] === $edt2_delete_template_id){
				$edt2_subject = $eachTemplate['mail_subject'];
				$edt2_body = $eachTemplate['mail_body'];
				break;
			}
		}
        writeLog("pagemode = delete, edt2_id=$edt2_delete_template_id");
        include ('edit2_delete.html');
    } elseif ($pagemode === 'delete_finish') {
        $r = new template();
        $r->deleteTemplate($edt2_delete_template_id);
        include ('edit2_delete_finish.html');
    } elseif ($pagemode === 'finish') {
        
        $r = new template();
        $r->insertTemplate($edt2_subject, $edt2_body);
        include ('edit2_finish.html');
    }
}
?>