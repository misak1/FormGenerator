<?php
// 対になるライブラリをrequireする。
require_once ('edit7_lib.php');
$and_action_href = 'edit7_index.php';
startSession();
require_once ('category.class.php');

$edt7_delete_category_id = isset($_SESSION['edt7_delete_category_id']) ? $_SESSION['edt7_delete_category_id'] : '';
$edt7_modify_category_id    = isset($_SESSION['edt7_modify_category_id'])    ? $_SESSION['edt7_modify_category_id']    : '';

$edt7_contents_id = isset($_SESSION['edt7_contents_id']) ? $_SESSION['edt7_contents_id'] : '';
$edt7_prioryty_no = isset($_SESSION['edt7_prioryty_no']) ? $_SESSION['edt7_prioryty_no'] : '';
$edt7_category_name    = isset($_SESSION['edt7_category_name'])    ? $_SESSION['edt7_category_name']    : '';
$edt7_eflg    = isset($_SESSION['edt7_eflg'])  ? $_SESSION['edt7_eflg']  : '';

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
        
        $edt7_contents_id = (isset($_POST['edt7_contents_id'])) ? $_POST['edt7_contents_id'] : 0;
        $edt7_prioryty_no = (isset($_POST['edt7_prioryty_no'])) ? $_POST['edt7_prioryty_no'] : 0;
        $edt7_category_name    = (isset($_POST['edt7_category_name'])) ? $_POST['edt7_category_name'] : '';
        $edt7_eflg    = (isset($_POST['edt7_eflg'])) ? $_POST['edt7_eflg'] : 0;
        
        $_SESSION['edt7_contents_id'] = $edt7_contents_id;
        $_SESSION['edt7_prioryty_no'] = $edt7_prioryty_no;
        $_SESSION['edt7_category_name']    = $edt7_category_name;
        $_SESSION['edt7_eflg']    = $edt7_eflg;
        
        $err = validateForm($edt7_contents_id, $edt7_prioryty_no, $edt7_category_name, $edt7_eflg);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit7_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit7_edit.html');
        }
    } elseif ($pagemode === 'edit') {
        writeLog("pagemode = edit, edt7_category_name=$edt7_category_name");
        include ('edit7_edit.html');
    } elseif ($pagemode === 'modify') {
        $edt7_modify_category_id  = (isset($_POST['category_id']))  ? $_POST['category_id'] : $edt7_modify_category_id;
        $_SESSION['edt7_modify_category_id'] = $edt7_modify_category_id;
		if($_POST['category_id']){
			//選択されたIDから、データ取得
	        $r = new category();
			$categoryLs = $r->getCategory();
			foreach($categoryLs as $eachCategory){
				if($eachCategory['category_id'] === $edt7_modify_category_id){
					
					$edt7_contents_id = $eachCategory['contents_id'];
					$edt7_prioryty_no = $eachCategory['prioryty_no'];
					$edt7_category_name = $eachCategory['category_name'];
					$edt7_view_eflg = ($eachCategory['effective_flg'] == 1) ? 'checked' : '';
					break;
				}
			}
		} else {
			$edt7_view_eflg = ($edt7_eflg) ? 'checked' : '';
		}
        writeLog("pagemode = modify, edt7_id=$edt7_modify_category_id");
        include ('edit7_modify.html');
	} elseif ($pagemode === 'modify_confirm'){
        writeLog("pagemode is modify_confirm");
        
        $edt7_contents_id    = (isset($_POST['edt7_contents_id']))    ? $_POST['edt7_contents_id']    : '';
        $edt7_prioryty_no    = (isset($_POST['edt7_prioryty_no']))    ? $_POST['edt7_prioryty_no']    : '';
        $edt7_category_name    = (isset($_POST['edt7_category_name']))    ? $_POST['edt7_category_name'] : '';
		$edt7_eflg    = (isset($_POST['edt7_eflg']))  ? $_POST['edt7_eflg']  : '';
		
		$_SESSION['edt7_contents_id']    = $edt7_contents_id;
        $_SESSION['edt7_prioryty_no']    = $edt7_prioryty_no;
        $_SESSION['edt7_category_name']    = $edt7_category_name;
        $_SESSION['edt7_eflg']    = $edt7_eflg;
		$edt7_view_eflg = (isset($_POST['edt7_eflg']))  ? '有効'  : '無効';

        $err = validateForm($edt7_contents_id, $edt7_prioryty_no, $edt7_category_name);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit7_modify_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit7_edit.html');
        }
    } elseif ($pagemode === 'modify_finish') {
		if($edt7_eflg == ""){ $edt7_eflg = 0; }
        $r = new category();
        $r->updateCategory($edt7_modify_category_id,$edt7_prioryty_no,$edt7_category_name,$edt7_eflg, $edt7_contents_id);
        $complite_massage = 'カテゴリーを更新しました。';
        include ('edit7_finish.html');
    } elseif ($pagemode === 'delete') {
        $edt7_delete_category_id  = (isset($_POST['category_id']))  ? $_POST['category_id'] : $edt7_delete_category_id;
        $_SESSION['edt7_delete_category_id'] = $edt7_delete_category_id;
		//選択されたIDから、データ取得
        $r = new category();
		$categoryLs = $r->getCategory();
		foreach($categoryLs as $eachCategory){
			if($eachCategory['category_id'] === $edt7_delete_category_id){
				$edt7_contents_id = $eachCategory['contents_id'];
				$edt7_prioryty_no = $eachCategory['prioryty_no'];
				$edt7_category_name = $eachCategory['category_name'];
				break;
			}
		}
        writeLog("pagemode = delete, edt7_id=$edt7_delete_category_id");
        include ('edit7_delete.html');
    } elseif ($pagemode === 'delete_finish') {
        $r = new category();
        $r->deleteCategory($edt7_delete_category_id);
        $complite_massage = 'カテゴリーを削除しました。';
        include ('edit7_finish.html');
    } elseif ($pagemode === 'finish') {
        
        $r = new category();
        $r->insertCategory($edt7_prioryty_no, $edt7_category_name, $edt7_contents_id);
        //$header = classFunction::create_mailHeader($from, $sender, $aryTo, $edt2_subject);
        //classFunction::sendMail($header, $edt2_body);
        $complite_massage = 'カテゴリーを追加しました。';
        include ('edit7_finish.html');
    }
}
?>