<?php

class Util {
	//文字コード変換
	public static function ToUTF8fromSJIS($data) {
		$data = mb_convert_encoding($data, "UTF-8", "sjis-win");
		return $data;
	}

	//文字コード変換
	public static function ToUTF8fromEUC($data) {
		$data = mb_convert_encoding($data, "UTF-8", "EUC");
		return $data;
	}

	//文字コード変換
	public static function ToSJISfromUTF8($data) {
		$data = mb_convert_encoding($data, "sjis-win", "UTF-8");
		return $data;
	}

	public static function ToSJISfromUTF8forArray($array) {
		$data = mb_convert_variables('UTF-8', 'sjis-win', $array);
		return $data;
	}

	//文字コード変換
	public static function ToSJISfromEUC($data) {
		$data = mb_convert_encoding($data, "sjis-win", "EUC");
		return $data;
	}

	// URLチェック
	public static function url_exists($url) {
		$header = @get_headers($url);
		print("header:" . $header . "<br/>");
		//print_r($header);
		// if (preg_match('#^HTTP/.*\s+[200|302]+\s#i', $header[0])) {
		if (preg_match('#^HTTP/.*\s+[200]+\s#i', $header[0])) {
			return true;
		}
		return false;
	}

	//必須チェック
	public static function nullCheck($data) {
		$data = str_replace(" ", "", $data);
		$data = str_replace("　", "", $data);
		$data = str_replace("\r", "", $data);
		$data = str_replace("\n", "", $data);
		if (!$data) {
			$return = "error";
		}
		return "$return";
	}

	//チェックボックスチェック
	public static function checkboxCheck($type, $array, &$dataHs, &$_SESSION, $othertext, $prefix) {
		global $_POST;
		$check_flag = '';
		$valueArrayLs = array();
		foreach ($array as $key => $value) {
			$temp0 = $prefix . "_" . $type;
			$temp = $prefix . "_" . $type . $key;
			$temp2 = $prefix . "_" . $type . $key . "_checked";
			$temp3 = $prefix . "_" . $type . $key . "_value";
			$temp4 = $othertext . $key;
			if ($_POST[$temp] == $value) {
				$check_flag = '1';
				$dataHs[$temp2] = " checked";
				$_SESSION[$temp] = $value;
				if ($key >= 91 && $key <= 99 && $_POST[$temp4]) {
					$_SESSION[$temp4] = htmlspecialchars($_POST[$temp4], ENT_QUOTES);
					$dataHs[$temp4] = $_SESSION[$temp4];
					$valueArrayLs[] = $value . "(" . $_SESSION[$temp4] . ")";
				} else {
					$valueArrayLs[] = $value;
				}
			}
			$dataHs[$temp3] = $value;
		}
		if ($check_flag == '') {
			return "null";
		} else {
			$dataHs[$temp0] = implode("<br />", $valueArrayLs);
			$_SESSION[$temp0] = implode("\n", $valueArrayLs);
			return "";
		}
	}

	//ホームページ掲載チェックボックスチェック
	public static function hpcheckboxCheck($name, &$dataHs, &$_SESSION, $type = "confirm") {
		global $_POST;
		$temp1 = $name . "_checked";
		$temp2 = "view_" . $name;
		if ($type == "confirm") {
			if ($_POST[$name] == 1) {
				$_SESSION[$name] = 1;
				$dataHs[$temp1] = " checked";
				$dataHs[$temp2] = "（ホームページ掲載可）";
			} else {
				$_SESSION[$name] = "";
				$dataHs[$temp1] = "";
				$dataHs[$temp2] = "（ホームページ掲載不可）";
			}
		} else {
			if ($_SESSION[$name] == 1) {
				$dataHs[$temp1] = " checked";
			} else {
				$dataHs[$temp1] = "";
			}
		}
	}

	//メール用ホームページ掲載チェック変換
	public static function gethpcheckboxvalue($data) {
		if ($data == 1) {
			$return = "（ホームページ掲載可）";
		} else {
			$return = "（ホームページ掲載不可）";
		}
		return $return;
	}

