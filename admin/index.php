<?php
ob_start();
session_start('');

if (!isset($_COOKIE{"manager"})) {
    header("location:admin-login.php"); 
    exit();
}
//Be sure to check if the SESSION details are in fact in the database.
$managerID = preg_replace('#{0-9}#i', '', $_COOKIE{"aid"});
$manager = preg_replace('#{^A-Za-z0-9}#i', '', $_COOKIE{"manager"});
$password = preg_replace('#{^A-Za-z0-9}#i', '', $_COOKIE{"password"});



//CONNECT TO THE DATABASE
include '../templates/storescripts/connect_to_mysql.php';
$sql = mysql_query("SELECT * FROM admin WHERE id='$managerID' AND username='$manager' AND password='$password' LIMIT 1");

//BE SURE THAT THE PERSON EXCISTS IN THE DATABASE
$exist_count = mysql_num_rows($sql); //count the rows in $sql
if ($exist_count==0) {
    header("location:admin-login.php");
    exit();
}

?>

<?php 
// RUN A SELECT QUERY TO DISPLAY MY PRODUCTS ON MY DYNAMIC LIST

$blog_display = "";
$sql = mysql_query("SELECT * FROM blog ORDER BY id DESC LIMIT 20"); //SELECT * ÞÝÐIR SELECT ALL
$blogCount = mysql_num_rows($sql);
if($blogCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $id = $row{"id"};
        $post = $row{"post"};
        $post_title = $row{"post_title"};
        $author = $row{"author"};
        $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
        $blog_display .= '
    <div class="blog_posts">
        <h3> ' . $post_title . '</h3>
        <p style="max-width: 800px; margin: 0px auto;"> ' . $post . '</p>
        <h5> ' . $author . '</h5>
    </div>  ';
    } 
} else {
    $blog_display = "Velkomin í Admin-kerfið!";
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
    <h1 style="margin-top:30px;">Fylgstu með!</h1>
    <p> Lestu það sem liggur okkur mest á hjarta. <br/> Hver veit nema þú lærir eitt og annað í leiðinni. </p>
     <?php echo $blog_display; ?>       

</div>






</body>
</html>
