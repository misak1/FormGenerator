$(document).ready(function() {
	$("input[name=right]").click(function() {
		move("product_id_list", "s2");
	});
	$("input[name=left]").click(function() {
		move("s2", "product_id_list");
	});
	function move(_this, target) {
		$("select[name=" + _this + "] option:selected").each(function() {
			$("select[name=" + target + "]").append($(this).clone());
			$(this).remove();
		});
	}
});