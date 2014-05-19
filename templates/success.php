<?php
global $cssVersion;
ob_start();
session_start();

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
<title>Til hamingju!</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

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

<div id="main-sans-menu" style="background-image: url('../images/rvk-blur.jpg'); height:100%; width:100%; 
  background-size: cover;
  background-repeat: no-repeat;
  background-position:50% 0%;
  position:relative;"> 
 
        <div id="tester-creation" style="height: auto; padding-bottom: 30px;">
          <br/>
          <div class="max-width">
            <h1>Takk <span style="color:#FF4C78">Kærlega</span> fyrir að sækja um þáttöku í prufuferlinu!</h1>

            <p> Við munum núna fara yfir þær upplýsingar sem þú hefur gefið okkur og sjáum hvort allt sé á sínum stað. <br/> <br/>
             <span style="font-size:18px;"> Ef svo er, munum við senda á þig staðfestingu á netfangið sem þú lést okkur hafa, og þú getur þá skráð þig inn með því notendanafni og lykilorði sem þú tókst fram!</span></p>
<br/>
              <a href="../index.php"><div class="button" style="width:250px; margin:0px auto;">Okei, Taktu mig aftur í innskráninguna!</div></a>
            </div>
        </div> 
</div>

</body>
</html>