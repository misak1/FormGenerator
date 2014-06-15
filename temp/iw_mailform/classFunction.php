<?php
class classFunction {
	/**
	 * 改行コードが複数存在する可能性も踏まえて、2種類の改行コード（\r\n、\r）を
	 * htmlでよく使う改行（\n）に統一した後、\nを区切りに配列へ入れていきます
	 */
	public static function lf_filter($txt) {
		// テキストエリアの値を取得
		$cr = array("\r\n", "\r");
		// 改行コード置換用配列を作成しておく

		// 文頭文末の空白を削除
		$txt = trim($txt);

		// 改行コードを統一
		//str_replace ("検索文字列", "置換え文字列", "対象文字列");
		$txt = str_replace($cr, "\n", $txt);

		//改行コードで分割（結果は配列に入る）
		$lines_array = explode("\n", $txt);
		return $lines_array;
	}

	public static function toCrlf($txt) {
		// 一旦 改行コードを揃えてから行分割する
		$lines_array = self::lf_filter($txt);
		$txt = implode("\r\n", $lines_array);
		return $txt;
	}

	public static function toLf($txt) {
		// 一旦 改行コードを揃えてから行分割する
		$lines_array = self::lf_filter($txt);
		$txt = implode("\n", $lines_array);
		return $txt;
	}

	public static function toCr($txt) {
		// 一旦 改行コードを揃えてから行分割する
		$lines_array = self::lf_filter($txt);
		$txt = implode("\r", $lines_array);
		return $txt;
	}

	public static function binary_dump($in) {
		$bindata = unpack("C*", $in);
		$out = "";
		foreach ($bindata as $v) {
			$out .= sprintf("%02x ", $v);
		}
		return $out;
	}

	public static function subject_encode($subject) {
		//$b = "チーサポ メールフォームからのお問い合わせ";
		//$cmd = 'echo "'.$subject.'" | nkf -jM';
		$cmd = 'echo "' . $subject . '" | ' . mf_nkf . ' -jM';
		$a = array();
		$s = "";
		exec($cmd, $a, $status);
		if (!$status) {
			$s = implode("\r\n", $a);
		}
		return $s;
	}

	// 参考　<shibata.misaki@imagica-imageworks.co.jp>,<misaki.pink@gmail.com>,<misaki.pink@hotmail.co.jp>
	public static function concat_meil($aryMailTo) {
		$a = array();
		$s = "";
		foreach ($aryMailTo as $address) {
			$address = self::atrim($address);
			$address = trim($address, '　');
			$a[] = '<' . $address . '>';
		}
		$s = implode(',', $a);
		return $s;
	}

	// 改行コードの削除
	public static function lf_trim($email) {
		// mail header injection 対策で、念のため $email から改行コードを削除しておく
		$email = implode('', mb_split('\r\n', $email));
		$email = implode('', mb_split('\r', $email));
		$email = implode('', mb_split('\n', $email));
		return $email;
	}

	// 全角スペースの削除
	public static function trim_2bytespacemo($str) {
		$str = preg_replace('/^[ 　]+/u', '', $str);
		$str = preg_replace('/[ 　]+$/u', '', $str);
		return $str;
	}

	public static function atrim($str) {
		$str = self::trim_2bytespacemo($str);
		$str = self::lf_trim($str);
		// 前後のホワイトスペース削除
		$str = trim($str);
		return $str;
	}

	public static function create_mailHeader($from, $sender, $aryTo, $subject, $Cc = "", $Bcc = "") {
		$aryfrom[] = $from;
		$arySender[] = $sender;

		$mail_from = self::concat_meil($aryfrom);
		$mail_sender = self::concat_meil($arySender);
		$mail_to = self::concat_meil($aryTo);
		$mail_subject = self::subject_encode($subject);
		$mail_cc = self::concat_meil($Cc);
		$mail_bcc = self::concat_meil($Bcc);

		$mheader = file_get_contents(mf_mheader);
		$mheader = implode($mail_from, mb_split('%MAIL_FROM%', $mheader));
		$mheader = implode($mail_sender, mb_split('%MAIL_SENDER%', $mheader));
		$mheader = implode($mail_to, mb_split('%MAIL_TO%', $mheader));
		$mheader = implode($mail_cc, mb_split('%MAIL_CC%', $mheader));
		$mheader = implode($mail_bcc, mb_split('%MAIL_BCC%', $mheader));
		$mheader = implode($mail_subject, mb_split('%SUBJECT%', $mheader));
		$mheader = self::toCrlf($mheader);
		writeLog($mheader);
		return $mheader;
	}

