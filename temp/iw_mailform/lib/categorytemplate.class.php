<?php
class categorytemplate {
    public function __construct() {
    }

    public function insertCategoryTemplate($category_id, $type, $template_id) {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("INSERT INTO `mf_tcategorytemplate` (`categorytemplate_id`, `category_id`, `type`, `template_id`, `add_date`, `upd_date`) VALUES (NULL, '%s', '%s', '%s', NULL, NULL);"
                , mysql_real_escape_string($category_id)
                , mysql_real_escape_string($type)
                , mysql_real_escape_string($template_id));
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        classDb::close($link);
        if (!$result) {
            return false;
        }
        return true;
    }
    public function selectCategoryTemplate() {
        $link = classDb::connect();
        $sql = "SELECT * FROM mf_tcategorytemplate ORDER BY categorytemplate_id ASC;";
        $r = mysql_query($sql, $link);
        $a = array();
        while ($row = mysql_fetch_assoc($r)) {
            $a[] = $row;
        }
        classDb::close($link);
        return $a;
    }
    public function getCategoryTemplate() {
        $link = classDb::connect();
        $sql = "SELECT * FROM mf_tcategorytemplate ORDER BY categorytemplate_id ASC;";
        $r = mysql_query($sql, $link);
        $a = array();
        while ($row = mysql_fetch_assoc($r)) {
            $a[] = $row;
        }
        classDb::close($link);
        return $a;
    }

    public function updateCategoryTemplate($modify_categorytemplate_id, $category_id, $type, $template_id) {
		global $log;
    	$link = classDb::connect();

    	$sql = sprintf("UPDATE `mf_tcategorytemplate` SET `category_id`= '%s' , `type`= '%s' , `template_id`= '%s' , `upd_date`= '%s' WHERE `categorytemplate_id`='%s';"
    		, mysql_real_escape_string($category_id)
    		, mysql_real_escape_string($type)
    		, mysql_real_escape_string($template_id)
    		, date('Y-m-d H:i:s')
    		, mysql_real_escape_string($modify_categorytemplate_id));
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

    public function deleteCategoryTemplate($delete_categorytemplate_id) {
		global $log;
    	$link = classDb::connect();

    	$sql = sprintf("DELETE from `mf_tcategorytemplate` WHERE `categorytemplate_id`='%s';"
    		, mysql_real_escape_string($delete_categorytemplate_id));
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