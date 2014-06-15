<?php
require_once('../boot.php');
class groupmember {
    function __construct() {
    }

    public function getMembers($group_id) {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("SELECT `member_id` FROM `mf_tgroupmember` WHERE `group_id` = '%s';", mysql_real_escape_string($group_id));
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        $arrayUser = array();
        while ($row = mysql_fetch_assoc($result)) {
            $member = array();
            $member['member_id'] = $row['member_id'];
            $arrayUser[] = $member;
        }
        classDb::close($link);
        return $arrayUser;
    }
    
    public function getAddressesByGroupId($group_id) {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("SELECT `mail_address` FROM `mf_vgroupmember` WHERE `group_id` = '%s';", mysql_real_escape_string($group_id));
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        $arrayUser = array();
        while ($row = mysql_fetch_assoc($result)) {
            $arrayUser[]  = $row['mail_address'];
        }
        classDb::close($link);
        return $arrayUser;
    }
    
}
//require_once '../boot.php';
//require_once '../classDb.php';
//require_once 'Util.class.php';
//$a = new groupmember();
//var_dump($a->getAddressesByGroupId("1"));
//var_dump($a->getUsers("admin"));
?>