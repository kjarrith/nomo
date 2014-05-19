<?php
global $cssVersion;
global $faviconVersion;
session_start('');
ob_start();

if (isset($_COOKIE{'uid'})){
//Be sure to check if the SESSION details are in fact in the database.
$userID = $_COOKIE{"uid"};

$sql = mysql_query("SELECT * FROM users WHERE id = '$userID' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $name = $row['name'];
        $username = $row['username'];
        $address = $row['address'];
        $email = $row['email'];
      }
      $name = ucfirst($name); 
}
}

//ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>
<?php 
// RUN A SELECT QUERY TO DISPLAY MY CATEGORIES IN THE MENU

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
?>

<?php 
// RUN A SELECT QUERY TO DISPLAY MY CATEGORIES IN THE MENU

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
<meta name="author" content="Kjartan Þóriss: 696-7602">
<meta name="keywords" content="ódýr föt á netinu, kaupa föt á netinu, íslensk vefverslun, íslensk föt á netinu, kringlan á netinu, fatamarkaður, gott verð, ódýrt, frí heimsending, skilafrestur ">

<meta property="og:title" content="Nomo - Verslunarmiðstöð á netinu" >
<link href="http://www.nomo.is/images/opengraphimg.jpg" rel="image_src"/>
<meta property="og:image" content="http://www.nomo.is/images/opengraphimg.jpg" />
<meta property="og:url" content="http://www.nomo.is/home" />

<title>Nomo : Verslaðu föt frá íslenskum fataverslunum á netinu</title>
<link rel="shortcut icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>">
<link rel="icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>" type="image/x-icon">

<link href="../css/styles.css?v=<?php echo $cssVersion; ?>" rel="stylesheet" type="text/css" />
<link href="cssmenu/menu_assets/styles.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="demo/menu.css" type="text/css" media="screen" />

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

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=234032696651711";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script>$(function() {
    $('.banner').unslider();
});</script>


<?php include_once("includes/header.php"); ?>
<?php include_once("includes/menu.php"); ?>

<div id="main" style="background:#f8f8f8; padding-bottom: 200px; background-image:url('http://www.topman.com/wcsstore/ConsumerDirectStorefrontAssetStore/images/colors/color6/cms/pages/css/css-0000057835/images/WK36_HOMEPAGE-Friday_opt.jpg'); overflow:hidden;"> 

  <div id="grid-wrapper">

      <div id="big-grid-1">
        <a href="http://nomo.is/accounts-landing">
          <img src="/images/grid/homegrid.jpg" alt="" width="990" height="450">
        <a>
      </div>
      <div id="small-grid-1">
        <a href="http://nomo.is/flokkur/21">
          <img src="/images/grid/leftgrid1.jpg" alt="Oxford Blazers - Dapper wardrobe essentials only £65" width="330" height="450">
        <a>
      </div>
      <div id="small-grid-2">
        <a href="http://nomo.is/flokkur/5">
          <img src="/images/grid/gridcenter.jpg" alt="Oxford Blazers - Dapper wardrobe essentials only £65" width="330" height="450">
        <a>
      </div>Ý
      <div id="small-grid-3">
        <a href="http://nomo.is/flokkur/9">
          <img src="/images/grid/gridleft1.jpg" alt="Oxford Blazers - Dapper wardrobe essentials only £65" width="330" height="450">
        <a>
      </div>
      <div id="big-grid-2">
        <a href="http://nomo.is/verslun/16">
          <img src="/images/grid/define.jpg" alt="" width="990" height="450">
        <a>
      </div>


  </div>
      <div style="width:900px; margin:0px auto; position: relative; margin-top: 20px; margin-bottom; 150px; text-align:center;">

      </div>
  <?php include_once("includes/footer.php"); ?> 
</div>



</body>
</html>