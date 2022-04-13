<?php
header("Access-Control-Allow-Origin: *");

$traceback = $_SERVER['HTTP_REFERER'];

echo $traceback;
 ?>
