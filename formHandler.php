<?php
startSession();

foreach ($_POST as $k => $v) {
//echo("_POST[" . $k . "]=$v");
}
foreach ($_SESSION as $k => $v) {
//echo("_SESSION[" . $k . "]=$v");
}

if (!isValidPage()) {
	writeLog("key not match, redirect to index");
	doRedirect("./"); // inputへ飛ばす
} else {
	$pagemode = (isset($_POST['pagemode'])) ? $_POST['pagemode'] : 'index';
	writeLog("key matches: pagemode=" . $pagemode);
	if ($pagemode === 'confirm') {
	        echo("pagemode is confirm");
                /*
		$category = (isset($_POST['category'])) ? $_POST['category'] : 0;
		$company = (isset($_POST['company'])) ? $_POST['company'] : '';
		$username = (isset($_POST['username'])) ? $_POST['username'] : '';
		$email = (isset($_POST['email'])) ? $_POST['email'] : '';
		$tel = (isset($_POST['tel'])) ? $_POST['tel'] : '';
		$content = (isset($_POST['content'])) ? $_POST['content'] : '';
		$policy = (isset($_POST['policy'])) ? 1 : 0;
                */
echo "<pre>";
var_dump($_POST);
echo "</pre>";

		//$err = validateForm($category, $company, $username, $email, $tel, $content, $policy);
		echo ("err=$err");
		if ($err === '') {
			include ('../contact-2014/template/confirm.html');
		} else {
			writeLog("問題があるから差し戻す");
			include ('../contact-2014/template/edit.html');
		}
	} elseif ($pagemode === 'edit') {
		echo ("pagemode = edit, username=$username");
		include ('../contact-2014/template/edit.html');
	} elseif ($pagemode === 'finish') {
		$r = new reply();

		include ('../contact-2014/template/finish.html');
	}
}
?>