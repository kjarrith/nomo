<?php

ob_start();
session_start();
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
//ÞÁTTA UPPLÝSINGAR FRÁ FORMINU

if(isset($_POST{'name'})) {
    $name = mysql_real_escape_string($_POST['name']);
    $email = mysql_real_escape_string($_POST['email']);
    $address = mysql_real_escape_string($_POST['address']);
    $gender = mysql_real_escape_string($_POST['gender']);
    $age1 = mysql_real_escape_string($_POST['age1']);
    $age2 = mysql_real_escape_string($_POST['age2']);
    $age3 = mysql_real_escape_string($_POST['age3']);
    $birthdate = $age3.$age2.$age1;

//ADD [USER] TO DATABASE
    $sql = mysql_query("INSERT INTO testers (name, email, address, gender, birthdate) VALUES('$name', '$email', '$address', '$gender', '$birthdate') ")or die(mysql_error());
    $testerid = mysql_insert_id();
    $_SESSION['testerid'] = $testerid;
header('Location: tester-2');
exit();
}
?>
<!DOCTYPE html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Búðu til aðgang!</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />

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
  position:relative;
  "> 
 
        <div id ="tester-creation" >
            <form method="post" action="" onsubmit="return validateForm()" name="createaccount" >
               
                    <h3 style="font-size: 30px;"> Vertu með í prufunni! <br/> <hr> </h3> <p style="line-height: 24px;">Til þess að við getum fengið þitt álit, þurfum við að vita eitthvað um þig! <br/> Segðu okkur frá þér!</p>

                    <p>
                        <input type="text" name="name" class="input" placeholder="Fullt Nafn" />
                    </p>  

                    <p>
                        <input type="text" name="email" class="input" placeholder="Netfang" />
                    </p>  

                    <p>
                        <input type="text" name="address"  class="input" placeholder="Heimilisfang"/>
                    </p>

                    <p>
                        <label for="gender">Ég er:</label> <br/>
                        <input type="radio" name="gender" value="karl">  Strákur<br/>
                        <input type="radio" name="gender" value="kona">  Stelpa
                    </p>
                        Ég fæddist:
                        <input type="text" name="age1"  class="input" placeholder="DD" size="2" maxlength="2" />
                        <input type="text" name="age2"  class="input" placeholder="MM" size="2" maxlength="2"/>
                        <input type="text" name="age3"  class="input" placeholder="YYYY" size="4" maxlength="4"/>
                    <p>
                        <input type="submit" class="button margin" value="Komið!" name="submit" />
                    </p>
              
            </form>

<?php
    if (isset($response)) 
        echo "<h4>" . $response . "</h4>";
    
?>

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
}

function validateForm()
{
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