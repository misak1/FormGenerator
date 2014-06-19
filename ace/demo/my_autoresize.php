<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Editor</title>
  <style type="text/css" media="screen">

	.ace_editor {
		position: relative !important;
		border: 1px solid lightgray;
		margin: auto;
		height: 200px;
		width: 80%;
	}
	.scrollmargin {
		height: 100px;
        text-align: center;
	}
    </style>
</head>
<body>
<?php
function writeFile($filename, $str) {
        clearstatcache();
        $fp=@fopen($filename, 'wb');
        if($fp){
            fwrite($fp, $str . "\n");
            fclose($fp);
            echo "saved";
        }else{
            echo "saveing failed..";
        }
}

if(isset($_POST['editor1'])){
   writeFile('form.html', $_POST['editor1']);
}
?>
<form mane="form_body_src" method="post">
<input id="hidden_body_src" type="hidden" name="editor1">
<pre id="editor1"><?php echo htmlspecialchars(file_get_contents('form.html'),ENT_QUOTES); ?></pre>
<div class="scrollmargin"></div>

<script src="kitchen-sink/require.js"></script>

<script>
// setup paths
require.config({paths: { "ace" : "../lib/ace"}});
// load ace and extensions
require(["ace/ace"], function(ace) {

    var editor1 = ace.edit("editor1");
    editor1.setTheme("ace/theme/tomorrow_night_eighties");
    editor1.session.setMode("ace/mode/html");
    editor1.setAutoScrollEditorIntoView(true);
    editor1.setOption("maxLines", 30);
});
function hgoega(){
  require(["ace/ace"], function(ace) {
    var editor1 = ace.edit("editor1");
    var edit1 = editor1.getSession().getValue();
    document.getElementById('hidden_body_src').value = edit1;
    document.forms[0].submit();
  });
}
</script>
<p>
<input type="button" onclick="hgoega();" value="UPDATE"/>
<input type="button" onclick='location.href="/fg/formGenerator.php?do=form_reset"' value="RESET" />
</p>
</form>

</body>
</html>
