<?php
class category {
	public function __construct() {
	}

	public function insertCategory($prioryty_no, $category_name, $contents_id) {
		global $log;
		$isResult = false;
		$link = classDb::connect();
		$sql = sprintf("INSERT INTO `mf_tcategory` (`category_id`, `prioryty_no`, `category_name`, `effective_flg`, `contents_id`, `add_date`, `upd_date`) VALUES (NULL, '%s', '%s', 1, '%s', NULL, NULL);", mysql_real_escape_string($prioryty_no), mysql_real_escape_string($category_name), mysql_real_escape_string($contents_id));
		$sql = Util::deleteCRLF($sql);
		$log -> info($sql);
		$result = mysql_query($sql, $link);
		classDb::close($link);
		if (!$result) {
			return false;
		}
		return true;
	}

	/**
	 * カテゴリ取得
	 *   条件１：有効なもののみ'effective_flg = 1'
	 *   条件２：(言語フィルター（デフォルト）有効)
	 * 使用箇所：index(フロント)、edit4、、、、
	 * @param $aryWhere = array("column_name" => "value", "column_name" => "value");
	 */
	public function selectCategory($aryAndWhere = NULL, $langfilter = TRUE) {
		$link = classDb::connect();
		if ($langfilter || is_null($langfilter)) {
			$aryAndWhere = classDb::langFilter($aryAndWhere);
		}

		//$sql = "SELECT * FROM mf_tcategory WHERE effective_flg = 1 ORDER BY prioryty_no DESC, category_id ASC;";
		$sql = 'SELECT t1.category_id, t1.prioryty_no, t1.category_name, t1.effective_flg, t1.contents_id, mf_tlang.lang, mf_tlang.language FROM ( ';
		$sql .= '	SELECT mf_tcategory.*, mf_tcontents.lang_id  FROM mf_tcategory ';
		$sql .= '	LEFT JOIN mf_tcontents ';
		$sql .= '	ON mf_tcategory.contents_id = mf_tcontents.contents_id ';
		$sql .= '	) AS t1 LEFT JOIN mf_tlang ';
		$sql .= 'ON t1.lang_id = mf_tlang.lang_id ';
		$sql .= 'WHERE 1=1 ';
		$sql .= 'AND effective_flg = 1 ';

		// WEHRE句内のANDを動的生成
		if (is_array($aryAndWhere)) {
			$keyVal = array();
			foreach ($aryAndWhere as $key => $value) {
				$keyVal[] = "$key = '$value'";
			}
			$sql .= ' AND ' . implode(' AND ', $keyVal);
		}
		$sql .= ' ORDER BY contents_id ASC, prioryty_no DESC, effective_flg DESC,category_id ASC ';
		$sql .= ';';
		//echo "sql1:".$sql;

		$r = mysql_query($sql, $link);
		$a = array();
		while ($row = mysql_fetch_assoc($r)) {
			$a[] = $row;
		}
		classDb::close($link);
		return $a;
	}

	/**
	 * カテゴリ取得
	 *   条件１：(言語フィルター（デフォルト）無効)
	 * 使用箇所：edit7、edit9、、、
	 * @param $aryWhere = array("column_name" => "value", "column_name" => "value");
	 */
	public function getCategory($aryAndWhere = NULL, $langfilter = FALSE) {
		$link = classDb::connect();

		if ($langfilter || is_null($langfilter)) {
			$aryAndWhere = classDb::langFilter($aryAndWhere);
		}

		//$sql = "SELECT * FROM mf_tcategory ORDER BY prioryty_no DESC, category_id ASC;";
		$sql = 'SELECT t1.category_id, t1.prioryty_no, t1.category_name, t1.effective_flg, t1.contents_id, mf_tlang.lang, mf_tlang.language FROM ( ';
		$sql .= '	SELECT mf_tcategory.*, mf_tcontents.lang_id  FROM mf_tcategory ';
		$sql .= '	LEFT JOIN mf_tcontents ';
		$sql .= '	ON mf_tcategory.contents_id = mf_tcontents.contents_id ';
		$sql .= '	) AS t1 LEFT JOIN mf_tlang ';
		$sql .= 'ON t1.lang_id = mf_tlang.lang_id ';
		$sql .= 'WHERE 1=1 ';

		// WEHRE句内のANDを動的生成
		if (is_array($aryAndWhere)) {
			$keyVal = array();
			foreach ($aryAndWhere as $key => $value) {
				$keyVal[] = "$key = '$value'";
			}
			$sql .= ' AND ' . implode(' AND ', $keyVal);
		}
		$sql .= ' ORDER BY contents_id ASC, prioryty_no DESC, effective_flg DESC,category_id ASC ';
		$sql .= ';';
		//echo "sql2:".$sql;

		$r = mysql_query($sql, $link);
		$a = array();
		while ($row = mysql_fetch_assoc($r)) {
			$a[] = $row;
		}
		classDb::close($link);
		return $a;
	}

