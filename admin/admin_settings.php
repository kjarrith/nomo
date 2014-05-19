<?php
ob_start();
session_start();

if (!isset($_COOKIE{"manager"})) {
    header("location:admin-login.php");
    exit();
}
//Be sure to check if the SESSION details are in fact in the database.
$managerID = preg_replace('#{0-9}#i', '', $_COOKIE{"aid"});
$manager = preg_replace('#{^A-Za-z0-9}#i', '', $_COOKIE{"manager"});
$password = preg_replace('#{^A-Za-z0-9}#i', '', $_COOKIE{"password"});



//CONNECT TO THE DATABASE
include '../templates/storescripts/connect_to_mysql.php';
$sql = mysql_query("SELECT * FROM admin WHERE id='$managerID' AND username='$manager' AND password='$password' LIMIT 1");

//BE SURE THAT THE PERSON EXCISTS IN THE DATABASE
$exist_count = mysql_num_rows($sql); //count the rows in $sql
if ($exist_count==0) {
    echo "Upplýsingar þínar eru ekki í gagnagrunninum okkar";
    exit();
}

   $sql = mysql_query("SELECT * FROM stores WHERE admin = '$manager'");
    if($sql != ""){
        $productCount = mysql_num_rows($sql);
    }
    if(isset($productCount) & $productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $sid = $row{"id"};
            $about = $row["about"];
          }
    }

?>

<?php

//ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>


<?php
//Breyta þeim upplýsingum sem beðið erum að breyta í forminu.

//BREYTA NAFNI
if(isset($_POST{'name'})) {
$name = mysql_real_escape_string ($_POST{'name'});
  $sql = mysql_query("UPDATE users SET name = '$name' WHERE id = '$userID';")or die(mysql_error());
  header("location:admin_settings.php");
  exit();
}
//BREYTA LÝSINGU
if(isset($_POST{'email'})) {
$email = mysql_real_escape_string ($_POST{'email'});
  $sql = mysql_query("UPDATE users SET email = '$email' WHERE id = '$userID';")or die(mysql_error());
  header("location:admin_settings.php");
  exit();
}
//BREYTA VERÐI
if(isset($_POST{'address'})) {
$address = mysql_real_escape_string ($_POST{'address'});

  $sql = mysql_query("UPDATE users SET address = '$address' WHERE id = '$userID';")or die(mysql_error());
  header("location:admin_settings.php");
  exit();
}

//BREYTA LYKILORÐI
if(isset($_POST{'oldpassword'}) & isset($_POST{'newpassword'}) & isset($_POST{'newpassword2'})) {
$old = $_POST{'oldpassword'};
$new = $_POST{'newpassword'};
$new2 = $_POST{'newpassword2'};
    if ($new == $new2 & strlen($new) > 2) {
      $new = sha1($new);
      $sql = mysql_query("UPDATE admin SET password = '$new' WHERE id = '$managerID';")or die(mysql_error());
      header("location: admin-login.php");
      exit();    
    } else {
  $response = "lykilorðin verða að vera þau sömu!";
}
}

//BREYTA LÝSINGU
if(isset($_POST{'about'})) {
$newabout = mysql_real_escape_string ($_POST{'about'});
  $sql = mysql_query("UPDATE stores SET about = '$newabout' WHERE id = '$sid';")or die(mysql_error());
  header("location:admin_settings.php");
  exit();
}

?>


<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Stillingar</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../cssmenu/menu_assets/styles.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../demo/menu.css" type="text/css" media="screen" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />

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
  
<?php include_once("../includes/header_admin.php"); ?>
<?php include_once("../includes/menu-admin.php"); ?>

<div id="main"> 
  <div id="profile-info" style="margin:0px auto; width:300px; text-align:center;">
    <?php echo "<h1> Hæ <strong>". $manager . "</strong></h1>" ?>
      <?php
          echo '
          Uppfærðu lýsingu Verslunar þinnar! <br/> <br/>
          <form action="admin_settings.php" enctype="multipart/form-data" name="edit" method="post">
            <textarea wrap="soft" rows="4" cols="20" name="about" class="input align_left">' . $about . '</textarea> <br/>
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          <br/>
          Viltu breyta lykilorði þínu?
          <br/>
          <br/>
          <form action="admin_settings.php" enctype="multipart/form-data" name="edit" method="post">
            <input name="oldpassword"  class="input align_left" type="password" size="20" placeholder="Gamla lykilorðið"> <br/>
            <input name="newpassword"  class="input align_left" type="password" size="20" placeholder="Nýja lykilorðið"> <br/>
            <input name="newpassword2"  class="input align_left" type="password" size="20" placeholder="Nýja aftur"><br/>
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          '
          ?>

          <?php
    if (isset($response)) 
        echo "<h4>" . $response . "</h4>";
    
?>  

 <a href='admin-logout.php'><button class="button">Skrá mig út</button></a>
  </div>
</div>




</body>
</html>