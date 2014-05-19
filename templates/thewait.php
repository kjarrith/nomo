<?php
global $cssVersion;
ob_start();
header('Location: http://www.nomo.is/');
exit();
?>

<?php

     $now = time(); // or your date as well
     $your_date = strtotime("2014-02-10");
     $datediff = $now - $your_date;
     $datediff = - $datediff;
     $opentime = ceil($datediff/(60*60*24));

?>

<?php
//ÞÁTTA UPPLÝSINGAR FRÁ FORMINU

if(isset($_POST{'name'})) {
    $name = mysql_real_escape_string($_POST['name']);
    $email = mysql_real_escape_string($_POST['email']);
    $gender = mysql_real_escape_string($_POST['gender']);
    $postal_code = mysql_real_escape_string($_POST['postal_code']);    
    $address = mysql_real_escape_string($_POST['address']);    
    $age1 = mysql_real_escape_string($_POST['age1']);
    $age2 = mysql_real_escape_string($_POST['age2']);
    $age3 = mysql_real_escape_string($_POST['age3']);
    $birthdate = $age3.$age2.$age1;
    $password = "1f68f892910c0073f821b3244d4a4658d9f87696";

    $today = date("Y-m-d H:i:s"); 

    $takk = "Takk";

  $sql = mysql_query("SELECT * FROM users WHERE email='$email' LIMIT 1");
  //BE SURE THAT THE PERSON EXCISTS IN THE DATABASE
  $exist_count = mysql_num_rows($sql); //count the rows in $sql

      if ($exist_count==0) {
                  //ADD [USER] TO DATABASE
              $sql = mysql_query("INSERT INTO users (name, email, gender, birthdate, date_added, password, address, postal_code) VALUES('$name', '$email','$gender', '$birthdate','$today','$password','$address','$postal_code') ")or die(mysql_error());
              $testerid = mysql_insert_id();

          //SEND CONFIRMATION EMAIL
          $subject = "Velkomin til NOMO";

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
        $response = "<p style='color:#fff;'>Takk fyrir þetta! <br/> <br/> Við sendum tölvupóst á netfangið ".$email." sem þú getur núna kíkt á! <br/><br/> Sjáumst 10. Febrúar.</p>";
      } else {
        $response = "<p style='color:#fff;'>Við erum með notanda skráðan með þessu netfangi. Gæti það kannski verið þú? <br/><br/> Ef ekki, prufaðu þá aftur.</p>";
      }

}
?>

<!DOCTYPE html>

<head>

<script type="text/javascript"src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, nomo, miðpunktur, tísku, tíska, netverslun, ">

<link href="http://www.nomo.is/images/opengraphimg.jpg" rel="image_src"/>
<meta property="og:title" content="Nomo - Öll flottustu fötin." >
<meta property="og:image" content="http://www.nomo.is/images/opengraphimg.jpg" />
<meta property="og:url" content="http://www.nomo.is/velkomin" />

<title>Nomo - Fötin á netinu</title>

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

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=234032696651711";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<body>

<div id="lower" style="overflow:auto;">
  <a name="lower"></a>
        <div class="absolute-center" style="margin-top: 50px;">
           <form method="post" action="opnun#lower" onsubmit="return validateForm()" name="createaccount" >
                <?php if(isset($response))  {
                  echo $response;
                } else {
                  echo '<h3 style="font-size: 30px; color: #fff;"> Fylgstu með<br/> </h3>
                    <p style="color:#fff;">Viltu fá að vita hvernig ferlið gengur?<br/> Viltu fá <strong>10% afslátt</strong> af öllu þegar við opnum? <br/><br/> Skráðu þig þá í klúbbinn, og við höldum þér upplýstum.</p>
';
                }?>
                    <p>
                        <input type="text" name="name" class="input" placeholder="Fullt Nafn" />
                    </p>  

                    <p>
                        <input type="email" name="email" class="input" placeholder="Netfang" />
                    </p>
                    <p>
                        <input type="text" name="postal_code"  class="input" placeholder="Póstnr." size="3" maxlength="3"/>
                        <input type="text" name="address"  class="input" placeholder="Heimilisfang" size="10" />
                    </p>
                    <p style="color:#fff;">
                        <label for="gender">Ég er:</label> <br/>
                        <input type="radio" name="gender" value="karl">  Strákur
                        <input type="radio" name="gender" value="kona">  Stelpa
                    </p>
                        <span style="color:#fff;">Ég fæddist:</span> <br/>
                        <input type="text" name="age1"  class="input" placeholder="DD" size="2" maxlength="2" />
                        <input type="text" name="age2"  class="input" placeholder="MM" size="2" maxlength="2"/>
                        <input type="text" name="age3"  class="input" placeholder="YYYY" size="4" maxlength="4"/>
                    <p>
                        <input type="submit" class="button margin" value="Komið!" name="submit" />
                    </p>
              
            </form>
        </div>
</div>

<div id="upper" style="background-image: url('../images/fullscreen/nomohome-4.jpg');">
          <div class="fb-like" data-href="https://www.facebook.com/pages/Nomo/576038825777229" data-width="450" data-layout="button_count" data-show-faces="true" data-send="true"></div>
    <div class="absolute-center" style="margin-top: 80px;">
      <?php 
          echo '
        <p style="margin-bottom: -55px;">NOMO opnar klukkan:</p> <br/>
        <span style="font-size: 15em;">18:00</span> <br/>
        <p style="margin-top: -35px;">Vertu með.</p>
          ';
      ?>
         <p style="line-height: 24px; color: #333;">
        Á www.nomo.is muntu geta skoðað og keypt <br/>föt frá öllum flottustu fataverslunum á Íslandi.<br/> <br/>
        Og já, það er frí heimsending.
        </p>
        <br/><br/>
    </div>
          <div id="ScrollDown" style="text-align: left;">
          <img id="popup" src="/images/icons/arrow.png" style="width:50px; ">
            <span style="position: relative; top: -68px; left:50px; text-align: left;">
            <p>Hey, Psst! <br/> Prufaðu að skrolla niður.</p>
          </span>
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
<script>
  $(function(){
      $('#lower').animate({marginTop: '-150px' }, 2000);
      $('#lower').animate({marginTop: '0px' }, 1000);
  });
</script>

</body>
</html>