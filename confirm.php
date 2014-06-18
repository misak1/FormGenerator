<?php
require_once ('formGenerator.php');
startSession();
formHandler();
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no" />
<title>none</title>
<link rel="stylesheet" type="text/css" href="form.css" />
<link rel="stylesheet" type="text/css" media="(min-width:481px)" href="form-pc.css" />
<link rel="stylesheet" type="text/css" media="(max-width:480px)" href="form-mobile.css" />
</head>
<body>
<!-- <?php echo __FILE__; ?> -->
<?php _formGenerator(); ?>
<!-- end.<?php echo __FILE__; ?> -->
</body>
</html>
