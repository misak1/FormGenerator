<?php
class mailtotype {
    public function __construct() {
    }

    public function getmailtotype() {
        $link = classDb::connect();
        $sql = "SELECT * FROM mf_tmailtotype ORDER BY mailtotype_id ASC;";
        $r = mysql_query($sql, $link);
        $a = array();
        while ($row = mysql_fetch_assoc($r)) {
            $a[] = $row;
        }
        classDb::close($link);
        return $a;
    }

    public function selectIdName() {
        $a = $this -> getmailtotype();
        $r = array();
        foreach ($a as $a2) {
            $r["{$a2["mailtotype_id"]}"] = $a2['note'];
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
?>