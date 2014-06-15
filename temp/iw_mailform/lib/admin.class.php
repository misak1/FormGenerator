<?php
require_once('../boot.php');
class admin {
    function __construct() {
    }

    public function checkPassword($login_id, $login_pw) {
        global $log;
        $isResult = false;
        $link = classDb::connect();
        $sql = sprintf("SELECT `login_pw` FROM `mf_tadmin` WHERE `login_id` = '%s';", mysql_real_escape_string($login_id));
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        while ($row = mysql_fetch_assoc($result)) {
            if ($login_pw === $row['login_pw']) {//パスワード確認
                $isResult = true;
                break;
            }
        }
        classDb::close($link);
        return $isResult;
    }

    public function getUsers($login_id) {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("SELECT `login_id`, `login_pw` FROM `mf_tadmin` WHERE `login_id`='%s';", mysql_real_escape_string($login_id));
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        $arrayUser = array();
        while ($row = mysql_fetch_assoc($result)) {
            $user = array();
            $user['login_id'] = $row['login_id'];
            $user['login_pw'] = $row['login_pw'];
            $arrayUser[] = $user;
        }
        classDb::close($link);
        return $arrayUser;
    }
}
//require_once '../boot.php';
//require_once '../classDb.php';
//require_once 'Util.class.php';
//$a = new admin();
// var_dump($a->checkPassword("admin","pass"));
//var_dump($a->getUsers("admin"));
?>