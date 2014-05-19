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
//ÞÁTTA UPPLÝSINGAR FRÁ FORMINU

if(isset($_POST{'name'})) {
    $name = mysql_real_escape_string($_POST['name']);
    $gender = mysql_real_escape_string($_POST['gender']);
    $postal_code = mysql_real_escape_string($_POST['postal_code']);    
    $address = mysql_real_escape_string($_POST['address']);    
    $age1 = mysql_real_escape_string($_POST['age1']);
    $age2 = mysql_real_escape_string($_POST['age2']);
    $age3 = mysql_real_escape_string($_POST['age3']);
    $birthdate = $age3.$age2.$age1;

    $today = date("Y-m-d H:i:s"); 

    //ADD [USER] TO DATABASE
    $sql = mysql_query("INSERT INTO users (name, gender, birthdate, date_added, address, postal_code, status) VALUES('$name','$gender', '$birthdate','$today','$address','$postal_code', 0) ")or die(mysql_error());
    $newuserid = mysql_insert_id();    
    $_SESSION['new_user_id'] = $newuserid;

    //INSERT INTO EMAILLIST
    $sql2 = mysql_query("INSERT INTO EmailList (address, gender, name, postal_code)
    VALUES('$address','$gender','$name', '$postal_code')")
    or die(mysql_error()); 
    $newuseremail = mysql_insert_id(); 
    $_SESSION['new_user_email'] = $newuserid;
    //-------

    header("Location: /accounts2");
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
                        <input type="text" name="name" class="input bigger" placeholder="Fullt Nafn" />
                    </p>  
                    <p>
                        <input type="text" name="postal_code"  class="input bigger" placeholder="Póstnr." size="3" maxlength="3"/>
                        <input type="text" name="address"  class="input bigger" placeholder="Heimilisfang" size="10" />
                    </p>

              <div class="radio-toolbar">
                 
                  <input type="radio" id="radio1" name="gender" value="karl" checked>
                  <label for="radio1">Strákur</label>

                  <input type="radio" id="radio2" name="gender" value="kona">
                  <label for="radio2">Stelpa</label>
              </div> 
              <br/>
                        <span style="">Ég fæddist:</span> <br/><br/>
                        <input type="text" name="age1"  class="input bigger" placeholder="DD" size="2" maxlength="2" />
                        <input type="text" name="age2"  class="input bigger" placeholder="MM" size="2" maxlength="2"/>
                        <input type="text" name="age3"  class="input bigger" placeholder="YYYY" size="4" maxlength="4"/>
                    <p>
                        <input type="submit" class="button margin" value="HALDA ÁFRAM" name="submit" style="width:250px;" />
                    </p>
              
            </form>
            <a href="/login">Ertu nú þegar skráður notandi?</a>
        </div>     
        <br/><br/>
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