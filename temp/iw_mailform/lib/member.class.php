<?php
class member {
    function __construct() {
    }
    public function insertMember($user_name, $mail_address) {
        global $log;
        $isResult = false;
        $link = classDb::connect();
        $sql = sprintf("INSERT INTO `mf_tmember` (`member_id`, `user_name`, `mail_address`, `add_date`, `upd_date`) VALUES (NULL, '%s', '%s', NULL, NULL);"
                , mysql_real_escape_string($user_name)
                , mysql_real_escape_string($mail_address));
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

    public function deleteMember($member_id) {
    	global $log;
    	$isResult = false;
    	$link = classDb::connect();

    	$sql = sprintf("DELETE FROM `mf_tmember` WHERE member_id='%s'", $member_id);

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

    public function updateMember($member_id,$user_name,$mail_address) {
    	global $log;
    	$isResult = false;
    	$link = classDb::connect();

    	$sql = sprintf("UPDATE `mf_tmember` SET `user_name`= '%s' , `mail_address`= '%s' , `upd_date`= '%s' WHERE `member_id`='%s'"
    	, mysql_real_escape_string($user_name)
    	, mysql_real_escape_string($mail_address)
    	, date('Y-m-d H:i:s')
    	, mysql_real_escape_string($member_id));

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

    public function getMembers() {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("SELECT `member_id`, `user_name`, `mail_address` FROM `mf_tmember` ORDER BY `member_id`;");
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        $arrayUser = array();
        while ($row = mysql_fetch_assoc($result)) {
            $member = array();
            $member['member_id'] = $row['member_id'];
            $member['user_name'] = $row['user_name'];
            $member['mail_address'] = $row['mail_address'];
            $arrayUser[] = $member;
        }
        classDb::close($link);
        return $arrayUser;
    }

    public function selectMember() {
        $link = classDb::connect();
        $sql = "SELECT * FROM mf_tmember;";
        $r = mysql_query($sql, $link);
        $a = array();
        while ($row = mysql_fetch_assoc($r)) {
            $a[] = $row;
        }
        classDb::close($link);
        return $a;
    }

    public function selectIdName() {
        $a = $this->selectMember();
        $r = array();
        foreach ($a as $a2) {
            $r["{$a2["member_id"]}"] = $a2['mail_address'];
        }
        return $r;
    }

    public function selectIdNameSc() {
        $r = $this->selectIdName();
        $r["0"] = "選択して下さい。";
        ksort($r);
        return $r;
    }

    public function makeSelect($aryAttribute, $selectValue = 0) {
        $r = $this->selectIdName();
        return classHtml::makeSelect($aryAttribute, $r, $selectValue);
    }

    public function makeSelectSc($aryAttribute, $selectValue = 0) {
        $r = $this->selectIdNameSc();
        return classHtml::makeSelect($aryAttribute, $r, $selectValue);
    }
}
/*
require_once '../boot.php';
//require_once '../classDb.php';
//require_once 'Util.class.php';
$a = new member();
$b = $a ->updateMember("6");
var_dump($b);
//var_dump($a->makeSelectSc());
// var_dump($a->checkPassword("admin","pass"));
//var_dump($a->getUsers("admin"));

?>