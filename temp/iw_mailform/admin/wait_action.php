<script type="text/javascript">
function printText(str) {
	$("p.wait_message").empty();
	$("p.wait_message").append("<b>" + str + "</b>");
}
function doWait() {
	// Loop Action
	{
		if (wait_cancel) {
			printText("処理が中断されました。")
			return;
		}
		t = wait_count + 2 + "秒後に元のページに遷移します。" + '<br/>';
		t += '<a href="#" onclick="cancelWait();">中断</a>'
		printText(t);
	}
	// End Action
	if (wait_count < 0) {
		location.href = "<?php echo $and_action_href; ?>";
		return;
	}
	// Waiting...
	setTimeout(function() {
		doWait();
	}, 1000);

	wait_count--;
}
// Wait Cancel
function cancelWait() {
	wait_cancel = true;
}

// 待ち設定
var wait_count = 2;
var wait_cancel = false;
doWait();
</script>
<br/>
<p class="wait_message"></p>