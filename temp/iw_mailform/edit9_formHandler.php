<?php
// 対になるライブラリをrequireする。
require_once ('edit9_lib.php');
$and_action_href = 'edit9_index.php';
startSession();
require_once ('categorytemplate.class.php');
require_once ('mailtotype.class.php');

$edt9_delete_categorytemplate_id = isset($_SESSION['edt9_delete_categorytemplate_id']) ? $_SESSION['edt9_delete_categorytemplate_id'] : '';
$edt9_modify_categorytemplate_id    = isset($_SESSION['edt9_modify_categorytemplate_id'])    ? $_SESSION['edt9_modify_categorytemplate_id']    : '';
$edt9_category_id = isset($_SESSION['edt9_category_id']) ? $_SESSION['edt9_category_id'] : '';
$edt9_type    = isset($_SESSION['edt9_type'])    ? $_SESSION['edt9_type']    : '';
$edt9_template_id    = isset($_SESSION['edt9_template_id'])    ? $_SESSION['edt9_template_id']    : '';

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
        $edt9_category_id = (isset($_POST['edt9_category_id'])) ? $_POST['edt9_category_id'] : '';
        $edt9_type    = (isset($_POST['edt9_type'])) ? $_POST['edt9_type'] : '';
        $edt9_template_id    = (isset($_POST['edt9_template_id'])) ? $_POST['edt9_template_id'] : '';
        $_SESSION['edt9_category_id'] = $edt9_category_id;
        $_SESSION['edt9_type']    = $edt9_type;
        $_SESSION['edt9_template_id']    = $edt9_template_id;
        $err = validateForm($edt9_category_id, $edt9_type, $edt9_template_id);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit9_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit9_edit.html');
        }
    } elseif ($pagemode === 'edit') {
        writeLog("pagemode = edit, edt9_id=$edt9_categorytemplate_id");
        include ('edit9_edit.html');
    } elseif ($pagemode === 'modify') {
        $edt9_modify_categorytemplate_id  = (isset($_POST['categorytemplate_id']))  ? $_POST['categorytemplate_id'] : $edt9_modify_categorytemplate_id;
        $_SESSION['edt9_modify_categorytemplate_id'] = $edt9_modify_categorytemplate_id;
		if($_POST['categorytemplate_id']){
			//選択されたIDから、データ取得
	        $r = new categorytemplate();
			$categorytemplateLs = $r->getCategoryTemplate();
			foreach($categorytemplateLs as $eachcategorytemplate){
				if($eachcategorytemplate['categorytemplate_id'] === $edt9_modify_categorytemplate_id){
					$edt9_category_id = $eachcategorytemplate['category_id'];
					$edt9_type = $eachcategorytemplate['type'];
					$edt9_template_id = $eachcategorytemplate['template_id'];
					break;
				}
			}
		}
        writeLog("pagemode = modify, edt9_id=$edt9_modify_categorytemplate_id");
        include ('edit9_modify.html');
	} elseif ($pagemode === 'modify_confirm'){
        writeLog("pagemode is modify_confirm");
        $edt9_category_id    = (isset($_POST['edt9_category_id']))    ? $_POST['edt9_category_id']    : '';
        $edt9_type    = (isset($_POST['edt9_type']))    ? $_POST['edt9_type'] : '';
        $edt9_template_id    = (isset($_POST['edt9_template_id']))    ? $_POST['edt9_template_id'] : '';
        $_SESSION['edt9_category_id']    = $edt9_category_id;
        $_SESSION['edt9_type']    = $edt9_type;
        $_SESSION['edt9_template_id']    = $edt9_template_id;

        $err = validateForm($edt9_category_id, $edt9_type, $edt9_template_id);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit9_modify_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit9_edit.html');
        }
    } elseif ($pagemode === 'modify_finish') {
        $r = new categorytemplate();
        $r->updateCategoryTemplate($edt9_modify_categorytemplate_id,$edt9_category_id,$edt9_type,$edt9_template_id);
        include ('edit9_modify_finish.html');
    } elseif ($pagemode === 'delete') {
        $edt9_delete_categorytemplate_id  = (isset($_POST['categorytemplate_id']))  ? $_POST['categorytemplate_id'] : $edt9_delete_categorytemplate_id;
        $_SESSION['edt9_delete_categorytemplate_id'] = $edt9_delete_categorytemplate_id;
		//選択されたIDから、データ取得
        $r = new categorytemplate();
		$categorytemplateLs = $r->getCategoryTemplate();
		foreach($categorytemplateLs as $eachcategorytemplate){
			if($eachcategorytemplate['categorytemplate_id'] === $edt9_delete_categorytemplate_id){
				$edt9_category_id = $eachcategorytemplate['category_id'];
				$edt9_type = $eachcategorytemplate['type'];
				$edt9_template_id = $eachcategorytemplate['template_id'];
				break;
			}
		}
        writeLog("pagemode = delete, edt9_id=$edt9_delete_categorytemplate_id");
        include ('edit9_delete.html');
    } elseif ($pagemode === 'delete_finish') {
        $r = new categorytemplate();
        $r->deleteCategoryTemplate($edt9_delete_categorytemplate_id);
        include ('edit9_delete_finish.html');
    } elseif ($pagemode === 'finish') {
        
        $r = new categorytemplate();
        $r->insertCategoryTemplate($edt9_category_id, $edt9_type, $edt9_template_id);
        //$header = classFunction::create_mailHeader($from, $sender, $aryTo, $edt2_subject);
        //classFunction::sendMail($header, $edt2_body);
        
        include ('edit9_finish.html');
    }
}
?>