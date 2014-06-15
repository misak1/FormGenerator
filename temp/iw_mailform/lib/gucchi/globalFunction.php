<?php 
function writeLog($msg){
    date_default_timezone_set( 'Asia/Tokyo');
    $fp = fopen(gucchi_log, 'ab');
    if($fp){
        fwrite($fp, date("Y/m/d H:i:s") . " " . $msg . "\n");
    }
    fclose($fp);
}
?>