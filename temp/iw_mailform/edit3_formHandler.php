<?php
// 対になるライブラリをrequireする。
require_once ('edit3_lib.php');
$and_action_href = 'edit3_index.php';
startSession();
require_once ('template.class.php');

$edt3_name = isset($_SESSION['edt3_name']) ? $_SESSION['edt3_name'] : '';
$edt3_note = isset($_SESSION['edt3_note']) ? $_SESSION['edt3_note'] : '';

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
        $edt3_name    = (isset($_POST['edt3_name']))    ? $_POST['edt3_name'] : '';
        $edt3_note    = (isset($_POST['edt3_note']))    ? $_POST['edt3_note']    : '';
        $_SESSION['edt3_name'] = $edt3_name;
        $_SESSION['edt3_note']    = $edt3_note;

        $err = validateForm($edt3_name, $edt3_note);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit3_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit3_edit.html');
        }
    } elseif ($pagemode === 'edit') {
        writeLog("pagemode = edit, edt3_name=$edt3_name");
        include ('edit3_edit.html');
    } elseif ($pagemode === 'finish') {
        
        $r = new group();
        $r->insertGroup($edt3_name, $edt3_note);
        //$header = classFunction::create_mailHeader($from, $sender, $aryTo, $edt3_name);
        //classFunction::sendMail($header, $edt3_note);
        
        include ('edit3_finish.html');
    }
}
?>