<?php
global $cssVersion;
global $faviconVersion;
session_start('');

if (isset($_COOKIE{'uid'})){
//Be sure to check if the SESSION details are in fact in the database.
$userID = $_COOKIE{"uid"};

$sql = mysql_query("SELECT * FROM users WHERE id = '$userID' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
if($sql){
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
    $category_list_konur = "Engar búðir ná að birtast";
} 
          $message ="";
?>
<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Hafðu Samband!</title>

<link rel="shortcut icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>">
<link rel="icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>" type="image/x-icon">

<link href="../css/styles.css?v=<?php echo $cssVersion; ?>" rel="stylesheet" type="text/css" />
<link href="../cssmenu/menu_assets/styles.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../demo/menu.css" type="text/css" media="screen" />

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
          <div id="email_wrapper">
          <?php
        if (isset($_POST['email']))
        //if "email" is filled out, send email
          {
          //send email
          $email = $_POST['email'] ;
          $subject = $_POST['subject'] ;
          $headers = "From: ".$email."\r\n" . 
        'X-Mailer: PHP/' . phpversion() . "\r\n" . 
        "MIME-Version: 1.0\r\n" . 
        "Content-Type: text/html; charset=utf-8\r\n" . 
        "Content-Transfer-Encoding: 8bit\r\n\r\n"; 
          $skilabod = $_POST['message'] ;
          $message .= '<html>
          <head>
            <style>
                @import url(http://fonts.googleapis.com/css?family=PT+Sans:400,700);
                /* All your usual CSS here */
                body {
                   font-family: "PT Sans", sans-serif;
                   color: #444444;
                }
            </style>
          </head>

          <body background="">

          <table width="100%" border="0" cellspacing="20px" cellpadding="0">
              <tr>
                  <td align="center">
                      <img src="http://nomo.is/images/nomologo.png" alt="NOMO"/>
                  </td>
              </tr>
              <tr>
                  <td align="center">
                      <h3 style="margin: 0px auto;">Til hamingju!</h3> 
                      <br/>
                      <h3 style="margin: 0px auto;">Þú varst að fá skilaboð:</h3>
                  </td>
              </tr>
              <tr>
                  <td align="center">
                      <table style="border-color: #666; width:100%;" cellpadding="10">
                      <tr style="background: #eee;">
                      <td width:20%;><strong>Frá:</strong> </td>
                      <td>' . $email . '</td>
                      </tr>
                  </td>
              </tr>
          </table>
          <hr/>
          <p style="margin: 0px auto;">'.$skilabod.'</p>
          <hr/>
          <h3 style="margin: 0px auto;">Nú væri sko heldur betur hentugt að svara þessari fyrirspurn eins fljótt og mögulegt er!</h3>'
          ;
          mail('nomo@nomo.is', $subject, $message, $headers);
          $response = "Takk fyrir að hafa samband. <br/> Við munum svara fyrirspurn þinni eins fljótt og hægt er. <br/> <a href='../index.php'>Taktu mig aftur á forsíðuna.</a>";
          }
        else
        //if "email" is not filled out, display the form
          {
          $message ="";
          echo "<form method='post' action=''>

          <h1 class='margin'> Við höfum öll eitthvað að segja. </h1>
          <p class='margin'>Komdu þínu á framfæri með því að senda okkur <strong>skilaboð</strong></p>
          <div style='width:400px; margin:0px auto;'><br/><hr/></div>          
          <br/>


          <p class='margin'>Öruggasta og hraðasta leiðin til þess að komast í samband við einhvern á vegum nomo.is <br/> er að senda okkur skilaboð í gegnum <strong>Facebook</strong>:</p>          
          <h2>Nomo á Facebook:</h2>
          <a href='https://www.facebook.com/www.nomo.is' target='_blank'><img src='/images/icons/facebook-very-long.png' style='height:80px;' class='hover'></a>

          <br/>
          <div style='width:100px; margin:0px auto;'><br/><img src='/images/elements/dots.png'></div>
          <p class='margin'>Ef þú ert ekki á Facebook, fylltu þá upp í formið hér að neðan <br/> og við svörum fyrirspurn þinni eins fljótt og mögulegt er:</p> <br/>

          Þitt Netfang: <br/> <input name='email' type='text' class='input margin' placeholder='Netfang'><br>

          Efnislína: <br/> <input name='subject' type='text' class='input margin' placeholder='Efnislína'><br>

          Skilaboð:<br>

          <textarea name='message' rows='15' cols='50' placeholder='Þín skilaboð...'></textarea><br>

          <input type='submit' value='Senda' class='button'>
          </form>";
          }
        ?>
        <?php
    if(isset($response)) {
         echo "<h4>" . $response . "</h4>";
     }
?>  
        </div>
          </div>
        <?php require_once(APPDIR . '/includes/footer_1up.php'); ?> '
</div>





</body>
</html>
