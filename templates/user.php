<?php
global $cssVersion;
global $faviconVersion;
ob_start();
session_start('oid');

if (isset($_COOKIE{'uid'})){
//Be sure to check if the SESSION details are in fact in the database.
$userID = $_COOKIE{"uid"};

$sql = mysql_query("SELECT * FROM users WHERE id='$userID' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
        $productCount = mysql_num_rows($sql);
        if($productCount>0){
            //NÁ Í ALLAR UPPLÝSINGAR UM VÖRUNA OG VERA MEÐ ÞÆR TIL STAÐAR
            while ($row=mysql_fetch_array($sql)) {
                $user = $row{"username"};
                $address = $row{"address"};
                $email = $row{"email"};
                $name = $row{"name"};
                $password = $row['password'];
            }
        } 
}else {
            header("location:login.php");
            exit();
        }

//LÁTA USERNAME VERA MEÐ FYRSTA STAFINN STÓRANN
$user = ucfirst($user); 
//CONNECT TO THE DATABASE

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
$name = $_POST{'name'};
  $sql = mysql_query("UPDATE users SET name = '$name' WHERE id = '$userID';")or die(mysql_error());
  header("location:user");
  exit();
}
//BREYTA LÝSINGU
if(isset($_POST{'email'})) {
$email = $_POST{'email'};
  $sql = mysql_query("UPDATE users SET email = '$email' WHERE id = '$userID';")or die(mysql_error());
  header("location:user");
  exit();
}
//BREYTA VERÐI
if(isset($_POST{'address'})) {
$address = $_POST{'address'};
  $sql = mysql_query("UPDATE users SET address = '$address' WHERE id = '$userID';")or die(mysql_error());
  header("location:user");
  exit();
}

//BREYTA LYKILORÐI
if(isset($_POST{'oldpassword'}) && $_POST{'newpassword'} && $_POST{'newpassword2'}) {
$old = $_POST{'oldpassword'};
$old = sha1($old);
$new = $_POST{'newpassword'};
$new2 = $_POST{'newpassword2'};
  if ($old === $password){
      if ($new == $new2 && strlen($new) > 2) {
            $new = sha1($new);
      $sql = mysql_query("UPDATE users SET password = '$new' WHERE id = '$userID';")or die(mysql_error());
      header("location: user");
      exit();    
      } else {
          $response = "lykilorðin verða að vera þau sömu!";
      } 
    } else {
          $response = "Gamla lykilorðið er ekki rétt.";
      }
}
?>

<?php 
// CATEGORY MENN

$category_list_menn = "";
$sql = mysql_query("SELECT * FROM category WHERE category_gender = 'menn' ORDER BY category_name ASC"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $cid = $row{"id"};
        $category_name = $row['category_name'];
        $category_id = $row['category_id'];
        $category_description = $row['category_description'];
        $category_gender = $row['category_gender'];
        $category_list_menn .= '
<a href="/flokkur/' . $cid . '"><li>' . $category_name . '</li></a>
';
    } 
} else {
    $category_list_menn = "Það eru engar vörur í búðinni";
} 

// CATEGORY KONUR

$category_list_konur = "";
$sql = mysql_query("SELECT * FROM category WHERE category_gender = 'konur' ORDER BY category_name ASC"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $cid = $row{"id"};
        $category_name = $row['category_name'];
        $category_id = $row['category_id'];
        $category_description = $row['category_description'];
        $category_gender = $row['category_gender'];
        $category_list_konur .= '
<a href="/flokkur/' . $cid . '"><li>' . $category_name . '</li></a>
';
    //UPPERCASING VARIABLES
$category_gender = ucfirst($category_gender);
$category_name = ucfirst($category_name); 
    } 
} else {
    $category_list_konur = "Það eru engar vörur í búðinni";
} 
?>

<?php 
// RUN A SELECT QUERY TO DISPLAY MY CATEGORIES IN THE MENU

$store_list = "";
$sql = mysql_query("SELECT * FROM stores ORDER BY store_name ASC"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $storeid = $row{"id"};
        $store_name = $row['store_name'];
        $store_list .= '
<a href="/verslun/' . $storeid . '"><li>' . $store_name . '</li></a>
';
    } 
} else {
    $category_list_konur = "Engar búðir ná að birtast";
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
<title>ValKvíði - Öll fötin á einum stað!</title>

<link rel="shortcut icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>">
<link rel="icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>" type="image/x-icon">

<link href="../css/styles.css?v=<?php echo $cssVersion; ?>" rel="stylesheet" type="text/css" />
<link href="../cssmenu/menu_assets/styles.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../demo/menu.css" type="text/css" media="screen" />

<link href="css/css/bootsrap.css" rel="stylesheet" type="text/css" />

<script src="//code.jquery.com/jquery-latest.min.js"></script>
  <script src="java/unslider.js"></script>
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
  
<?php require_once(APPDIR . '/includes/header_1up.php'); ?>
<?php require_once(APPDIR . '/includes/menu_1up.php'); ?>

<div id="main"> 
  <div id="profile-info" style="text-align:center;">
    <?php echo "<h1> Hæ <strong>". $name . "</strong></h1>" ?>
      <?php
          echo '
          Hér getur þú breytt upplýsingum þínum.
          <form action="user" enctype="multipart/form-data" name="edit" method="post">
            <p style="font-size:15px;"> Nafn: </p> <input name="name" class="input align_left" type="text" placeholder="' . $name . '">
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          <form action="user" enctype="multipart/form-data" name="edit" method="post">
            <p style="font-size:15px;"> Netfang:</p> <input name="email" class="input align_left"  type="text" placeholder="' . $email . '"> 
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          <form action="user" enctype="multipart/form-data" name="edit" method="post">
            <p style="font-size:15px;"> Heimilisfang: </p> <input name="address"  class="input align_left" type="text" size="20" placeholder="' . $address . '">
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          <hr/>
          Viltu breyta lykilorði þínu?
          <br/>
          <br/>
          <form action="user" enctype="multipart/form-data" name="edit" method="post">
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
  </div>
<br/>
<?php require_once(APPDIR . '/includes/footer_1up.php'); ?>   
</div>




</body>
</html>