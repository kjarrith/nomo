<?php

ob_start();
session_start();
include "../templates/storescripts/connect_to_mysql.php";

if(isset($_COOKIE["oid"])) {
        header("Location: storeadmin/admin_index.php");
        exit();
    }
?>

<?php
if(isset($_POST{"username"}) && isset($_POST{"password"})) {
    //FILTERING
    $manager = $_POST{"username"};
    $manager = mysql_real_escape_string($manager);
    $password = $_POST{"password"};
    //CONNECT TO DATABASE
    $sql = mysql_query("SELECT * 
                        FROM admin 
                        WHERE username='$manager' AND password='$password' and username = 'admin'
                        LIMIT 2");

    //MAKE SURE PERSON EXISTS IN DATABASE 
    $exist_count = mysql_num_rows($sql); 
    if ($exist_count>0) {
        while($row = mysql_fetch_array($sql)) {
            $id = $row{'id'};
            $store = $row{'store'};
        }
        //CREATING SESSIONS
        $_SESSION{"oid"} = $id;
        $_SESSION{"store"} = $store;
        $_SESSION{"owner"} = $manager;
        //CREATING COOKIES
        $expire=time()+60*60*24*30;
        setcookie("oid", $id, $expire);
        setcookie("store", $store, $expire);
        setcookie("owner", $manager, $expire);

        header("Location: storeadmin/admin_index.php");
        exit();
    } else {
        echo 'That information is incorrect. <a href="index.php"> Click here to try again </a>';
        exit();
    }

}

?>

<!DOCTYPE html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Login to Admin</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/admin.css" rel="stylesheet" type="text/css" />

  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41790272-2', 'nomo.is');
  ga('send', 'pageview');

</script>
</head>

<body onload="process()">

<?php include_once("includes/header_admin.php"); ?>


<div id="main-admin" style="margin-left: 130px; margin-top: 80px;"> 
 
        <div id ="login">
            <form method="post" action="">
               
                    <h3> Hæ Kjartan, Thulin eða Nonni. </h3>

                    <p>
                        <label for="name">Notendanafn</label> <br/>
                        <input type="text" name="username" class="input" />
                    </p>  

                    <p>
                        <label for="pwd">Lykilorð </label> <br/>
                        <input type="password" name="password"  class="input"/>
                    </p>

                    <p>
                        
                        <input type="submit" class="submit" value="Staðfesta" name="button" />
                    </p>
              
            </form>

<?php
    if (isset($response)) 
        echo "<h4>" . $response . "</h4>";
    
?>
        </div>    

    </div>

</div>



</body>
</html>