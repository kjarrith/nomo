<?php

ob_start();
session_start();
include 'storescripts/connect_to_mysql.php';
?>
<?php
if(isset($_POST["username"]) && isset($_POST["password"])) {
    //FILTERING
    $user = mysql_real_escape_string($_POST["username"]);
    $password = $_POST['password'];
    $password = sha1($password);
    //CONNECT TO DATABASE
    $sql = mysql_query("SELECT * 
                        FROM users
                        WHERE username='$user' AND password='$password'
                        LIMIT 1 
                        ");
    //MAKE SURE PERSON EXISTS IN DATABASE 
    $exist_count=mysql_num_rows($sql); 
    if($exist_count>0){
        while($row = mysql_fetch_array($sql)){
            $id = $row{'id'};
            $name = $row['name'];
            $address = $row['address'];
            $email = $row['email'];
        }
        $_SESSION{"uid"} = $id;
        $_SESSION{"username"} = $user;
        header("Location: ../index.php");
        exit();
    } else {
        $response = 'Notendanafnið og lykilorðið smullu ekki saman. <a href="login.php"> Reyndu aftur. </a> <br/><br/> <p style="color:#555;"> (Ertu kannski ekki með í prufutímabilinu?) <br/> <a href="create_tester.php">Sæktu um prufuaðgang</a></p> ';
    }
}
?>
<!DOCTYPE html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta http-equiv="refresh" content="7;URL='http://nomo.is'" />   
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, nomo, miðpunktur, tísku, tíska, netverslun, ">
<title>Úpsí!</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
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
<?php include_once("includes/header_simple.php"); ?>

<div id="main-sans-menu">
        <div id ="login" style="margin-top: 100px; ">
            <h1 style="font-size:90px;">Obbossí!</h1>
            <p>Þú lentir í þægilegu tilfelli af fjögurhundruðogfjögur villu...</p> <br/>
            <p>Engar áhyggjur samt, við skulum senda þig aftur á heimasíðuna!</p>
        </div>

</div>



</body>
</html>