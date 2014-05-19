<?php
ob_start();
session_start();

include 'storescripts/connect_to_mysql.php';
?>

<?php
//ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 'On');

?>

<?php
if (isset($_SESSION['testerid'])){
$testerid = $_SESSION['testerid'];

$sql = mysql_query("SELECT * FROM testers WHERE id='$testerid' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
        $productCount = mysql_num_rows($sql);
        if($productCount>0){
            //NÁ Í ALLAR UPPLÝSINGAR UM VÖRUNA OG VERA MEÐ ÞÆR TIL STAÐAR
            while ($row=mysql_fetch_array($sql)) {
            $name = $row{"name"};
            $address = $row{"address"};
            $email = $row{"email"};
            $gender = $row{"gender"};
            }
        } 
}

if(isset($_POST['username'])) {
    $phone = mysql_real_escape_string($_POST['phone']);
    $username = mysql_real_escape_string($_POST['username']);
    $password = "1f68f892910c0073f821b3244d4a4658d9f87696";
    $sql = mysql_query("UPDATE testers SET username = '$username', password = '$password', phone = '$phone', date_joined = now()  WHERE id = '$testerid';")or die(mysql_error());
//send email
    /*
          $to = $email;

          $subject = "Takk fyrir að sækja um prufuaðgang!";

          $headers = "From: nomo@nomo.is";
          $headers .= "Reply-To: nomo@nomo.is";
          $headers .= "MIME-Version: 1.0\r\n";
          $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

          $message = '<html>
                <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                </head>
              <body onload="process()">';
          $message .= '<h1 style="text-align:center; font-size: 25px; color:#007F55;">NOMO</h1>';
          $message .= '<h1 style="text-align:center;">'.$name.', þú ert frábær!</h1>';
          $message .= '<h4 style="text-align:center;">Hér eru þær upplýsingar sem þú skráðir hjá okkur! Við munum svo láta þig vita seinna ef við völdum þig til þess að taka þátt í prufuprógraminu! </h4>';
          $message .= '<table rules="all" style="border-color: #888; margin:0px auto;" cellpadding="10">';
          $message .= "<tr style='background: #eee;'><td><strong>Nafn:</strong> </td><td>" . $name . "</td></tr>";
          $message .= "<tr><td><strong>Netfang:</strong> </td><td>" . $email . "</td></tr>";
          $message .= "<tr><td><strong>Heimilsfang:</strong> </td><td>" . $address . "</td></tr>";
          $message .= "<tr><td><strong>Sími:</strong> </td><td>" . $phone . "</td></tr>";
          $message .= "<tr><td><strong>Notendanafn:</strong> </td><td>" . $username . "</td></tr>";
          $message .= "<tr><td><strong>Lykilorð:</strong> </td><td>" . $password . "</td></tr>";
          $message .= "</table>";
          $message .= '<p>(Af öryggisástæðum geymum við ekki lykilorðið þitt, það er bara birt hér svo þú gleymir því ekki þegar þú ætlar að skrá þig inn!)</p>';
          $message .= "</body></html>";

          mail($to, $subject, $message, $headers);
          */
    header('Location: takk');
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
  position:relative;"> 
 
        <div id ="tester-creation">
            <form method="post" action="" onsubmit="return validateForm()" name="createaccount" >
               
                    <h3 style="font-size: 30px;"> Seinasta skrefið!<br/> <hr> </h3> <p class="max-width">Þú þarft <strong>notendanafn</strong> til þess að geta skráð þig inn <br/> og við þurfum <strong>símanúmer</strong> til þess að geta hringt í þig og fengið þitt álit!</p>
                    <p>
                        <input type="text" name="phone" class="input" placeholder="Símanúmer" maxlength="7" size="7" />
                    </p>
                    <p>
                        <input type="text" name="username" class="input" placeholder="Notendanafn" />
                    </p>  
                    <p>
                        
                        <input type="submit" class="button margin" value="Komið!" name="submit" />
                    </p>
              
            </form>
<?php
    if(isset($response)){ 
        echo "<h4>" . $response . "</h4>";  
      }
?>
        </div> 

        
    </div>

</div>

<script type="text/javascript">
function validateForm()
{
var x=document.forms["createaccount"]["username"].value;
if (x==null || x=="")
  {
  alert("Það vantar notendanafn");
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