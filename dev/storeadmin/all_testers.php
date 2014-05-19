<?php
//STARTING A CONNECTION TO THE DATABASE
ob_start();
session_start();
include '../templates/storescripts/connect_to_mysql.php';

if (
    !isset($_COOKIE{"owner"})) {
    header("location:../index.php");
    exit();
}
//Be sure to check if the SESSION details are in fact in the database.
$managerID = preg_replace('#{0-9}#i','', $_COOKIE{"oid"});
$manager = preg_replace('#{^A-Za-z0-9}#i', '', $_COOKIE{"owner"});

//CONNECT TO THE DATABASE
$sql = mysql_query("SELECT * FROM admin WHERE id='$managerID' AND username='admin' LIMIT 1");

//BE SURE THAT THE PERSON EXCISTS IN THE DATABASE
$exist_count = mysql_num_rows($sql); //count the rows in $sql
if ($exist_count==0) {
    echo "Upplýsingar þínar eru ekki í gagnagrunninum okkar";
    exit();
}

?>

<?php
//SPURNING TIL STJÓRNANDA UM HVORT EYÐA EIGI Notanda OG EYÐA VÖRU EF ÞAÐ ER VALIÐ
if(isset($_POST{'item_to_delete'})) {
    $item_to_delete = $_POST{'item_to_delete'};
    $sql = mysql_query("DELETE FROM testers WHERE id = '$item_to_delete' LIMIT 1") or die(mysql_error());
        header("location: all_testers.php");
    exit();
}
?>

<?php
//SAMÞYKKJA NOTANDA, BÆTA HONUM INN Í USERS OG EYÐA FRÁ TESTERS

if(isset($_POST['item_to_accept'])) {
    $accept_id = $_POST['item_to_accept'];
    $accept_name = $_POST['item_name'];
    $accept_address = $_POST['item_address'];
    $accept_email = $_POST['item_email'];
    $accept_username = $_POST['item_username'];
    $accept_gender = $_POST['item_gender'];
    $accept_phone = $_POST['item_phone'];
    $accept_birthdate = $_POST['item_birthdate'];
    $accept_password = $_POST['item_password'];
//BÆTA INN Í USERS
    $sql = mysql_query("INSERT INTO users (username, password, email, address, phone, gender, name, date_added, birthdate)
                        VALUES('$accept_username', '$accept_password', '$accept_email', '$accept_address', '$accept_phone', '$accept_gender', '$accept_name', now(), '$accept_birthdate')
                        ")or die(mysql_error());
// EYÐA ÚR TESTERS
    $sql = mysql_query("DELETE FROM testers WHERE id = '$accept_id' LIMIT 1") or die(mysql_error());
// SENDA MAILIÐ
    /*
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
                /* All your usual CSS here 
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
                      <h3 style="margin: 0px auto; font-size:25px;">Til hamingju <span style="color:#444;">'.$accept_name.'</span>!</h3> 
                      <br/>
                      <h3 style="margin: 0px auto; color:#444;">Við höfum samþykkt þig sem prufunotanda!</h3>
                      <br/>
                      <h3 style="margin: 0px auto; color:#222;">Það þýðir að þú getur notað <span style="color:#888;">eftirfarandi upplýsingar</span> til þess að skrá þig inn á www.nomo.is!</h3>
                    <hr/>
                  </td>
              </tr>
              <tr>
                  <td align="center">
                      <table style="border-color: #666; width:30%; border-collapse:collapse;" cellpadding="10" align="center">
                      <tr style="background: #eee;border:0px solid #fff; border-radius:10px;">
                        <td><strong>Notendanafn:</strong> </td>
                        <td>'.$accept_username.'</td>
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
mail($accept_email, $subject, $message, $headers);
*/
// MAIL KLÁRAST
    header("Location: all_testers.php");
    exit();
}
?>

<?php

if ($manager == "admin") {
// PRINTING OUT the users
    $user_list = "";
    $sql = mysql_query("SELECT * FROM testers ORDER BY id DESC"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql);
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $username = $row{"username"};
            $password = $row{"password"};
            $name = $row{"name"};
            $address = $row{"address"};
            $email = $row{"email"};
            $gender = $row{"gender"};
            $phone = $row{"phone"};
            $birthdate = $row{"birthdate"};
         //explode the date to get month, day and year
         $birthdate = explode("-", $birthdate);
         //get age from date or birthdate
         $age = (date("md", date("U", mktime(0, 0, 0, $birthdate[0], $birthdate[1], $birthdate[2]))) > date("md") ? ((date("Y")-$birthdate[0])-1):(date("Y")-$birthdate[0]));
            $date_added = strftime("%d %b %y", strtotime($row{"date_joined"}));
            $user_list .='<tr>';
            $user_list .='<td>' . $id . '</td>';
            $user_list .='<td>' . $name . '</td>';
            $user_list .='<td>' . $username . '</td>';
            $user_list .='<td> ' . $address .'
            </td>';
            $user_list .='<td>'. $email . '</td>';
            $user_list .='<td>' . $gender . '</td>';
            $user_list .='<td>' . $phone . '</td>';
            $user_list .='<td>' . $age . ' ára</td>';
            $user_list .='<td> 
            <form action ="all_testers.php" method="post"> 
    <input name="acceptbtn" type="submit" class="button" Value="X" style="font-size:12px;"/> 
    <input name="item_to_delete" type="hidden" value="' . $id . '"/>   
            </form>

            <a class="button" href="product_edit.php?id=$id .">?</a> 

            <form action ="" method="post"> 
    <input name="acceptbtn" type="submit" class="button" Value="Samþykkja" style="font-size:12px;"/> 
    <input name="item_to_accept" type="hidden" value="' . $id . '"/>
    <input name="item_name" type="hidden" value="' . $name . '"/> 
    <input name="item_username" type="hidden" value="' . $username . '"/> 
    <input name="item_address" type="hidden" value="' . $address . '"/> 
    <input name="item_email" type="hidden" value="' . $email . '"/> 
    <input name="item_gender" type="hidden" value="' . $gender . '"/>
    <input name="item_phone" type="hidden" value="' . $phone . '"/>  
    <input name="item_birthdate" type="hidden" value="' . $birthdate . '"/>   
    <input name="item_password" type="hidden" value="1f68f892910c0073f821b3244d4a4658d9f87696"/>    
            </form>       
            </td>' ;
            $user_list .='</tr>';
        } 
    } else {
        $user_list = "Það eru engar vörur í búðinni";
    } 

//Printing out [STORE_list] AS ADMIN ---------------------------

}
?>

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Prufarar</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>

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

<?php include_once("../includes/header_admin.php"); ?>
<?php include_once("../includes/menu-owner.php"); ?>

<div id="main-admin"> 
<h1 style="margin-top:30px;">Prufarar</h1>
        <div id="admin-container">
            <?php
echo 'Fjöldi skráðra prufara: <strong>'. $productCount. '</strong>';
?>

    <table class="cart_table">
                <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                    <td width="5%">id</td>
                    <td width="18%">Nafn <br/></td>
                    <td width="18%">Notendanafn <br/></td>
                    <td width="10%">Heimilisfang</td>
                    <td width="15%">E-Mail</td>
                    <td width="8%">Kyn</td>
                    <td width="10%">Sími</td>
                    <td width="9%">Aldur</td>
                    <td width="9%">Breyta</td>
                </tr>
                    <?php echo $user_list; ?>
    </table>

        </div>    






</body>
</html>