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
// Post the BLOG post
if(isset($_POST["author"]) && isset($_POST["post"])) {
     //FILTERING
    $author =  mysql_real_escape_string($_POST{"author"});
    $post = mysql_real_escape_string($_POST{"post"});
    $title =  mysql_real_escape_string($_POST{"title"});
    $sql = mysql_query("INSERT INTO blog (post, post_title, author, date_added) VALUES('$post', '$title', '$author', now())")or die(mysql_error());
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
<?php include_once("../includes/menu-owner.php"); ?>




<div id="main-admin">   
    <h1 style="margin-top:30px;">Skrifaðu bloggpóst!</h1>

<form action ="" method="post"> 
    <input type="text" class="input align_left" name="author"  size="15" maxlength="100" placeholder="Nafn"/>
    <br/>
     <input type="text" class="input align_left" name="title"  size="15" maxlength="100" placeholder="Heiti bloggpósts"/>
<br/>
<textarea rows="15" cols="100" name="post">

</textarea>
<br/>
    <input type="submit" class="button" Value="Blogga" style="font-size:12px;"/>  
</form>              
</div>






</body>
</html>
