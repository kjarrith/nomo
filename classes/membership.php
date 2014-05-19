<?php

require 'mysql.php';

class membership {

	function validate_user($un, $pwd) { 
		$mysql = New Mysql();
		$ensure_credentials = $mysql->verify_Username_and_Pass($un, $pwd);

		if($ensure_credentials) {
			$_SESSION {'status'} = 'authorized';
			header("location:index.php");
		} else return "Vinsamlegast sláið inn rétt notendanafn og lykilorð";
	}
}
