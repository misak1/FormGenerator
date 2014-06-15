<?php
class UtilDate {
	// yyyy_mm_dd　形式
	public static $yyyy_mm_dd = 'Y_m_d';
	// (Thu, 29 Mar 2012 02:01:00 GMT) 形式
	public static $str_date = 'D M d H:i:s T Y';
	// yyyy-mm-dd hh:mm:ss　形式
	public static $yyyy_mm_dd_hhmmss = 'Y-m-d H:i:s';

	static $mon = '月';
	static $tue = '火';
	static $wed = '水';
	static $thu = '木';
	static $fri = '金';
	static $sat = '土';
	static $sun = '日';

	public static $aryMonthname = array(
					0 => '', 
					1 => 'Jan', 
					2 => 'Feb', 
					3 => 'Mar', 
					4 => 'Apr', 
					5 => 'May', 
					6 => 'Jun', 
					7 => 'Jul', 
					8 => 'Aug', 
					9 => 'Sep', 
					10 => 'Oct', 
					11 => 'Nov', 
					12 => 'Dec');

	function __construct() {
	}

	/*
	 public static function printDate() {

	 echo "現状　　　：Mon Jun 28 20:59:18 JST 2010 (実際はGMT)<br />";
	 echo "完成予想図：Tus Jun 29 05:59:18 JST 2010 (実際はJST)<br /><br />";

	 $deftime = "Mon Jun 28 20:59:18 JST 2010";
	 // $deftime = "Tue, 26 Jun 2012 01:05:00 GMT";
	 // $deftime = "Fri, 01 Jun 2012 01:11:00 GMT";

	 echo strtotime($deftime);
	 echo "<br />出力結果：";
	 echo date('D M d H:i:s T Y', strtotime($deftime) + 9 * 60 * 60);

	 }

	 public static function printDate2() {

	 echo "現状　　　：Mon Jun 28 20:59:18 JST 2010 (実際はGMT)<br />";
	 echo "完成予想図：Tus Jun 29 05:59:18 JST 2010 (実際はJST)<br /><br />";

	 $deftime = "Mon Jun 28 20:59:18 JST 2010";
	 // $deftime = "Tue, 26 Jun 2012 01:05:00 GMT";
	 // $deftime = "Fri, 01 Jun 2012 01:11:00 GMT";

	 echo strtotime($deftime);
	 echo "<br />出力結果：";
	 echo date('D M d H:i:s T Y', strtotime($deftime) + 9 * 60 * 60);
	 //         Mon Jun 28 20:59:18 JST 2010
	 //                 Thu Mar 29 11:06:00 JST 2012
	 }
	 */

	/**
	 * 日時テキストを日付型データで返却
	 */
	public static function strToDate($deftime, $format = 'Y-m-d H:i:s') {
		// JSTに変換する時
		//echo date('D M d H:i:s T Y', strtotime($deftime) + 9 * 60 * 60);
		//return date($format, strtotime($deftime) + 17 * 60 * 60);
		return date($format, strtotime($deftime));
	}

	/**
	 * 日時テキストを日付型データで返却(Twitterの時刻用)
	 */
	 public static function strToDateT($deftime, $format = 'Y-m-d H:i:s') {
	     // カリフォルニア時刻
		 return date($format, strtotime($deftime) + 17 * 60 * 60);
     }


	/**
	 * 日付から曜日（月～金）を取得
	 */
	public static function getDayOfWeek($date) {
		$dayOfWeek = date("w", strtotime($date));
		if ($dayOfWeek == '1')
			return self::$mon;
		if ($dayOfWeek == '2')
			return self::$tue;
		if ($dayOfWeek == '3')
			return self::$wed;
		if ($dayOfWeek == '4')
			return self::$thu;
		if ($dayOfWeek == '5')
			return self::$fri;
		if ($dayOfWeek == '6')
			return self::$sat;
		if ($dayOfWeek == '0')
			return self::$sun;
		return "";
	}

	/**
	 * 日付を取得
	 * 使い方  - UtilDate::getDate(UtilDate::$yyyy_mm_dd, 0);
	 */
	public static function getDate($format = 'Y_m_d', $add = 0) {
		if ($add > 0) {
			$add = '+' . $add;
		}
		return date($format, strtotime($add . ' day'));
	}

	/**
	 * 時間を取得
	 */
	public static function getTime($date) {
		$date2 = date("H:i", strtotime($date));
		return $date2;
	}

	/**
	 * 月の配列を作成
	 */
	public static function createYearAry($add = 3) {
		$current_year = UtilDate::getDate('Y');
		$aryYear = array();
		for ($i = 0; $i < $add; $i++) {
			$value = ($current_year - $i);
			$aryYear[$value] = $value;
		}
		return $aryYear;
	}

	/**
	 * 日の配列を作成
	 */
	public static function createDateAry() {
		$aryDay = array();
		for ($i = 1; $i <= 12; $i++) {
			$aryDay[$i] = sprintf("%02d", $i);
		}
		return $aryDay;
	}

	/**
	 * 月の配列を作成
	 */
	public static function createMonthAry() {
		$aryMonth = array();
		for ($i = 1; $i <= 31; $i++) {
			$aryMonth[$i] = sprintf("%02d", $i);
		}
		return $aryMonth;
	}

	/**
	 * 時の配列を作成
	 */
	public static function createHourAry() {
		$aryHour = array();
		for ($i = 0; $i <= 23; $i++) {
			$aryHour[$i] = sprintf("%02d", $i);
		}
		return $aryHour;
	}

	/**
	 * 分の配列を作成
	 */
	public static function createMinutesAry() {
		$aryMinutes = array();
		for ($i = 0; $i <= 59; $i++) {
			$aryMinutes[$i] = sprintf("%02d", $i);
		}
		return $aryMinutes;
	}

	/**
	 * 比較
	 * str1 が str2 より 大きい ときは 1
	 * str1 と str2 が 等しい ときは 0
	 * str1 が str2 より 小さい ときは -1
	 *
	 * @param $date1 'YYYY-mm-dd'
	 * @param $date2 'YYYY-mm-dd'
	 */
	public static function compare($date1, $date2) {
		$i = 0;
		if (strtotime($date1) > strtotime($date2)) {
			$i = -1;
		} else if (strtotime($date1) < strtotime($date2)) {
			$i = 1;
		} else {
			//if(strtotime(date('Y-m-d')) == strtotime('2012-07-11')){
		}

		return $i;

	}

	/**
	 * （グレゴリウス暦 - 省略形）から月を返す。
	 */
	public static function monthnameToMonth($strMonthName) {
		return array_search($strMonthName, self::$aryMonthname);
	}

	/**
	 * "Mon, 16 Jul 2012 19:04:53" から　2012-07-16 19:04:53に変換
	 */
	public static function strToTimestamp($pdate) {
		list($wd, $d, $mStr, $y, $h, $i, $s) = sscanf($pdate, "%s %d %s %d %d:%d:%d");
		$m = self::monthnameToMonth($mStr);
		$res = ($y . "-" . sprintf("%02d", $m) . "-" . sprintf("%02d", $d) . " " . sprintf("%02d", $h) . ":" . sprintf("%02d", $i) . ":" . sprintf("%02d", $s));
		return $res;
	}

}
?>
