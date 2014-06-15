<?php
/**
 * 入力値チェック
 */
class UtilValidate {

	/**
	 * checkdate()の(Y-m-d版)
	 * @param $year
	 * @param $month
	 * @param $day
	 */
	public static function checkdate_JP($year, $month, $day) {
		return checkdate($month, $day, $year);
	}
}
?>