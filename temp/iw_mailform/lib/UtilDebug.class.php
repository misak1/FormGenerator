<?php
class UtilDebug {
	/**
	 * &lt;pre&gt;で囲んだ文字列を表示
	 */
	public static function printPre($str_arrray) {
		print('<pre>');
		print_r($str_arrray);
		print('</pre>');
	}

	/**
	 * &lt;pre&gt;で囲んだ文字列を表示
	 */
	public static function showParam() {
		print("--------- SERVER ---------" . "<br/>");
		print("<pre>");
		var_dump($_SERVER);
		print("</pre>");
		print("--------- GET ---------" . "<br/>");
		print("<pre>");
		var_dump($_GET);
		print("</pre>");
		print("--------- POST ---------" . "<br/>");
		print("<pre>");
		var_dump($_POST);
		print("</pre>");
		print("--------- SESSION ---------" . "<br/>");
		print("<pre>");
		@var_dump($_SESSION);
		print("</pre>");
		print("--------- FILE ---------" . "<br/>");
		print("<pre>");
		var_dump($_FILES);
		print("</pre>");
	}
}
?>