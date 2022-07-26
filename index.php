<?php
header("Access-Control-Allow-Origin: *");
require "home.html";
require "template.php";

$traceback = $_SERVER['HTTP_REFERER'];

//echo $traceback;
?>