	//ラジオボタンチェック
	public static function radioCheck($type, $array, &$dataHs, &$_SESSION, $othertext, $prefix) {
		global $_POST;
		$check_flag = '';
		$temp = $prefix . "_" . $type;
		$dataHs[$temp] = '';
		foreach ($array as $key => $value) {
			$temp2 = $prefix . "_" . $type . $key . "_checked";
			$temp3 = $prefix . "_" . $type . $key . "_value";
			$temp4 = $othertext . $key;
			if ($key >= 91 && $key <= 99 && $_POST[$temp4]) {
				$check_flag = '1';
				$_SESSION[$temp4] = htmlspecialchars($_POST[$temp4], ENT_QUOTES);
				$dataHs[$temp4] = $_SESSION[$temp4];
			}
			if ($_POST[$temp] == $value) {
				$check_flag = '1';
				$dataHs[$temp2] = " checked";
				if ($key >= 91 && $key <= 99 && $_POST[$temp4]) {
					$dataHs[$temp] .= $value . '(' . $dataHs[$temp4] . ')';
				} else {
					if ($dataHs[$temp]) {
						$dataHs[$temp] .= '<br />';
					}
					$dataHs[$temp] .= $value;
				}
			} else if ($_POST[$temp4]) {
				if ($dataHs[$temp]) {
					$dataHs[$temp] .= '<br />';
				}
				$dataHs[$temp] .= '(' . $dataHs[$temp4] . ')';
			}
			$_SESSION[$temp] = $dataHs[$temp];
			$dataHs[$temp3] = $value;
			$_SESSION[$temp3] = $dataHs[$temp3];
		}
		if ($check_flag == '') {
			return "null";
		} else {
			return "";
		}
	}

	//数字のみチェック
	public static function numberCheck($data) {
		if (!eregi("^[0-9]{1,}$", $data)) {
			$return = "error";
		} else {
			$return = "$data";
		}
		return "$return";
	}

	//英字のみチェック
	public static function alphaCheck($data) {
		if (!eregi("^[a-z]{1,}$", $data)) {
			$return = "error";
		} else {
			$return = "$data";
		}
		return "$return";
	}

	//英字+「_」のみチェック
	public static function alphaunderbarCheck($data) {
		if (!eregi("^[a-z_]{1,}$", $data)) {
			$return = "error";
		} else {
			$return = "$data";
		}
		return "$return";
	}

	//英数のみチェック
	public static function numberAlphaCheck($data) {
		if (!eregi("^[0-9a-zA-Z]{1,}$", $data)) {
			$return = "error";
		} else {
			$return = "$data";
		}
		return "$return";
	}

	//電話チェック
	public static function numberhyphenCheck($data) {
		if (!eregi("^[0-9\-]{1,}$", $data)) {
			$return = "error";
		} else {
			$return = "$data";
		}
		return "$return";
	}

	//国際電話番号チェック
	public static function internationaltelCheck($data) {
		if (!eregi("^[0-9+()\*\#]{1,}$", $data)) {
			$return = "error";
		} else {
			$return = "$data";
		}
		return "$return";
	}

	//ヒラガナのみチェック
	public static function hiraganaCheck($data) {
		mb_regex_encoding("UTF-8");
		$check_data = str_replace("　", " ", $data);
		$check_data = str_replace(" ", "", $check_data);
		if (mb_ereg("^[ぁ-ん]+$", $check_data)) {
			$return = "$data";
		} else {
			$return = "error";
		}
		return "$return";
	}

	//全角カタカナのみチェック
	public static function katakanaCheck($data) {
		mb_regex_encoding("UTF-8");
		if (mb_ereg("^[ァ-ヶー]+$", $data)) {
			$return = "$data";
		} else {
			$return = "error";
		}
		return "$return";
	}

	//パスワードチェック
	public static function passCheck($data) {
		if (!eregi("^[0-9a-zA-Z_\-]{1,}$", $data)) {
			$return = "error";
		} else {
			$return = "$data";
		}
		return "$return";
	}

	//日付チェック
	public static function dateFormatCheck($data) {
		$data = str_replace("-", "/", $data);
		if (!eregi("^([0-9]{4})+\/+([0-9]{2})+\/+([0-9]{2})$", $data)) {
			$return = "error";
		} else {
			$return = "$data";
		}
		return "$return";
	}

