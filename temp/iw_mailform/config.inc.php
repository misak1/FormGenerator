<?php

header('Content-type: text/plain; charset=UTF-8');
header('Content-Transfer-Encoding: binary');

mb_language("uni");
//内部文字コードを変更
mb_internal_encoding("utf-8");
mb_http_input("auto");
mb_http_output("utf-8");

?>