	public function updateCategory($modify_category_id, $prioryty_no, $category_name, $effective_flag, $contents_id) {
		global $log;
		$link = classDb::connect();

		$sql = sprintf("UPDATE `mf_tcategory` SET `prioryty_no`= '%s' , `category_name`= '%s' , `effective_flg`= '%s', `contents_id`= '%s', upd_date = '%s'  WHERE `category_id`='%s';", mysql_real_escape_string($prioryty_no), mysql_real_escape_string($category_name), mysql_real_escape_string($effective_flag), mysql_real_escape_string($contents_id), date('Y-m-d H:i:s'), mysql_real_escape_string($modify_category_id));
		$sql = Util::deleteCRLF($sql);
		writeLog($sql);
		$log -> info($sql);
		$result = mysql_query($sql, $link);
		classDb::close($link);
		if (!$result) {
			return false;
		}
		return true;
	}

	public function deleteCategory($modify_category_id) {
		global $log;
		$link = classDb::connect();

		//    		$sql = sprintf("UPDATE `mf_tcategory` SET `effective_flg`= 0,upd_date = '%s'  WHERE `category_id`='%s';"
		//    		, date('Y-m-d H:i:s')
		//    		, mysql_real_escape_string($modify_category_id));
		$sql = sprintf("DELETE FROM `mf_tcategory` WHERE `category_id`='%s';", mysql_real_escape_string($modify_category_id));
		$sql = Util::deleteCRLF($sql);
		writeLog($sql);
		$log -> info($sql);
		$result = mysql_query($sql, $link);
		classDb::close($link);
		if (!$result) {
			return false;
		}
		return true;
	}

	public function nameById($category_id) {
		$link = classDb::connect();
		$sql = sprintf("SELECT category_name FROM mf_tcategory WHERE category_id = '%s';", mysql_real_escape_string($category_id));
		$r = mysql_query($sql, $link);
		//$a = array();
		$a = "";
		//while ($row = mysql_fetch_assoc($r)) {
		$row = mysql_fetch_assoc($r);
		$a = $row['category_name'];
		//}
		classDb::close($link);
		return $a;
	}

	public function selectIdName($langfilter = NULL) {
		$a = $this -> selectCategory(NULL, $langfilter);
		$r = array();
		foreach ($a as $a2) {
			$r["{$a2["category_id"]}"] = $a2['category_name'];
		}
		return $r;
	}

	public function selectIdNameSc($langfilter = NULL) {
		$r = $this -> selectIdName($langfilter);
		$r["0"] = "選択して下さい。";
		ksort($r);
		return $r;
	}

	/**
	 * 「選択して下さい」なし
	 */
	public function makeSelect($aryAttribute, $selectValue = 0, $langfilter = NULL) {
		$r = $this -> selectIdName($langfilter);
		return classHtml::makeSelect($aryAttribute, $r, $selectValue);
	}

	/**
	 * 「選択して下さい」あり
	 */
	public function makeSelectSc($aryAttribute, $selectValue = 0, $langfilter = NULL) {
		$r = $this -> selectIdNameSc($langfilter);
		return classHtml::makeSelect($aryAttribute, $r, $selectValue);
	}

	/**
	 * コンテンツID＋言語設定取得
	 *   条件１：有効なもののみ'effective_flg = 1'
	 * 使用箇所：edit7、、、、
	 * @param $aryWhere = array("column_name" => "value", "column_name" => "value");
	 */
	public function selectLang($aryAndWhere = NULL) {
		$link = classDb::connect();

		//$sql = "SELECT * FROM mf_tcategory WHERE effective_flg = 1 ORDER BY prioryty_no DESC, category_id ASC;";
		$sql = ' SELECT mf_tcontents.contents_id, mf_tcontents.prioryty_no, mf_tcontents.lang_id,  mf_tlang.lang, mf_tlang.language FROM mf_tcontents ';
		$sql .= ' INNER JOIN mf_tlang ';
		$sql .= ' ON mf_tcontents.lang_id = mf_tlang.lang_id ';
		$sql .= 'WHERE 1=1 ';
		$sql .= 'AND mf_tcontents.effective_flg = 1 ';

		// WEHRE句内のANDを動的生成
		if (is_array($aryAndWhere)) {
			$keyVal = array();
			foreach ($aryAndWhere as $key => $value) {
				$keyVal[] = "$key = '$value'";
			}
			$sql .= ' AND ' . implode(' AND ', $keyVal);
		}
		$sql .= ' ORDER BY mf_tcontents.prioryty_no DESC ';
		$sql .= ';';
		//echo "sql1:".$sql;

		$r = mysql_query($sql, $link);
		$a = array();
		while ($row = mysql_fetch_assoc($r)) {
			$a[] = $row;
		}
		classDb::close($link);
		return $a;
	}
	
}

/*
 require_once ('boot.php');
 require_once ('classDb.php');
 $c = new category();
 //$v = $c -> selectCategory();
 $v = $c -> selectIdNameSc();
 print("<pre>");
 var_dump($v);
 print("</pre>");
 */
?>