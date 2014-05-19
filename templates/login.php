<?php
global $cssVersion;
global $faviconVersion;
ob_start();
session_start();

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
        $_SESSION{"uid"} = $id;
        header("Location: /home");
        exit();
    } else {
        $response = 'Notendanafnið og lykilorðið smullu ekki saman. <a href="login.php"> Reyndu aftur. </a> <br/><br/> <p style="color:#555;"> (Ertu kannski ekki með í prufutímabilinu?) <br/> <a href="create_tester.php">Sæktu um prufuaðgang</a></p> ';
    }
}
?>
<!DOCTYPE html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, nomo, miðpunktur, tísku, tíska, netverslun, ">

<link href="http://www.nomo.is/images/opengraphimg.jpg" rel="image_src"/>
<meta property="og:title" content="Nomo - Öll flottustu fötin á Íslandi" >
<meta property="og:image" content="http://www.nomo.is/images/opengraphimg.jpg" />
<meta property="og:url" content="http://www.nomo.is/velkomin" />

<title>Nomo - Tískumiðjan</title>

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

<div id="main-sans-menu">
        <div id ="login" style="margin-top: 80px; background-color: #fff;">
            <div id ="top_part">
            <form method="post" action="">
<?php 
if (isset($_SESSION{"username"})) {
    echo "Ætlaru að skrá þig inn aftur?";
} else 
?> 
                    <h1 style="font-size:14px;"> -Við erum á prufutímabili-</h1>
                    <br/>
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
<?php
    if(isset($response)) {
         echo "<h4>" . $response . "</h4>";
     }
?>  
            <img src="../images/nomologo80.png" style="width:40px; position: relative; top: 23px; z-index: 30; ">
        </div>
        <div id="bottom_part">

<p>Við erum að leita af fólki sem <strong>elskar</strong> föt til þess að taka þátt í <strong>prufukeyrslu</strong> <em>Nomo</em>.</p>
<p>Langar þig vera einn af þeim sem hjálpa til við að skapa <Strong>framtíð íslensks fatamarkaðs?</strong></p>
<a href="tester-1"><div class="button action" style="background-color:; margin:20px auto; width:270px; font-size:22px;">Sæktu um prufuaðgang!</div></a>
<hr/>

            <h3 style="padding:0; font-size: 30px; color:#444;"> 
        Hvað er Nomo?
    </h3>
    <p><em>Nomo</em> verður samansafn íslenskra tískuverslana ... á netinu. <br/> <em>Þetta</em> verður miðpunktur íslenskrar tísku. </p>
            <ul id="three_steps">
                <li>
                    <div class="wiv" style="background-image: url('../images/rack.jpg')"><div class="wiv-text"><h1 style="font-weight: bold; color:#fff; margin-top:20px;"> Skoðaðu föt frá öllum verslunum á Íslandi</h1></div></div>
                </li>
                <li>
                    <div class="wiv" style="background-image: url('../images/pay.jpg')"> <div class="wiv-text"><h1 style="font-weight: bold; color:#222; margin-top:20px;"> Kauptu bara það sem þú <em style="color:#E60042">elskar</em></h1></div></div>
                </li>
                <li>
                    <div class="wiv" style="background-image: url('../images/shipppiry.jpg')"><div class="wiv-text"><h1 style="font-weight: bold; color:#fff; margin-top:20px;"> ... og fáðu það sent heim</h1></div></div>
                </li>
            </ul>
        </div> 
   </div>
         <div id="splash" style="background-image: url('../images/osx_white.jpg'); background-repeat: no-repeat; background-position: 50% 0%;"></div>

   

</div>



</body>
</html>