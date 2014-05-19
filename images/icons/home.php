<?php
global $cssVersion;
session_start('');

if (isset($_SESSION{'uid'})){
//Be sure to check if the SESSION details are in fact in the database.
$userID = $_SESSION{"uid"};

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


} else {

          header("location:/opnun");
          exit();
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
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">

<meta property="og:title" content="Nomo - Öll flottustu" >
<link href="http://www.nomo.is/images/opengraphimg.jpg" rel="image_src"/>
<meta property="og:image" content="http://www.nomo.is/images/opengraphimg.jpg" />
<meta property="og:url" content="http://www.nomo.is/home" />

<title>Nomo</title>
<link rel="shortcut icon" href="images/favicon.ico">
<link rel="icon" href="images/favicon4.ico" type="image/x-icon">

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

<div id="main" style="background:#f8f8f8;overflow:hidden;"> 
<div class="banner">
      <ul>
          <li>
                  <div id="background-container" style="background-image: url('../images/nomohome.jpg');">

                  </div>
           <div id="feedname" style="min-height: 800px; padding-bottom: 0;">
            <p style="color:#333; font-size: 30px; line-height: 37px; margin-top: 50px; text-align:left; float:left;"> Nomo mun færa þér allar <br/>flottustu fataverslanirnar<span style="color:#222;"><br/> á Íslandi</span><br/><br/><span style="color:#555; font-size:45px; font-weight: bold; margin-top: 30px;">Gjörðu svo vel</span> </p><br/>
              
              <div class="button-container" style="margin-top: 50px; text-align:left; float:left; font-size: 20px;">
                <h3 style="text-align:left;float:left;">Og já, við sendum þetta frítt heim til þín</h3>
              </div>
            </div>
          </li>

          <li>
            <div id="feedname" style="width: auto; padding-bottom: 0; min-height: 800px; min-width: 900px; text-align:center; padding-top:100px; margin-top:-100px;">
              <br/>
              <div style="width:100%; margin:auto; text-align:center;">
                            <a href='https://www.facebook.com/www.nomo.is' target="_blank" ><div style="background-color: #3b579d; position:absolute; left:0px; width:50%;height:100%; margin-top:-100px; "></div></a>
                            <a href='http://instagram.com/nomo_strakar' target="_blank"><div style="background-color: #ddd1c7; position:absolute; right:0px; width:50%;height:100%;margin-top:-100px; "></div></a>
                  <ul style="text-align:center; margin:auto; width: ; display: inline-block;">
                      <li id="bla" style="padding:20px; display: inline-block;">
                        <h1 style="font-size:35px; color: #fff;">NOMO Á FACEBOOK</h1><hr/>
                        <a href='https://www.facebook.com/www.nomo.is' target='_blank'><img src='/images/icons/facebook-cover.png' style='height:110px; opacity:0.8;' class='hover'></a>
                      </li>
                    <li id="bla" style="padding:20px;">
                      <h1 style="font-size:35px;">NOMO Á INSTAGRAM</h1><hr/>
                      <a href='http://instagram.com/nomo_strakar' target='_blank'><img src='/images/icons/insta-very-long.png' style='height:110px;' class='hover'></a>
                  </li>
                </ul>
              </div>
            </div>
          </li>

          <li>
                  <div id="background-container" style="background-image: url('../images/morrow1.jpg');">

                  </div>
           <div id="feedname" style="min-height: 800px; padding-bottom: 0;">
            <p style="color:#333; font-size: 30px; line-height: 37px; margin-top: 50px; text-align:left; float:left; position:relative; left: 15%;"> Ný sending frá <br/>MORROW <br/><br/><span style="color:#555; font-size:45px; font-weight: bold; margin-top: 30px;">Vetur 2013</span> </p><br/>
            <div style=" position:relative; left: 15%; margin-top:210px">
                <a href="http://www.nomo.is/verslun/19"><div class="button big"> Skoða</div></a>
                <br/>
            </div>
            </div>
          </li>
      </ul>
    </div>


<?php include_once("includes/footer.php"); ?>   
</div>




</body>
</html>