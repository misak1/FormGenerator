<?php
class classDb {
	public static function connect() {
		$link = mysql_connect(mf_db_server, mf_dbuser, mf_dbpasswd);
		mysql_set_charset("utf8", $link);
		mysql_select_db(mf_dbname, $link);
		return $link;
	}

	public static function close($link) {
		mysql_close($link);
	}

	public function langFilter($aryAndWhere = NULL) {
		if (!is_array($aryAndWhere)) {
			$aryAndWhere = array();
		}
		if ($_GET['lang'] === 'en') {
			$aryAndWhere['lang'] = 'en';
		} else {
			$aryAndWhere['lang'] = 'ja';
		}
		return $aryAndWhere;
	}

}
?>