	//文字数チェック
	public static function strlenCheck($data, $min = 0, $max = 0) {
		if ($min != 0 and mb_strlen($data, "UTF-8") < $min) {
			$return = "error";
		} else if ($max != 0 and mb_strlen($data, "UTF-8") > $max) {
			$return = "error";
		} else {
			$return = "$data";
		}
		return "$return";
	}

	//Eメール@.チェック
	public static function baseemailCheck($email) {
		//	$email = mb_convert_kana($email,'a');		//全角英数字を半角英数字に変換
		//大文字小文字を区別せずに正規表現によるマッチング
		if (!eregi("^(.+)+@+(.+)+\\.+[a-z]{2,4}$", $email)) {
			$return = "error";
		} else {
			$return = "$email";
		}
		return "$return";
	}

	//Eメールチェック
	public static function emailCheck($email) {
		//	$email = mb_convert_kana($email,'a');		//全角英数字を半角英数字に変換
		//大文字小文字を区別せずに正規表現によるマッチング
		if (!eregi("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $email)) {
			$return = "error";
		} else {
			$return = "$email";
		}
		return "$return";
	}

	//文字長さチェック
	public static function strlengthCheck($data, $type, $checklength) {
		if (!$data) {
			return "";
		}
		$length = mb_strlen($data, "UTF-8");
		switch($type) {
			case "min" :
				if ($checklength > $length) {
					$return = "error";
				}
				break;
			case "max" :
				if ($checklength < $length) {
					$return = "error";
				}
				break;
		}
		return "$return";
	}

	//日時整形
	public static function getmaildate($date) {
		$return = substr($date, 0, 4) . "年" . substr($date, 4, 2) . "月" . substr($date, 6, 2) . "日 " . substr($date, 8, 2) . "時" . substr($date, 10, 2) . "分";
		return $return;
	}

	//管理者用日付整形
	public static function getAdminDate($date) {
		$return = substr($date, 0, 4) . "年" . substr($date, 5, 2) . "月" . substr($date, 8, 2) . "日";
		return $return;
	}

	//日付整形
	public static function getviewDate($date) {
		$return = substr($date, 0, 4) . "年" . substr($date, 4, 2) . "月" . substr($date, 6, 2) . "日";
		return $return;
	}

	//検索条件選択済み表示処理
	public static function getsearchdata($_SESSION) {
		foreach ($_SESSION as $key => $val) {
			if (substr($key, 0, 7) != "search_" or $val == '') {
				if ($key == "map") {
					if ($val == 1) {
						$dataHs[$key] = " checked";
					} else {
						$dataHs[$key] = "";
					}
					$session_map = "1";
				} else {
					continue;
				}
			} else {
				$len = intval(strlen($key)) - 7;
				$searchkeyname = substr($key, 7, $len);
				if ($searchkeyname == "freeword") {
					$dataHs[$key] = htmlspecialchars($val, ENT_QUOTES);
				} else {
					if (gettype($val) == "array") {
						switch($key) {
							case "search_roque01" :
								if (in_array("東京23区", $val)) {
									$dataHs["search_roque01_1"] = " checked";
								}
								if (in_array("多摩地区", $val)) {
									$dataHs["search_roque01_2"] = " checked";
								}
								if (in_array("島しょ", $val)) {
									$dataHs["search_roque01_3"] = " checked";
								}
								break;
							case "search_roque16" :
								if (in_array("2週間前", $val)) {
									$dataHs["search_roque16_1"] = " checked";
								}
								if (in_array("2週間前", $val)) {
									$dataHs["search_roque16_2"] = " checked";
								}
								if (in_array("1週間以内も可", $val)) {
									$dataHs["search_roque16_3"] = " checked";
								}
								break;
							case "search_roque14" :
								if (in_array("有料", $val)) {
									$dataHs["search_roque14_1"] = " checked";
								}
								if (in_array("無料", $val)) {
									$dataHs["search_roque14_2"] = " checked";
								}
								break;
							case "search_roque25" :
								foreach ($val as $value) {
									$temp = $key . "_" . $value;
									$dataHs[$temp] = " checked";
								}
								break;
						}
					} else {
						if ($val == "") {
							$dataHs[$key] = "";
						} else {
							$dataHs[$key] = " checked";
						}
					}
				}
			}
		}
		if ($session_map != 1) {
			$dataHs["map"] = " checked";
		}
		return $dataHs;
	}

	//CSVダウンロード生成
	public static function csvdownload($data, $name) {

		$filename = "/tmp/" . $name . ".csv";
		$fp = fopen($filename, "w");
		flock($fp, 2);
		while (list($key, $value) = @each($data)) {
			fwrite($fp, $value);
			fwrite($fp, "\r\n");
		}
		flock($fp, 3);
		fclose($fp);

		$filesize = filesize($filename);
		$content_type = "application/octet-stream";

		header("Cache-Control: public");
		header("Pragma: public");
		header("Accept-Ranges: none");
		header("Content-Length: $filesize");
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment; filename=" . $name . '.csv');
		$fp = fopen($filename, "rb") or die('CSVファイルのダウンロードに失敗しました。');
		$res = fpassthru($fp);
		exit ;
	}

	//CSVダウンロード生成
	public static function filedownload($filpath) {
		$filesize = filesize($filpath);
		$filename = basename($filpath);
		$content_type = "application/octet-stream";

		header("Cache-Control: public");
		header("Pragma: public");
		header("Accept-Ranges: none");
		header("Content-Length: $filesize");
		header("Content-Type: $content_type");
		header("Content-Disposition: attachment; filename=" . $filename);
		$fp = fopen($filpath, "rb") or die('CSVファイルのダウンロードに失敗しました。');
		$res = fpassthru($fp);
		exit ;
	}

	//添付ファイルダウンロード生成
	public static function tmpdownload($dataHs) {
		$filesize = filesize(_filepathDir_ . $dataHs["saver"]);
		header("Cache-Control: public");
		header("Pragma: public");
		header("Accept-Ranges: none");
		header("Content-Length: " . $filesize);
		header("Content-Type: " . $dataHs["saver"]);
		header("Content-Disposition: attachment; filename=" . ToSJISfromUTF8($dataHs["name"]));
		$fp = fopen(_filepathDir_ . $dataHs["saver"], "rb") or die('添付ファイルのダウンロードに失敗しました。');
		$res = fpassthru($fp);
		exit ;
	}

	/**
	 * 改行コード削除
	 */
	public static function deleteCRLF($str) {
		return str_replace(array("\r\n", "\r", "\n"), '', $str);
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

	/**
	 * parse_ini_fileの書込み
	 */
	public static function write_ini_file($filename, $ini_array, $process_sections = false) {

		if (is_array($ini_array)) {
			$fp = fopen($filename, "w");
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

	//-------------------------------------------------------------------------
	// array parse_http_date( string Date )
	// DateはRFC1123、RFC 850、ANSI C's asctime() formatのいずれか。
	//-------------------------------------------------------------------------
	public static function parse_http_date($string_date) {

		// 月の名前と数字を定義
		$define_month = array("01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");

		if (preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), ([0-3][0-9]) (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) ([0-9]{4}) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9]) GMT$/", $string_date, $temp_date)) {

			$date["hour"] = $temp_date[5];
			$date["minute"] = $temp_date[6];
			$date["second"] = $temp_date[7];
			// 定義済みの月の名前を数字に変換する
			$date["month"] = array_search($temp_date[3], $define_month);
			$date["day"] = $temp_date[2];
			$date["year"] = $temp_date[4];

		} elseif (preg_match("/^(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday), ([0-3][0-9])-(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)-([0-9]{2}) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9]) GMT$/", $string_date, $temp_date)) {

			$date["hour"] = $temp_date[5];
			$date["minute"] = $temp_date[6];
			$date["second"] = $temp_date[7];
			// 定義済みの月の名前を数字に変換する
			$date["month"] = array_search($temp_date[3], $define_month);
			// 年が2桁しかないので1900を足して4桁に
			$date["day"] = $temp_date[2];
			$date["year"] = 1900 + $temp_date[4];

		} elseif (preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun) (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) ([0-3 ][0-9]) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9]) ([0-9]{4})$/", $string_date, $temp_date)) {
			$date["hour"] = $temp_date[4];
			$date["minute"] = $temp_date[5];
			$date["second"] = $temp_date[6];
			$date["month"] = array_search($temp_date[2], $define_month);
			// 日が1桁の場合先、半角スペースを0に置換
			$date["day"] = str_replace(" ", 0, $temp_date[3]);
			// 定義済みの月の名前を数字に変換する
			$date["year"] = $temp_date[7];

		} else {
			return FALSE;
		}

		// UNIXタイムスタンプを生成GMTなのに注意
		$date["timestamp"] = gmmktime($date["hour"], $date["minute"], $date["second"], $date["month"], $date["day"], $date["year"]);

		return $date;

	}

	public static function urlAutoLink($ret) {
	    // POSIXキャラクタクラス
        $ret = mb_ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\">\\0</a>", $ret);
		return ($ret);
	}
	
	public static function twitterify($ret) {
       $ret =  self::urlAutoLink($ret);
       /*
       $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
       $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret); // wwwリンク
	    $ret = preg_replace("/@(\w+)/", "<a href=\"http://twitter.com/intent/user?screen_name=\\1\" target=\"_blank\">@\\1</a>", $ret); // プロフィール
	    $ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=%23\\1\" target=\"_blank\">#\\1</a>", $ret); // ハッシュタグ
	    */

       $ret = preg_replace('/\@([a-zA-Z0-9_]+)/', '<a href="http://twitter.com/\1">@\1</a>', $ret);
       $ret = preg_replace('/\#([a-zA-Z0-9_]+)/', '<a href="http://twitter.com/search/#\1">#\1</a>', $ret);
       
       $ret =  self::urlAutoLinkShort($ret);
	    return ($ret);
	}
	
