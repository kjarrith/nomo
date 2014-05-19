<?php
include '../templates/storescripts/connect_to_mysql.php';

echo 'Takk fyrir að skrá þig í klúbbinn';

   	$name = mysql_real_escape_string($_POST['name']);
    $email = mysql_real_escape_string($_POST['email']);
    $gender = mysql_real_escape_string($_POST['gender']);
    $age1 = mysql_real_escape_string($_POST['age1']);
    $age2 = mysql_real_escape_string($_POST['age2']);
    $age3 = mysql_real_escape_string($_POST['age3']);
    $birthdate = $age3.$age2.$age1;
    $password = "1f68f892910c0073f821b3244d4a4658d9f87696";

    $today = date("Y-m-d H:i:s"); 

//ADD [USER] TO DATABASE
    $sql = mysql_query("INSERT INTO users (name, email, gender, birthdate, date_added, password) VALUES('$name', '$email','$gender', '$birthdate','$today','$password') ")or die(mysql_error());
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

}

?>