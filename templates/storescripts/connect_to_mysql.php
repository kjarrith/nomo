<?php

$db_host = "localhost";
$db_username = "nomois_admin";
$db_password = "jBecgroup-1";
$db_name = "nomois_nomo_db";

mysql_connect("$db_host", "$db_username", "$db_password") or die("Could not connect");
mysql_select_db($db_name) or die("no database");
mysql_query("SET NAMES utf8");