/**
 * 短縮URLチェック、構成文字＋スラッシュを最低一つ以上含んでいること
 * @param unknown_type $text
 */
	public static function is_urlChar($text) {
	    if (preg_match('/^([-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $text)) {
	        if(strpos($text, "/")){
	        return TRUE;
	        }else{
	            return FALSE;
	        }
	    } else {
	        return FALSE;
	    }
	}
	
	/**
	 * URLの構成文字列だけの場合は短縮URLとみなす
	 * @param unknown_type $ret
	 * @return unknown
	 */
	public static function urlAutoLinkShort($ret) {
	    $pattern0 = " ";
	    $matches = explode($pattern0, $ret); // スペースで分割
	     
	    $resultA = array();
	    foreach ($matches as $match) {
	        $match = trim($match);
	       if(self::is_urlChar($match)){
	           $resultA[] = "<a href=\"http://{$match}\">{$match}</a>";
	       }else{
	           $resultA[] = $match;
	       }
	    }
	    $ret = implode(" ", $resultA);
	    return ($ret);
	}
	
	/**
	 * 短縮URLからサムネイル
	 * @param unknown_type $status_text
	 */
	public static function getThumbnailHtml($status_text) {
	    $html = '';
	    $patterns = array(
	            // twitpic
	            array('/http:\/\/twitpic[.]com\/(\w+)/', '<img src="http://twitpic.com/show/thumb/$1" width="150" height="150" />'),
	
	            // Mobypicture
	            array('/http:\/\/moby[.]to\/(\w+)/', '<img src="http://moby.to/$1:small" />'),
	
	            // yFrog
	            array('/http:\/\/yfrog[.]com\/(\w+)/', '<img src="http://yfrog.com/$1.th.jpg" />'),
	
	            // 携帯百景
	            array('/http:\/\/movapic[.]com\/pic\/(\w+)/', '<img src="http://image.movapic.com/pic/s_$1.jpeg" />'),
	
	            // はてなフォトライフ
	            array('/http:\/\/f[.]hatena[.]ne[.]jp\/(([\w\-])[\w\-]+)\/((\d{8})\d+)/', '<img src="http://img.f.hatena.ne.jp/images/fotolife/$2/$1/$4/$3_120.jpg" />'),
	
	            // PhotoShare
	            array('/http:\/\/(?:www[.])?bcphotoshare[.]com\/photos\/\d+\/(\d+)/', '<img src="http://images.bcphotoshare.com/storages/$1/thumb180.jpg" width="180" height="180" />'),
	
	            // PhotoShare の短縮 URL
	            array('/http:\/\/bctiny[.]com\/p(\w+)/e', '\'<img src="http://images.bcphotoshare.com/storages/\' . base_convert("$1", 36, 10) . \'/thumb180.jpg" width="180" height="180" />\''),
	
	            // img.ly
	            array('/http:\/\/img[.]ly\/(\w+)/', '<img src="http://img.ly/show/thumb/$1" width="150" height="150" />'),
	
	            // brightkite
	            array('/http:\/\/brightkite[.]com\/objects\/((\w{2})(\w{2})\w+)/', '<img src="http://cdn.brightkite.com/$2/$3/$1-feed.jpg" />'),
	
	            // Twitgoo
	            array('/http:\/\/twitgoo[.]com\/(\w+)/', '<img src="http://twitgoo.com/$1/mini" />'),
	
	            // pic.im
	            array('/http:\/\/pic[.]im\/(\w+)/', '<img src="http://pic.im/website/thumbnail/$1" />'),
	
	            // youtube
	            array('/http:\/\/(?:www[.]youtube[.]com\/watch(?:\?|#!)v=|youtu[.]be\/)([\w\-]+)(?:[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]*)/', '<img src="http://i.ytimg.com/vi/$1/hqdefault.jpg" width="240" height="180" />'),
	
	            // imgur
	            array('/http:\/\/imgur[.]com\/(\w+)[.]jpg/', '<img src="http://i.imgur.com/$1l.jpg" />'),
	
	            // TweetPhoto, Plixi, Lockerz
	            array('/http:\/\/tweetphoto[.]com\/\d+|http:\/\/plixi[.]com\/p\/\d+|http:\/\/lockerz[.]com\/s\/\d+/', '<img src="http://api.plixi.com/api/TPAPI.svc/imagefromurl?size=mobile&url=$0" />'),
	
	            // Ow.ly
	            array('/http:\/\/ow[.]ly\/i\/(\w+)/', '<img src="http://static.ow.ly/photos/thumb/$1.jpg" width="100" height="100" />'),
	
	            // Instagram
	            array('/http:\/\/instagr[.]am\/p\/([\w\-]+)\//', '<img src="http://instagr.am/p/$1/media/?size=t" width="150" height="150" />'),
	
	            // フォト蔵
	            array('/http:\/\/photozou[.]jp\/photo\/show\/\d+\/([\d]+)/', '<img src="http://photozou.jp/p/thumb/$1" />'),
	
	            // ついっぷる フォト
	            array('/http:\/\/p[.]twipple[.]jp\/([\w]+)/', '<img src="http://p.twipple.jp/show/thumb/$1" />'),
	    );
	
	    foreach ($patterns as $pattern) {
	        if (preg_match($pattern[0], $status_text, $matches)) {
	            $url = $matches[0];
	            $html = preg_replace($pattern[0], $pattern[1], $url);
	            $html = '<a href="' . $url . '" target="_blank">' . $html . '</a>';
	            break;
	        }
	    }
	
	    return $html;
	}
	
	/**
	 * $patternの後の連続したホワイトスペースを削除
	 */
	public static function trimTwitterUrl($ret) {
	    $pattern0 = "http://";
	    $pattern1 = "&nbsp;";
	     
     // スペース削除置換
	        preg_match_all('|'.preg_quote($pattern0, '/').'.*'.$pattern1.'|U', $ret, $matches);
	        
	        foreach ($matches as $match) {
	            $replace  = preg_replace('/\s/', "", $match);
	            $ret = str_replace($match, $replace, $ret);
	        }
	        $ret = str_replace($pattern1, " {$pattern1} ", $ret); // &nbsp;の前後にスペースを追加
	        
	    return $ret;
	}
}
?>