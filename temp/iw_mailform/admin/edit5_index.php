<?php
require_once ('../boot.php');
require_once ('category.class.php');
require_once ('group.class.php');
include ('session_check.php');

// form
require_once('../edit5_lib.php');
initSession();

include('edit5_index.html');
?>