<?php
class classConf {
    static $conf_file = '/home/Imageworks/htdocs/iw_mailform/config.ini';

    /**
     * 設定ファイル読み込み
     */
    public static function load($ini_array, $process_sections = false) {
        $config_ini = parse_ini_file(self::conf_file, true);
    }

    /**
     * 設定ファイル書込み
     */
    public static function write($ini_array, $process_sections = false) {
        if (is_array($ini_array)) {
            $fp = fopen(self::conf_file, "w");
            foreach ($ini_array as $key => $val) {
                $buf = "";
                if (is_array($val)) {
                    if ($process_sections != false) {
                        $buf .= "[${key}]\n";
                    }
                    $buf .= self::parse_array($val);
                } else {
                    $buf .= "${key} = " . self::add_quote('"', $val) . "\n";
                }
                fwrite($fp, $buf);
            }
            fclose($fp);
        }
    }

    /**
     * クォート追加
     */
    public static function add_quote($quote_str, $val) {
        if (is_string($val)) {
            $val = "${quote_str}${val}${quote_str}";
        }
        return $val;
    }

    public static function parse_array($ini_array, $buf = "") {
        if (is_array($ini_array)) {
            foreach ($ini_array as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $key2 => $val2) {
                        $buf .= "${key}[${$key2}] = " . self::add_quote('"', $val2) . "\n";
                    }
                } else {
                    $buf .= "${key} = " . self::add_quote('"', $val) . "\n";
                }
            }
        }
        return $buf;
    }

}
?>
