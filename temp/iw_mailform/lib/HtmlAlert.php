<?php
/**
 *　HTMLアラート
 * (実行する場合は$isShow=trueに指定して下さい。)
 */
$aryMessageType = array(0 => "", 1 => " alert-success", 2 => " alert-error");

// function宣言
function printHTMLAlert($message_title = "", $message_body = "", $massageType = 0, $isHtmlAlertShow = false) {
	global $aryMessageType;
	// Logger::getLogger('adminLog') -> info("Type:" . $message);
	// Logger::getLogger('adminLog') -> info("Type:" . $massageType);
	// Logger::getLogger('adminLog') -> info("Type:" . $aryMessageType[$massageType]);
	if ($isHtmlAlertShow) {
		print <<<EOF
			<!-- エラー・完了　ページ -->
			<div class="alert {$aryMessageType[$massageType]}">
				<a class="close" data-dismiss="alert" href="">×</a>
				<h4 class="alert-heading">{$message}</h4>
				{$message_body}
			</div>
			<!-- エラー・完了　ページ -->
EOF;

	}
}

// function実行
printHTMLAlert($message_title, $message_body, $massageType, $isHtmlAlertShow);
?>