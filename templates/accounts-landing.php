<?php

ob_start();
session_start();
global $cssVersion;
global $faviconVersion;
//if (isset($_SESSION{"username"})) {
//    header("location:index.php");
//    exit();
//}

//CONNECT TO THE DATABASE
include 'storescripts/connect_to_mysql.php';
?>

<?php
//ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 'On');

?>
<!DOCTYPE html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Búðu til aðgang!</title>

<link rel="shortcut icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>">
<link rel="icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>" type="image/x-icon">

<link href="../css/styles.css?v=<?php echo $cssVersion; ?>" rel="stylesheet" type="text/css" />
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

<?php require_once(APPDIR . '/includes/header_simple.php'); ?>

<div id="main-sans-menu" style="background-image: url('../images/fullscreen/nomohome-4.jpg'); padding-bottom: 50px;"> 
 
<div id="accounts-wrapper" > 
    <h3>Ertu nýr notandi eða ertu hérna til að skrá þig inn?</h3> <hr/>
                        <a href="/accounts"><div class="button margin action" style="width:250px; margin:20px auto; color:#fff;" /><strong>NÝR NOTANDI</strong></div></a>
                        <a href="/login"><div class="button margin" style="width:250px; margin:20px auto;" /><strong>SKRÁ MIG INN</strong></div></a>
</div>     
    </div>

<script type="text/javascript">
  function validateForm()
  {
  var x=document.forms["createaccount"]["name"].value;
  if (x==null || x=="")
    {
    alert("Þú gleymdir að segja okkur hvað þú heitir :)");
    return false;
    }
  var x=document.forms["createaccount"]["email"].value;
  var atpos=x.indexOf("@");
  var dotpos=x.lastIndexOf(".");
  if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
    {
    alert("Ertu alveg viss um að þetta sé rétta netfangið þitt?");
    return false;
    }
  }

</script>

</body>
</html>