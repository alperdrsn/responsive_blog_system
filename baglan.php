<?php

session_start();
date_default_timezone_set('Europe/Istanbul');
$db = @new mysqli('localhost', 'root', '', 'blog');
if($db->connect_errno){
	die('Bağlantı Hatası:'. $db->connect_error);
}
$db->set_charset("utf8");

?>