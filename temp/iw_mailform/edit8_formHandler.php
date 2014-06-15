<?php
// 対になるライブラリをrequireする。
require_once ('edit8_lib.php');
$and_action_href = 'edit8_index.php';
startSession();
//require_once ('template.class.php');

require_once ('member.class.php');

$edt8_delete_member_id = isset($_SESSION['edt8_delete_member_id']) ? $_SESSION['edt8_delete_member_id'] : '';
$edt8_modify_member_id = isset($_SESSION['edt8_modify_member_id']) ? $_SESSION['edt8_modify_member_id'] : '';
$edt8_name = isset($_SESSION['edt8_name']) ? $_SESSION['edt8_name'] : '';
$edt8_note = isset($_SESSION['edt8_note']) ? $_SESSION['edt8_note'] : '';

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
		$edt8_name = (isset($_POST['edt8_name'])) ? $_POST['edt8_name'] : '';
		$edt8_note = (isset($_POST['edt8_note'])) ? $_POST['edt8_note'] : '';
		$_SESSION['edt8_name'] = $edt8_name;
		$_SESSION['edt8_note'] = $edt8_note;

		$err = validateForm($edt8_name, $edt8_note);
		writeLog("err=$err");
		if ($err === '') {
			include ('edit8_confirm.html');
		} else {
			writeLog("問題があるから差し戻す");
			include ('edit8_edit.html');
		}
	} elseif ($pagemode === 'edit') {
		writeLog("pagemode = edit, edt8_name=$edt8_name");
		include ('edit8_edit.html');
	} elseif ($pagemode === 'modify') {
		$edt8_modify_member_id = (isset($_POST['member_id'])) ? $_POST['member_id'] : $edt8_modify_member_id;
		$_SESSION['edt8_modify_member_id'] = $edt8_modify_member_id;
		if ($_POST['member_id']) {
			//選択されたIDから、データ取得
			$r = new member();
			$memberLs = $r -> getMembers();
			foreach ($memberLs as $eachMember) {
				if ($eachMember['member_id'] === $edt8_modify_member_id) {
					$edt8_name = $eachMember['user_name'];
					$edt8_note = $eachMember['mail_address'];
					break;
				}
			}
		}
		writeLog("pagemode = modify, edt8_id=$edt8_modify_member_id");
		include ('edit8_modify.html');
	} elseif ($pagemode === 'modify_confirm') {
		writeLog("pagemode is modify_confirm");
		$edt8_name = (isset($_POST['edt8_name'])) ? $_POST['edt8_name'] : '';
		$edt8_note = (isset($_POST['edt8_note'])) ? $_POST['edt8_note'] : '';
		$_SESSION['edt8_name'] = $edt8_name;
		$_SESSION['edt8_note'] = $edt8_note;

		$err = validateForm($edt8_name, $edt8_note);
		writeLog("err=$err");
		if ($err === '') {
			include ('edit8_modify_confirm.html');
		} else {
			writeLog("問題があるから差し戻す");
			include ('edit8_edit.html');
		}
	} elseif ($pagemode === 'modify_finish') {
		$r = new member();
		$r -> updateMember($edt8_modify_member_id, $edt8_name, $edt8_note);
		$complite_massage = 'メンバーを更新しました。';
		include ('edit8_finish.html');
	} elseif ($pagemode === 'delete') {
		$edt8_delete_member_id = (isset($_POST['member_id'])) ? $_POST['member_id'] : $edt8_delete_member_id;
		$_SESSION['edt8_delete_member_id'] = $edt8_delete_member_id;
		//選択されたIDから、データ取得
		$r = new member();
		$memberLs = $r -> getMembers();
		foreach ($memberLs as $eachMember) {
			if ($eachMember['member_id'] === $edt8_delete_member_id) {
				$edt8_name = $eachMember['user_name'];
				$edt8_note = $eachMember['mail_address'];
				break;
			}
		}
		writeLog("pagemode = delete, edt8_id=$edt8_delete_member_id");
		include ('edit8_delete.html');
	} elseif ($pagemode === 'delete_finish') {
		$r = new member();
		$r -> deleteMember($edt8_delete_member_id);
		$complite_massage = 'メンバーを削除しました。';
		include ('edit8_finish.html');
	} elseif ($pagemode === 'finish') {

		$r = new member();
		$r -> insertMember($edt8_name, $edt8_note);
		$complite_massage = 'メンバーを追加しました。';
		include ('edit8_finish.html');
	}
}
?>