	/**
	 * メール本文作成
	 */
	public static function create_mailBody($category, $company, $username, $email, $tel, $content, $body) {
		$ts = date('Y年m月d日 H時i分s秒');
		$body = implode($category, mb_split('%CATEGORY%', $body));
		$body = implode($company, mb_split('%COMPANY%', $body));
		$body = implode($username, mb_split('%USERNAME%', $body));
		$body = implode($email, mb_split('%EMAIL%', $body));
		$body = implode($tel, mb_split('%TEL%', $body));
		$body = implode($content, mb_split('%CONTENT%', $body));
		$body = implode($ts, mb_split('%TIMESTAMP%', $body));
		return $body;
	}

	/**
	 * 内部向けメッセージ追加
	 */
	public static function create_mailBodyEX($category, $company, $username, $email, $tel, $content, $body) {
		// 内部向けメッセージ
		$internal_message = '';
		if (file_exists(mf_message_file)) {
			$internal_message = file_get_contents(mf_message_file);
		} else {
			$internal_message = '"' . mf_message_file . '" file not exists.';
		}
		$body = implode($internal_message, mb_split('%INTERNAL_MESSAGE%', $body));

		return self::create_mailBody($category, $company, $username, $email, $tel, $content, $body);
	}

	public static function sendMail($header, $body) {

		date_default_timezone_set('Asia/Tokyo');
		// MTA の起動
		$sendmail = mf_sendmail . ' -t ';

		// "\r\n"に変換
		$body = self::toCrlf($body);

		// body は base64 エンコードする
		$b64body = chunk_split(base64_encode($body));

		// では sendmail 起動しよう
		$sm = popen($sendmail, 'w');
		if ($sm !== FALSE) {
			writeLog("sendmail opend");
			fwrite($sm, $header);
			fwrite($sm, "\r\n\r\n");
			fwrite($sm, $b64body);
			pclose($sm);
			//writeLog("header follows:");
			//writeLog($header);
			writeLog("body follows:");
			writeLog($body);
			writeLog("sendmail closed");
		}
	}

}

// メールヘッダー作成テスト
/*
 require_once ('boot.php');
 header('Content-type: text/plain; charset=UTF-8');
 header('Content-Transfer-Encoding: binary');
 // $from,$senderは１要素
 $from = "shibata.misaki@imagica-imageworks.co.jp";
 $sender = "iiw-contact@imagica-imageworks.co.jp";
 // $toは複数可
 $to[] = "shibata.misaki@imagica-imageworks.co.jp";
 $to[] = "misaki.pink@gmail.com";
 $to[] = "misaki.pink@hotmail.co.jp";
 $subject = 'PHP/不等号やアンパサンドなどのエスケープ関数・htmlspecialchars';

 // 改行統一
 $mheader = classFunction::create_mailHeader($from, $sender, $to, $subject);
 */

// メール本文作成テスト
/*
 //header('Content-type: text/plain; charset=UTF-8');
 //header('Content-Transfer-Encoding: binary');
 $category = "テストカテゴリ";
 $company = "テスト会社";
 $username= "テストゆーざー";
 $email= "テストメール";
 $tel = "テスト電話番号";
 $content= "テスト問い合わせ内容";
 $body = classFunction::create_mailBody($category, $company, $username, $email, $tel, $content);
 */

// 送信テスト
/*
 //echo $mheader;
 //echo classFunction::binary_dump($mheader);
 //echo $body;
 //classFunction::sendMail($mheader, $body);
 //echo "送信完了"
 */
/*
 require_once ('boot.php');
 header('Content-type: text/plain; charset=UTF-8');
 header('Content-Transfer-Encoding: binary');

 $from='iw-service-d@imagica-imageworks.co.jp';
 $sender='iw-service-d@imagica-imageworks.co.jp';
 $aryTo[]= 'shibata.misaki@imagica-imageworks.co.jp';
 $edt1_subject='test';

 echo $from . '|';
 echo $sender . '|';
 var_dump($aryTo);
 echo   '|';
 echo $edt1_subject . '|';

 $header = classFunction::create_mailHeader($from, $sender, $aryTo, $edt1_subject);
 echo $header;
 classFunction::sendMail($header, "テスト送信");
 */
?>