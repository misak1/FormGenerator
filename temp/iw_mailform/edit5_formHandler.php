<?php
// 対になるライブラリをrequireする。
require_once ('edit5_lib.php');
$and_action_href = 'edit5_index.php';
startSession();
require_once ('template.class.php');

$edt5_name = isset($_SESSION['edt5_name']) ? $_SESSION['edt5_name'] : '';
$edt5_note = isset($_SESSION['edt5_note']) ? $_SESSION['edt5_note'] : '';

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
        $edt5_name    = (isset($_POST['edt5_name']))    ? $_POST['edt5_name'] : '';
        $edt5_note    = (isset($_POST['edt5_note']))    ? $_POST['edt5_note']    : '';
        $_SESSION['edt5_name'] = $edt5_name;
        $_SESSION['edt5_note']    = $edt5_note;

        $err = validateForm($edt5_name, $edt5_note);
        writeLog("err=$err");
        if ($err === '') {
            include ('edit5_confirm.html');
        } else {
            writeLog("問題があるから差し戻す");
            include ('edit5_edit.html');
        }
    } elseif ($pagemode === 'edit') {
        writeLog("pagemode = edit, edt5_name=$edt5_name");
        include ('edit5_edit.html');
    } elseif ($pagemode === 'finish') {
        
        $r = new group();
        $r->insertGroup($edt5_name, $edt5_note);
        //$header = classFunction::create_mailHeader($from, $sender, $aryTo, $edt5_name);
        //classFunction::sendMail($header, $edt5_note);
        
        include ('edit5_finish.html');
    }
}
?>