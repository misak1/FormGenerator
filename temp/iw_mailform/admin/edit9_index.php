<?php
require_once ('../boot.php');
require_once ('category.class.php');
require_once ('template.class.php');
require_once ('categorytemplate.class.php');
require_once ('mailtotype.class.php');
include ('session_check.php');

// form
require_once('../edit9_lib.php');
initSession();

include('edit9_index.html');
?>