<?php
class group {
    public function __construct() {
    }

    public function selectGroup() {
        $link = classDb::connect();
        $sql = "SELECT * FROM mf_tgroup ORDER BY group_id ASC;";
        $r = mysql_query($sql, $link);
        $a = array();
        while ($row = mysql_fetch_assoc($r)) {
            $a[] = $row;
        }
        classDb::close($link);
        return $a;
    }
    
    public function insertGroup($group_name, $group_note) {
        global $log;
        $isResult = false;
        $link = classDb::connect();
        $sql = sprintf("INSERT INTO `mf_tgroup` (`group_id`, `group_name`, `group_note`, `add_date`, `upd_date`) VALUES (NULL, '%s', '%s', NULL, NULL);"
                , mysql_real_escape_string($group_name)
                , mysql_real_escape_string($group_note));
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        classDb::close($link);
        if (!$result) {
            return false;
        }
        return true;
    }

    public function nameById($group_id) {
        $link = classDb::connect();
        $sql=sprintf("SELECT group_name FROM mf_tgroup WHERE group_id = '%s';",
            mysql_real_escape_string($group_id)
        );
        $r = mysql_query($sql, $link);
        //$a = array();
        $a = "";
        //while ($row = mysql_fetch_assoc($r)) {
        $row = mysql_fetch_assoc($r);
            $a = $row['group_name'];
        //}
        classDb::close($link);
        return $a;
    }

    public function selectIdName() {
        $a = $this -> selectGroup();
        $r = array();
        foreach ($a as $a2) {
            $r["{$a2["group_id"]}"] = $a2['group_name'];
        }
        return $r;
    }

    public function selectIdNameSc() {
        $r = $this -> selectIdName();
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
require_once ('boot.php');
require_once ('classDb.php');
$c = new category();
//$v = $c -> selectGroup();
$v = $c -> selectIdNameSc();
print("<pre>");
var_dump($v);
print("</pre>");
*/
?>