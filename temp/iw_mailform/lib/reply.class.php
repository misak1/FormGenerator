<?php
class reply {
    function __construct() {
    }

    public function insertReply($mail_address, $mail_note) {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("INSERT INTO `mf_treply` (`reply_id`, `mail_address`, `mail_note`, `add_date`, `upd_date`) VALUES (NULL, '%s', '%s', NULL, NULL);"
                , mysql_real_escape_string($mail_address)
                , mysql_real_escape_string($mail_note));
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        classDb::close($link);
        if (!$result) {
            return false;
        }
        return true;
    }

    public function updateReply($modify_reply_id, $mail_address, $mail_note) {
        global $log;
        $link = classDb::connect();
    	$sql = sprintf("UPDATE `mf_treply` SET `mail_address`= '%s' , `mail_note`= '%s', `upd_date`= '%s' WHERE `reply_id`='%s';"
    		, mysql_real_escape_string($mail_address)
    		, mysql_real_escape_string($mail_note)
    		, date('Y-m-d H:i:s')
    		, mysql_real_escape_string($modify_reply_id));
    	$sql = Util::deleteCRLF($sql);
    	writeLog($sql);
    	$log->info($sql);
    	$result = mysql_query($sql, $link);
    	classDb::close($link);
    	if (!$result) {
    		return false;
    	}
    	return true;
    }

    public function deleteReply($delete_reply_id) {
        global $log;
        $link = classDb::connect();
    	$sql = sprintf("DELETE FROM `mf_treply` WHERE `reply_id`='%s';"
    		, mysql_real_escape_string($delete_reply_id));
    	$sql = Util::deleteCRLF($sql);
    	writeLog($sql);
    	$log->info($sql);
    	$result = mysql_query($sql, $link);
    	classDb::close($link);
    	if (!$result) {
    		return false;
    	}
    	return true;
    }

    public function selectReply() {
        global $log;
        $link = classDb::connect();
        $sql = "SELECT `reply_id`, `mail_address` FROM `mf_treply`";
        $log->info($sql);
        $r = mysql_query($sql, $link);
        $a = array();
        while ($row = mysql_fetch_assoc($r)) {
            $a[] = $row;
        }
        classDb::close($link);
        return $a;
    }
    
    public function getReplys() {
        global $log;
        $link = classDb::connect();
        $sql = "SELECT `reply_id`, `mail_address`, `mail_note` FROM `mf_treply` ORDER BY `upd_date`;";
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        $aryR = array();
        while ($row = mysql_fetch_assoc($result)) {
            $a = array();
            $a['reply_id'] = $row['reply_id'];
            $a['mail_address'] = $row['mail_address'];
            $a['mail_note'] = $row['mail_note'];
            $aryR[] = $a;
        }
        classDb::close($link);
        return $aryR;
    }

    public function getAddressById($reply_id) {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("SELECT `mail_address` FROM `mf_treply` WHERE `reply_id` = '%s';", mysql_real_escape_string($reply_id));
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        $address = "";
        while ($row = mysql_fetch_assoc($result)) {
            $address = $row['mail_address'];
        }
        classDb::close($link);
        return $address;
    }

    public function selectIdReply() {
        $a = $this -> selectReply();
        $r = array();
        foreach ($a as $a2) {
            $r["{$a2["reply_id"]}"] = $a2['mail_address'];
        }
        return $r;
    }

    public function selectIdReplySc() {
        $r = $this -> selectIdReply();
        $r["0"] = "選択して下さい。";
        ksort($r);
        return $r;
    }

    public function makeSelect($aryAttribute, $selectValue = 0) {
        $r = $this -> selectIdReply();
        return classHtml::makeSelect($aryAttribute, $r, $selectValue);
    }
    
    public function makeSelectSc($aryAttribute, $selectValue = 0) {
        $r = $this -> selectIdReplySc();
        return classHtml::makeSelect($aryAttribute, $r, $selectValue);
    }

}
/*
require_once '../boot.php';
require_once '../classDb.php';
$a = new reply();
//var_dump($a->selectReply());
var_dump($a->makeSelectSc());
 */
?>