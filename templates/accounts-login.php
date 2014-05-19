<?php
ob_start();
session_start();
global $cssVersion;
global $faviconVersion;

if(isset($_POST["email"]) && isset($_POST["password"])) {
    //FILTERING
    $user = mysql_real_escape_string($_POST["email"]);
    $password = $_POST['password'];
    $password = sha1($password);
    //CONNECT TO DATABASE
    $sql = mysql_query("SELECT * 
                        FROM users
                        WHERE email='$user' AND password='$password'
                        LIMIT 1 
                        ");
    //MAKE SURE PERSON EXISTS IN DATABASE 
    $exist_count=mysql_num_rows($sql); 
    if($exist_count>0){
        while($row = mysql_fetch_array($sql)){
            $id = $row{'id'};
            $name = $row['name'];
            $address = $row['address'];
            $email = $row['email'];
        }
        //CREATING COOKIES
        $expire=time()+60*60*24*365;
        setcookie("uid", $id, $expire);
        //CREATING SESSIONS
        header("Location: /home");
        exit();
    } else {
        $response = 'Notendanafnið og lykilorðið smullu ekki saman. <a href="login.php"> Reyndu aftur. </a> <br/><br/> <p style="color:#555;"> (Ertu kannski ekki með í prufutímabilinu?) <br/> <a href="create_tester.php">Sæktu um prufuaðgang</a></p> ';
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

<div id="main-sans-menu" style="padding-bottom: 50px;"> 
 
<div id="accounts-wrapper" >

            <form method="post" action="">
<?php 
if (isset($_SESSION{"username"})) {
    echo "Ætlaru að skrá þig inn aftur?";
} else 
?> 
                    <h1> Skráðu þig inn! </h1>

                    <p>
                        
                        <input placeholder="Netfang" type="text" name="email" class="input" />
                    </p>  

                    <p>
                        
                        <input placeholder="Lykilorð" type="password" name="password"  class="input"/>
                    </p>

                    <p>
                        
                        <input type="submit" class="button" value="Staðfesta" name="submit" />
                    </p>
              
            </form>
            <a href="/forgot">Gleymdiru lykilorðinu?</a>
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