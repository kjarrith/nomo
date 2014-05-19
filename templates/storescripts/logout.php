<?php
ob_start();
session_start();
unset($_SESSION);
session_destroy();
session_write_close();
//ADMIN COOKIES
        setcookie("aid", false, time()-3600, "/");
        setcookie("store", false, time()-3600, "/");
        setcookie("manager", false, time()-3600, "/");
        setcookie("password", false, time()-3600, "/");
//USER COOKIES
		setcookie('uid', false, time()-3600, "/");

header('Location: /home');
die;
exit;

?>