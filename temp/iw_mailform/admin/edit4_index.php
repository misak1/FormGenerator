<?php
require_once ('../boot.php');
require_once ('category.class.php');
require_once ('member.class.php');
require_once ('reply.class.php');
require_once ('replycategorymember.class.php');
include ('session_check.php');

// form
require_once('../edit4_lib.php');
initSession();

include('edit4_index.html');
?>