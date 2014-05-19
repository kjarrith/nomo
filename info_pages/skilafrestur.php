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
<title>Nomo - Heimili tískunnar</title>

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
      <h1 style="text-align:center;">Skil og Skilafrestur</h1>
      <h3>Viltu skila einhverju? Ekkert mál.</h3>
      <hr/>
      <div class="max-width">
      <h4>Hverju get ég skilað?</h4>
      <p>Hægt er að skila vöru sem er í góðu ástandi og í upprunalegum umbúðum. Kaupverð er endurgreitt að fullu sé henni skilað innan 14 daga frá því að viðskiptavinur hefur kvittað fyrir móttöku.<br/><br/> Mundu bara að geyma staðfestingartölvupóstinn sem þú færð þegar þú kaupir vöru á nomo.is. Þar eru ýmsar upplýsingar sem þú þarft á að halda til þess að fylla út <strong>skilaeyðublaðið</strong> sem má nálgast hér að neðan.</p>
      <!--<p>Frá því að þú kvittar fyrir móttöku vörunnar, hefur þú 7 daga til þess að ákveða hvort þú skilir vörunni eða ekki.<br/><br/>Ef varan er gölluð borgar Nomo sendingarkostnaðinn sem fellur til við skil á vöru en annars er sendingarkostnaður á ábyrgð kaupanda.</p>-->
      <hr/>
      <h4>Hverju fæst ekki skilað?</h4>
      <p>Ekki er hægt að skila útsöluvörum. <br/><br/> Vegna hreinlætisráðstafana er ekki hægt að skila vörum eins og nærbuxum, eyrnalokkum og sundfatnaði sem eiga það til að óhreinkast við minnstu notkun. </p>
      <hr/>
      <h4>Skiptimöguleiki</h4>
      <p>Hægt er að fara með vöruna í þá verslun sem hún tilheyrir og skipta henni fyrir aðra í annarri stærð eða fyrir inneignarnótu í viðeigandi verslun.</p>
      <hr/>
      <h4>Ástand skilavarnings</h4>
      <p>Allar vörur eru skoðaðar við skil. <br/> <br/>Við reynum eins og við getum að taka við flestum skilum en ef ástand vöru er ekki það sama og þegar hún var send, er Nomo ekki skyldugt til þess að framkvæma endurgreiðslu og munum við þá senda vöruna aftur til þín ef það er tekið fram á <strong>skilaeyðblaði</strong>.</p>
      <hr/>
      <h4>Ábyrgð viðskiptavinar</h4>
      <p>Þú berð ábyrgð á vörunni á leið til okkar, svo vertu viss um að pakka vörunni vel inn svo hún skemmist ekki!</p>
      <hr/>
      <h4>Endurgreiðslureikningur</h4>
      <p>Lagt verður inn á þann reikning sem stóð fyrir kaupum um leið og staðfest hefur verið að tiltekin vara sé í sama ástandi og þegar hún yfirgaf höfuðstöðvar Nomo. <br/><br/> Ef svo ólíklega vill til að ekki hafi verið lagt inn á þig innan 10 daga frá skilum, skaltu hafa samband við okkur með því að senda mail á <strong>nomo@nomo.is</strong> </p>
      <hr/>
      <a href="http://www.nomo.is/functions/dl.php"><h4 style="text-align:center;">Sækja skilaeyðublað!</h4></a>
      </div>
  </div>
</div>
<?php require_once(APPDIR . '/includes/footer_1up.php'); ?>   
</div>




</body>
</html>