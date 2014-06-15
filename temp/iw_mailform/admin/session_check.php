<?php
require_once ('../boot.php');
require_once ('admin.class.php');
session_name(mf_session_name);
session_start();
//UtilDebug::showParam();
if (isset($_SESSION["PASSWORD"]) && $_SESSION["PASSWORD"] != null) {
	$admin = new admin();
	$users = $admin -> getUsers($_SESSION["LOGIN_ID"]);
	foreach ($users as $user) {
		if (md5($user -> password) === $_SESSION["PASSWORD"]) {
			//print "Login success";
			break;
		};
	}
} else {
	session_destroy();
	//セッション破棄
	// print "Login failed";
	header("Location:session_error.php");
}

?>