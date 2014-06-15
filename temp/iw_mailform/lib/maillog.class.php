<?php
class maillog  {
    function __construct() {
    }

    public function insertMailLog($from, $reply, $to, $subject, $header, $body, $type=NULL) {
        global $log;
        $isResult = false;
        $link = classDb::connect();
		if($type){
	        $sql = sprintf("INSERT INTO `mf_tmaillog` (`no`, `from`, `reply`, `to`, `subject`, `header`, `body`, `add_date`, `upd_date`, `type`) VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', NULL, NULL, 1);"
	                , mysql_real_escape_string($from)
	                , mysql_real_escape_string($reply)
	                , mysql_real_escape_string($to)
	                , mysql_real_escape_string($subject)
	                , mysql_real_escape_string($header)
	                , mysql_real_escape_string($body));
		} else {
	        $sql = sprintf("INSERT INTO `mf_tmaillog` (`no`, `from`, `reply`, `to`, `subject`, `header`, `body`, `add_date`, `upd_date`) VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', NULL, NULL);"
	                , mysql_real_escape_string($from)
	                , mysql_real_escape_string($reply)
	                , mysql_real_escape_string($to)
	                , mysql_real_escape_string($subject)
	                , mysql_real_escape_string($header)
	                , mysql_real_escape_string($body));
		}
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        classDb::close($link);
        if (!$result) {
            return false;
        }
        return true;
    }

    public function getFromMaillog() {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("SELECT `no`, `from`, `reply`, `to`, `subject`, `header`, `body`, `add_date`, `upd_date` FROM  `mf_tmaillog` WHERE type is NULL order by add_date desc;");
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        $arrayUser = array();
        while ($row = mysql_fetch_assoc($result)) {
            $maillog = array();
            $maillog['no']       = $row['no'];
            $maillog['from']     = $row['from'];
            $maillog['reply']    = $row['reply'];
            $maillog['to']       = $row['to'];
            $maillog['subject']  = $row['subject'];
            $maillog['header']   = $row['header'];
            $maillog['body']     = $row['body'];
            $maillog['add_date'] = $row['add_date'];
            $arrayUser[] = $maillog;
        }
        classDb::close($link);
        return $arrayUser;
    }

    public function getToMaillog() {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("SELECT `no`, `from`, `reply`, `to`, `subject`, `header`, `body`, `add_date`, `upd_date` FROM  `mf_tmaillog` WHERE type = 1 order by add_date desc;");
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        $arrayUser = array();
        while ($row = mysql_fetch_assoc($result)) {
            $maillog = array();
            $maillog['no']       = $row['no'];
            $maillog['from']     = $row['from'];
            $maillog['reply']    = $row['reply'];
            $maillog['to']       = $row['to'];
            $maillog['subject']  = $row['subject'];
            $maillog['header']   = $row['header'];
            $maillog['body']     = $row['body'];
            $maillog['add_date'] = $row['add_date'];
            $arrayUser[] = $maillog;
        }
        classDb::close($link);
        return $arrayUser;
    }
}
/*
require_once('../boot.php');
$m = new maillog();
$from = "shibata@imagica-imageworks.co.jp";
$to = "shibata@imagica-imageworks.co.jp";
$subject= "shibata@imagica-imageworks.co.jp";
$header= "shibata@imagica-imageworks.co.jp";
$body= "shibata@imagica-imageworks.co.jp";
$r = $m->insertMailLog($from,$from, $to, $subject, $header, $body);
var_dump($r);
 */

?>