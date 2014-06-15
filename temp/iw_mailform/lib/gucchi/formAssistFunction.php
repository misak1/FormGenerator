<?php
function genKEY(){
    return mt_rand();
}
function genTAG(){
    return 'key' . mt_rand(1000, 9999);
}
function getKEYTAG(){
    $key = genKEY();
    $tag = genTAG();
    $_SESSION['tagname'] = $tag;
    $_SESSION['keyvalue'] = $key;
    return '<input type="hidden" name="' . $tag . '" value="' . $key . '" />';
}
function isValidPage(){
    $ret = 0;
    if(isset($_SESSION['tagname'])){
        $t = $_SESSION['tagname'];

        writeLog("tagname=$t");
        writeLog("keyvalue=". $_SESSION['keyvalue']);
        writeLog("_POST[" . $t . "]=". $_POST[$t]);
        writeLog(($_POST[$t] == $_SESSION['keyvalue']));

        if(isset($_POST[$t]) && isset($_SESSION['keyvalue']) && $_POST[$t] == $_SESSION['keyvalue']){
            $ret = 1;
        }
    }
    return $ret;
}
function doRedirect($path){
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: ${path}");
}
function startSession(){
    session_name(mf_session_name);
    session_start();
}
?>