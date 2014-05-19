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

//ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>


<?php
    $user_list = "";
if ($manager == "admin") {
// PRINTING OUT the users

    $sql = mysql_query("SELECT * FROM stores ORDER BY id DESC "); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql);
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $store_name = $row{"store_name"};
            $user_list .='<a href="payup.php?id='.$id.'">' . $store_name . '</a> - ';
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
<title>UserList</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>

  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41790272-2', 'nomo.is');
  ga('send', 'pageview');

</script>

<script type="text/javascript">
  function getSearch(value) {
      $.post("closerlook.php",{search:value}, function(data){
        $("#results").html(data);
      });
  }
</script>

</head>

<body >

<?php include_once("../includes/header_admin.php"); ?>
<?php include_once("../includes/menu-owner.php"); ?>

<div id="main-admin"> 
  <h1 style="margin-top:30px;">Verslanir</h1>

    <table class="cart_table" id="results"  style="width:100px; margin:0px auto;">
                   <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                    <td width="18%">Verzlun <br/></td>
                    </tr>
                    <?php echo $user_list;?>
    </table>
        </div>    






</body>
</html>