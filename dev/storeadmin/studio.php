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

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Admin</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />

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
<?php include_once("../includes/menu-admin.php"); ?>




<div id="main-admin"> 
  
    <div id="admin-q">
        <h3> MyndaStúdíóið</h3>
        <p> Ertu að fá nýja sendingu og viltu auka sölu með því að setja hluta sendingarinnar inn á ValKvíða? <br/> Fylltu út formið hér að neðan og við látum þig vita <strong>samdægurs</strong> hvenær við getum komið þeim fyrir í myndverinu. </p>
  	<br/>
  	<?php
        if (isset($_POST['email']))
        //if "email" is filled out, send email
          {
          //send email
          $email = $_POST['email'] ;
          $subject = $_POST['subject'] ;
          $headers = "From:" . $email;
          $headers .= 'MIME-Version: 1.0\r\n';
          $headers .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';
          $message .= $_POST['message1'] ;
          $message .= $_POST['message2'] ;
          $message .= $_POST['message3'] ;
          mail('kjarrith@gmail.com', $subject, $message, $headers);
          echo "Takk fyrir að hafa samband. <br/> Við munum svara fyrirspurn þinni eins fljótt og hægt er. <br/> <a href='../index.php'>Taktu mig aftur á forsíðuna.</a>";
          }
        else
        //if "email" is not filled out, display the form
          {
          echo "<form method='post' action='footer_samband.php'>

          <h1 class='margin'> Pantaðu tíma í Stúdíóinu. </h1>

          Netfang: <br/> <input name='email' type='text' class='input margin' placeholder='Netfang'>

<input name='subject' type='hidden' class='input margin' placeholder=' $store '><br>

          Fjöldi nýrra vara:<br>

          <textarea name='message1' rows='1' cols='3' class='input'>
          </textarea><br>

          Sérstakar kröfur:<br>

          <textarea name='message2' rows='5' cols='30' >
          </textarea><br>

          <input type='submit' value='Senda' class='button'>
          </form>";
          }
        ?>
    </div>    


            

</div>






</body>
</html>
