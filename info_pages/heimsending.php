<?php
global $cssVersion;
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
    $store_list = "Engar búðir ná að birtast";
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
<title>Nomo - Miðpunktur tísku</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

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
<div id="product-wrapper" style="position:relative; top:20px;">
  <div id="profile-info">
      <h1 style="text-align:center; ">Heimsending</h1>
      <h3>Við viljum koma sendingunni í hendurnar á þér eins fljótt og hægt er.</h3>
      <hr/>
      <div class="max-width">
      <h4>Höfuðborgarsvæðið</h4>
      <p>Heimsending er ókeypis hvert á land sem er. <br/><br/>Sendingin verður komin heim til þín innan þriggja virkra daga frá staðfestri greiðslu. <br/><br/>Þú mátt búast við pöntuninni á milli klukkan 12:00 og 20:00. Við munum biðja um kvittun fyrir staðfestingu á móttöku.</p>
      <!--<p>Frá því að þú kvittar fyrir móttöku vörunnar, hefur þú 7 daga til þess að ákveða hvort þú skilir vörunni eða ekki.<br/><br/>Ef varan er gölluð borgar Nomo sendingarkostnaðinn sem fellur til við skil á vöru en annars er sendingarkostnaður á ábyrgð kaupanda.</p>-->
      <hr/>
      <h4>Landsbyggðin</h4>
      <p>Heimsending er ókeypis hvert á land sem er. <br/><br/> Sendingin verður komin heim til þín innan 5 virkra daga frá staðfestri greiðslu. <br><br/> Þú mátt búast við pöntuninni á milli klukkan 12:00 og 20:00. Við munum biðja um kvittun fyrir staðfestingu á móttöku.</p>
      <hr/>
      <h4>Varan ekki komin á réttum tíma?</h4>
      <p>Ef liðið hefur lengri tími en áður framgreindur sendingartími segir til um, endilega sendu okkur stuttan tölvupóst á <strong>nomo@nomo.is</strong> og láttu okkur vita hversu miklir klúðrarar við erum.</p>
      <hr/>
      <h4>Er sendingin á leiðinni til mín?</h4>
      <p>Við munum senda þér staðfestingartölvupóst þess efnis þegar greiðsla hefur átt sér stað. <br><br/>Mundu eftir því að geyma þennan tölvupóst því þar eru ýmsar upplýsingar sem gagnast þér ef svo ólíklega vill til að þú viljir skila pöntuninni.</p>
      <hr/>
      <h4>Hvað gerist ef ég er ekki heima þegar sending kemur?</h4>
      <p>Við munum reyna að koma með vöruna aftur ef þú ert ekki heima þegar heimsending á sér stað. Ef þú ert ekki heldur heima þá, munum við skilja eftir skilaboð um hvar og hvernig má nálgast pakkann, sem líklegast verður á næsta pósthúsi. </p>
      </div>
  </div>
</div>
<?php require_once(APPDIR . '/includes/footer_1up.php'); ?>   
</div>




</body>
</html>