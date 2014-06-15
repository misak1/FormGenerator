<?php
//require_once ('boot.php');
//require_once ('classDb.php');

class classCategory {
    public function __construct() {
    }

    public function selectCategory() {
        $link = classDb::connect();
        $sql = "SELECT * FROM mf_tcategory WHERE effective_flg = 1 ORDER BY prioryty_no DESC, category_id ASC;";
        $r = mysql_query($sql, $link);
        $a = array();
        while ($row = mysql_fetch_assoc($r)) {
            $a[] = $row;
        }
        classDb::close($link);
        return $a;
    }

    public function selectIdName() {
        $a = $this -> selectCategory();
        $r = array();
        foreach ($a as $a2) {
            $r[] =  $a2["category_name"];
        }
        return $r;
    }

    public function selectIdNameSc() {
        $r = $this -> selectIdName();
        array_unshift($r, "選択して下さい。");
        return $r;
    }
    
    public function makeSelect($aryAttribute, $selectValue = 0) {
        $r = $this -> selectIdName();
        return classHtml::makeSelect($aryAttribute, $r, $selectValue);
    }

    public function selectNameFromID($id) {
        $a = $this -> selectCategory();
        foreach ($a as $a2) {
			if($a2["category_id"] === $id){
				return $a2["category_name"];
			}
        }
        return false;
    }
}
//$c = new classCategory();
//$v = $c -> selectCategory();
//$v = $c -> selectIdNameEx();
//var_dump($v);
?>