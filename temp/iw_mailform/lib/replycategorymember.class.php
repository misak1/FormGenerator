<?php
class replycategorymember {
    public function __construct() {
    }

    public function insertReplyCategoryMember($reply_id, $category_id, $member_idLs) {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("INSERT INTO `mf_treplycategorymember` (`replycategorymember_id`, `reply_id`, `category_id`, `member_id`, `add_date`, `upd_date`) VALUES (NULL, '%s', '%s', '%s', NULL, NULL);"
                , mysql_real_escape_string($reply_id)
                , mysql_real_escape_string($category_id)
                , implode(',',$member_idLs));
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        classDb::close($link);
        if (!$result) {
            return false;
        }
        return true;
    }
    public function selectReplyCategoryMember() {
        $link = classDb::connect();
        $sql = "SELECT * FROM mf_treplycategorymember ORDER BY replycategorymember_id ASC;";
        $r = mysql_query($sql, $link);
        $a = array();
        while ($row = mysql_fetch_assoc($r)) {
            $a[] = $row;
        }
        classDb::close($link);
        return $a;
    }
    public function getReplyCategoryMember() {
        $link = classDb::connect();
        $sql = "SELECT * FROM mf_treplycategorymember ORDER BY replycategorymember_id ASC;";
        $r = mysql_query($sql, $link);
        $a = array();
        while ($row = mysql_fetch_assoc($r)) {
            $a[] = $row;
        }
        classDb::close($link);
        return $a;
    }

    public function updateReplyCategoryMember($modify_replycategorymember_id, $reply_id, $category_id, $member_id) {
		global $log;
    	$link = classDb::connect();

    	$sql = sprintf("UPDATE `mf_treplycategorymember` SET `reply_id`= '%s' , `category_id`= '%s' , `member_id`= '%s' , `upd_date`= '%s' WHERE `replycategorymember_id`='%s';"
    		, mysql_real_escape_string($reply_id)
    		, mysql_real_escape_string($category_id)
    		, implode(',',$member_id)
    		, date('Y-m-d H:i:s')
    		, mysql_real_escape_string($modify_replycategorymember_id));
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

    public function deleteReplyCategoryMember($delete_replycategorymember_id) {
		global $log;
    	$link = classDb::connect();

    	$sql = sprintf("DELETE from `mf_treplycategorymember` WHERE `replycategorymember_id`='%s';"
    		, mysql_real_escape_string($delete_replycategorymember_id));
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

}
?>