<?php
require_once('../boot.php');
class template {
    function __construct() {
    }

    public function insertTemplate($mail_subject, $mail_body) {
        global $log;
        $isResult = false;
        $link = classDb::connect();
        $sql = sprintf("INSERT INTO `mf_ttemplate` (`template_id`, `mail_subject`, `mail_body`, `effective_flg`,`add_date`, `upd_date`) VALUES (NULL, '%s', '%s', 1, NULL, NULL);"
                , mysql_real_escape_string($mail_subject)
                , mysql_real_escape_string($mail_body));
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        classDb::close($link);
        if (!$result) {
            return false;
        }
        return true;
    }

    public function updateTemplate($modify_template_id, $mail_subject, $mail_body, $effective_flg) {
		global $log;
    	$link = classDb::connect();

    	$sql = sprintf("UPDATE `mf_ttemplate` SET `mail_subject`= '%s' , `mail_body`= '%s' , `effective_flg`= '%s' , `upd_date`= '%s' WHERE `template_id`='%s';"
    		, mysql_real_escape_string($mail_subject)
    		, mysql_real_escape_string($mail_body)
    		, mysql_real_escape_string($effective_flg)
    		, date('Y-m-d H:i:s')
    		, mysql_real_escape_string($modify_template_id));
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

    public function deleteTemplate($delete_template_id) {
		global $log;
    	$link = classDb::connect();

    	$sql = sprintf("UPDATE `mf_ttemplate` SET `effective_flg`= 0 ,`upd_date`= '%s' WHERE `template_id`='%s';"
    		, date('Y-m-d H:i:s')
    		, mysql_real_escape_string($delete_template_id));
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

    public function getTemplate() {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("SELECT `template_id`, `mail_subject`, `mail_body`, `effective_flg` FROM `mf_ttemplate`;");
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        $arrayUser = array();
        while ($row = mysql_fetch_assoc($result)) {
            $maillog = array();
            $maillog['template_id']   = $row['template_id'];
            $maillog['mail_subject']  = $row['mail_subject'];
            $maillog['mail_body']     = $row['mail_body'];
            $maillog['effective_flg'] = $row['effective_flg'];
            $arrayUser[] = $maillog;
        }
        classDb::close($link);
        return $arrayUser;
    }

    public function getActiveTemplate() {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("SELECT `template_id`, `mail_subject`, `mail_body`, `effective_flg` FROM `mf_ttemplate` WHERE effective_flg = 1;");
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        $arrayUser = array();
        while ($row = mysql_fetch_assoc($result)) {
            $maillog = array();
            $maillog['template_id']   = $row['template_id'];
            $maillog['mail_subject']  = $row['mail_subject'];
            $maillog['mail_body']     = $row['mail_body'];
            $arrayUser[] = $maillog;
        }
        classDb::close($link);
        return $arrayUser;
    }


    public function selectIdName() {
        $a = $this -> getTemplate();
        $r = array();
        foreach ($a as $a2) {
            $r["{$a2["template_id"]}"] = $a2['mail_subject'];
        }
        return $r;
    }

    public function selectActiveIdName() {
        $a = $this -> getActiveTemplate();
        $r = array();
        foreach ($a as $a2) {
            $r["{$a2["template_id"]}"] = $a2['mail_subject'];
        }
        return $r;
    }

    public function selectIdNameSc() {
        $r = $this -> selectActiveIdName();
        $r["0"] = "選択して下さい。";
        ksort($r);
        return $r;
    }

    public function makeSelect($aryAttribute, $selectValue = 0) {
        $r = $this -> selectIdName();
        return classHtml::makeSelect($aryAttribute, $r, $selectValue);
    }
    
    public function makeSelectSc($aryAttribute, $selectValue = 0) {
        $r = $this -> selectIdNameSc();
        return classHtml::makeSelect($aryAttribute, $r, $selectValue);
    }

}
/*
$m = new template();
$from = "shibata@imagica-imageworks.co.jp";
$to = "shibata@imagica-imageworks.co.jp";
$subject= "shibata@imagica-imageworks.co.jp";
$header= "shibata@imagica-imageworks.co.jp";
$body= "shibata@imagica-imageworks.co.jp";
//$r = $m->insertTemplate($from, $to);
$r = $m->getTemplate();
var_dump($r);
*/
?>