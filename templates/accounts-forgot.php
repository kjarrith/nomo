<?php
ob_start();
session_start();
global $cssVersion;

if(isset($_POST["email"])) {
    //FILTERING
    $email = mysql_real_escape_string($_POST["email"]);
    //CONNECT TO DATABASE
    $sql = mysql_query("SELECT * 
                        FROM users
                        LIMIT 1 
                        ");
    //MAKE SURE PERSON EXISTS IN DATABASE 
    $exist_count=mysql_num_rows($sql); 
    if($exist_count>0){
        $changesql = mysql_query("UPDATE users SET password = 'ddf4fe3cae262357bc96578c49dfe95662c6c090' WHERE email = '$email' ");
        //SEND REPLACEMENT EMAIL
                  $subject = "Gleymt Lykilorð";

                  $headers = "From: Nomo teymið \r\n" . 
                          'X-Mailer: PHP/' . phpversion() . "\r\n" . 
                          "MIME-Version: 1.0\r\n" . 
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
                                        <h3 style="margin: 0px auto; font-size:25px;">Gleymt <span style="color:#444;">Lykilorð</span>!</h3> 
                                        <br/>
                                        <h3 style="margin: 0px auto; color:#444;">Nýtt lykilorð: <strong>gullhani12</strong></h3>
                                        <br/>
                                        <h3 style="margin: 0px auto; color:#222;">Endilega breyttu svo um lykilorð eins fljótt og hægt er undir "stillingar" flipanum á www.nomo.is</h3>
                                      <hr/>
                                      Takk.
                                    </td>
                                </tr>
                              </table>
                            </body></html>
                  ';
                  mail($email, $subject, $message, $headers);
                  // MAIL KLÁRAST
        header("Location: /login");
        exit();
    } else {
        $response = 'Þetta netfang er ekki skráð.';
    }
}
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
<title>Gleymt Lykilorð!</title>

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

<div id="main-sans-menu" style="padding-bottom: 50px;"> 
 
<div id="accounts-wrapper" >

            <form method="post" action="">
<?php 
if (isset($_SESSION{"username"})) {
    echo "Ætlaru að skrá þig inn aftur?";
} else 
?> 
                    <p>Settu inn netfangið sem að þú notar til þess að skrá þig inn og við skulum senda þér nýtt lykilorð!</p>

                    <p>
                        
                        <input placeholder="Netfangið þitt" type="text" name="email" class="input" />
                    </p>  

                    <p>
                        <input type="submit" class="button" value="Fá sent nýtt lykilorð" name="submit" />
                    </p>
              
            </form>
        </div>     
        <br/><br/>
        <div id="splash" style="background-image: url('../images/osx_white.jpg'); background-repeat: no-repeat; background-position: 50% 0%; margin-bottom: -50px;"></div>
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