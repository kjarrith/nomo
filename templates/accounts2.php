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

<?php
if(isset($_SESSION['new_user_id'])) {
    $userID = $_SESSION['new_user_id'];
}
if(isset($_SESSION['new_user_email'])) {
    $emailID = $_SESSION['new_user_email'];
}
//ÞÁTTA UPPLÝSINGAR FRÁ FORMINU

if(isset($_POST{'email'})) {
    $email = mysql_real_escape_string($_POST['email']);
    $new = $_POST{'newpassword'};
    $new2 = $_POST{'newpassword2'};
    if ($new == $new2 && strlen($new) > 2) {
      $new = sha1($new);
      $sql = mysql_query("SELECT * FROM users WHERE email='$email' LIMIT 1");
      $exist_count = mysql_num_rows($sql); //count the rows in $sql
          if ($exist_count==0) {
                //UPDATE USER INFO
                $sql = mysql_query("UPDATE users SET password = '$new', email = '$email', status = 0, discount = 0 WHERE id = '$userID';")or die(mysql_error());    
                //INSERT INTO EMAILLIST
                        $sql3 = mysql_query("SELECT * FROM EmailList WHERE email = '$email' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
                        $emailCount = mysql_num_rows($sql3);
                        if($emailCount<1){
                            mysql_query("UPDATE EmailList SET email = '$email', date_added = now() WHERE id = '$emailID';")or die(mysql_error());
                        }
                //-------    
                  //SEND CONFIRMATION EMAIL
                  $subject = "Velkomin til NOMO";

                  $headers = "From: Nomo teymið \r\n" . 
                          'X-Mailer: PHP/' . phpversion() . "\r\n" . 
                          "MIME-Version: 1.0\r\n" . 
                          'Reply-To: nomo@nomo.is' . "\r\n" .
                          "Content-Type: text/html; charset=utf-8\r\n" . 
                          "Content-Transfer-Encoding: 8bit\r\n\r\n";

                  $message = '
                         <html>
                            <head>
                              <style>
                                  @import url(http://fonts.googleapis.com/css?family=PT+Sans:400,700);
                                  /* All your usual CSS here*/
                                  body {
                                     font-family: "PT Sans", sans-serif;
                                     color: #444444;
                                  }
                              </style>
                            </head>
                         <body style="background-color: #fff">
                            <table width="100%" border="0" cellspacing="20px" cellpadding="0" style="">
                                <tr style="border:1px solid #ccc; border-radius:10px;">
                                    <td align="center">
                                        <img src="http://nomo.is/images/nomologo_long_trans.png" alt="NOMO"/>
                                    </td>
                                    <hr/>
                                </tr>
                                <tr style="">
                                    <td align="center">
                                        <h3 style="margin: 0px auto; font-size:25px;">Til hamingju <span style="color:#444;">'.$name.'</span>!</h3> 
                                        <br/>
                                        <h3 style="margin: 0px auto; color:#444;">Þú ert núna í NOMO klúbbnum.</h3>
                                        <br/>
                                        <h3 style="margin: 0px auto; color:#222;">Það þýðir að þú getur notað <span style="color:#888;">eftirfarandi upplýsingar</span><br/> til þess að skrá þig inn á www.nomo.is þegar við opnum!</h3>
                                      <hr/>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <table style="border-color: #666; width:30%; border-collapse:collapse;" cellpadding="10" align="center">
                                        <tr style="background: #eee;border:0px solid #fff; border-radius:10px;">
                                          <td><strong>Netfang:</strong> </td>
                                          <td>'.$email.'</td>
                                        </tr>
                                        <tr style="background: #eee;">
                                          <td><strong>Lykilorð:</strong> </td>
                                          <td>egerhetja </td>
                                        </tr>
                                        </table>
                                  </td>
                              </tr>
                              <tr>
                                  <td align="center">
                                    <h3 style="margin: 0px auto; color:#444;">Þú getur svo BREYTT lykilorðinu þínu með því að smella á nafnið þitt eftir að þú ert búin/nn að skrá þig inn!</h3>
                                    <br/>
                                    <hr/>
                                    <h3 style="margin: 0px auto;">Okkur þætti virkilega gaman að fá þig inn á <a>www.nomo.is</a>, því að við elskum að hjálpa fólki að finna föt sem henta þeim, sama frá hvaða fataverslun þau eru.</h3>
                                  </td>
                              </tr>
                              </table>
                            </body></html>
                  ';
                  mail($email, $subject, $message, $headers);
                  // MAIL KLÁRAST
                $response = "<p style='color:#333;'>Takk fyrir þetta! <br/> <br/> Við sendum tölvupóst á netfangið ".$email." sem þú getur núna kíkt á! <br/><br/>Þú ert góð manneskja.</p>";
                header("Location: /login");
                exit();
          } else {
            $response = "<p style='color:#333;'>Við erum með notanda skráðan með þessu netfangi. Gæti það kannski verið þú? <br/><br/> Ef ekki, prufaðu þá aftur.</p>";
          }  
      } else {
          $response = "Lykilorðin verða að vera þau sömu! <br/><br/> Reyndu aftur.";
      } 
}
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

<form method="post" action="" onsubmit="return validateForm()" name="createaccount" >
                <?php if(isset($response))  {
                  echo $response;
                } else {
                  echo '<h3 style="font-size: 30px; color: #333;"> Nomo Klúbburinn<br/> </h3>
                    <p style="color:#555;">Skráðu þig í klúbbinn og við komum þér á óvart með afsláttum og skemmtilegum tölvupóstum!</p>
                ';
                }?>
                    <p>
                        <input type="email" name="email" class="input bigger" placeholder="Netfang" />
                    </p>
                    <p>
                        <input type="password" name="newpassword" class="input bigger" placeholder="Lykilorð" />
                    </p>
                    <p>
                        <input type="password" name="newpassword2" class="input bigger" placeholder="Lykilorðið Aftur" />
                    </p>
                    <div style="border-bottom: 1px solid #ccc; width: 200px; margin:0px auto;"></div>
                    <p>
                        <input type="submit" class="button margin" value="STAÐFESTA" name="submit" style="width:250px;" />
                    </p>
              
            </form>
            <a href="/login">Viltu skrá þig inn?</a>
        </div>     
        <br/><br/>
    </div>

<script type="text/javascript">
  function validateForm()
  {
  var x=document.forms["createaccount"]["email"].value;
  if (x==null || x=="")
    {
    alert("Þú gleymdir að láta okkur fá netfangið þitt");
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