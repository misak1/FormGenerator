<?php
class classHtml {
    
    public static function makeSelect($aryAttribute, $aryKeyValue, $selectValue = 0) {
        $html = "";
        $name = "";
        $id = "";
        $class = "";
        if (!empty($aryAttribute)) {
            if (array_key_exists("name", $aryAttribute)) {
                $name = "name=\"{$aryAttribute["name"]}\"";
            }
            if (array_key_exists("id", $aryAttribute)) {
                $id = "id=\"{$aryAttribute["id"]}\"";
            }
            if (array_key_exists("class", $aryAttribute)) {
                $class = "class=\"{$aryAttribute["class"]}\"";
            }
            if (array_key_exists("other", $aryAttribute)) {
                $other = $aryAttribute["other"];
            }
        }

        $html .= "<select $name $id $class $other>" . PHP_EOL;
        foreach ($aryKeyValue as $k => $v) {
            $chk = "";
            if ($k == $selectValue) {
                $chk = " selected";
            }
            $html .= "<option value=\"$k\" $chk>$v</option>" . PHP_EOL;
        }
        $html .= "</select>" . PHP_EOL;
        return $html;
    }

}
?>