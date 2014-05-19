<?php
//STARTING A CONNECTION TO THE DATABASE
ob_start();
session_start();
include '../templates/storescripts/connect_to_mysql.php';


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
//BÆTA VIÐ ÚTSENDARA

if(isset($_POST{'new_affiliate'})) {
      $new_affiliate = mysql_real_escape_string($_POST{'new_affiliate'});
      $new_phone = mysql_real_escape_string($_POST{'new_phone'});
      $new_code = mysql_real_escape_string($_POST{'new_code'});
  //ADD [affiliate] TO DATABASE
      $sql = mysql_query("INSERT INTO affiliates (affiliate, phone, code) VALUES('$new_affiliate', '$new_phone', '$new_code') ")or die(mysql_error());
      header("location: affiliates.php");
      exit();
}
?>

<?php
//BREYTA PRICE
if(isset($_POST{'discount'})) {
$discount = preg_replace("#[^0-9]#","", $_POST{'discount'});
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE users SET discount = '$discount' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:all_users.php");
  exit();
}
?>

<?php
$pagination ="";
// PAGINATION STARTS
//Counting items in database
$count_sql = mysql_query("SELECT * FROM affiliates"); //SELECT * ÞÝÐIR SELECT ALL
$count = mysql_num_rows($count_sql);

// Replacing everything from the GET variable except numbers
if(isset($_GET['page'])){
  $page = preg_replace("#[^0-9]#","", $_GET['page']);
} else {
  $page = 1;
}
//NUMBER OF ITEMS DISPLAYED
$perPage = 20;
$pages = ceil($count/$perPage);
//IF USER FUCKS WITH THE GET VARIABLE
if ($page < 1){
  $page = 1;
} else if($page > $pages) {
  $page = $pages;
}

//Creating the limit in the query
$limit = "LIMIT " . ($page - 1) * $perPage . ", $perPage";

if($pages !=1) {

  if($page != 1){
    $prev = $page - 1;
    $pagination .= '<a href="all_users.php?page='.$prev.'"> < </a>';
  }else {
    $pagination .= '<';
  }
  if($pages > 1) {
    for($i = 1; $i <= $pages; $i++) {
            $active ="";
        if($i == $page){
          $active .= "active";
        }
        $pagination .= '<a href="all_users.php?page='.$i.'" class="'.$active.'"> '.$i.' </a>';
    }
  }
  if($page != $pages){
    $next = $page + 1;
    $pagination .= '<a href="all_users.php?page='.$next.'"> > </a>';
  } else {
    $pagination .= '>';
  }


}


if ($manager == "admin") {
// PRINTING OUT the users
    $user_list = "";

    $sql2 = mysql_query("SELECT * FROM affiliates"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql2);

    $sql = mysql_query("SELECT * FROM affiliates ORDER BY id DESC $limit"); //SELECT * ÞÝÐIR SELECT ALL
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $affiliate = $row{"affiliate"};
            $phone = $row{"phone"};
            $code = $row['code'];
            $used = $row['used'];
            $user_list .='<tr>';
            $user_list .='<td>' . $affiliate . '</td>';
            $user_list .='<td> S: ' . $phone .'</td>';
            $user_list .='<td> ' . $code .'</td>';
            $user_list .='<td>'. $used . ' sinnum</td>';
            $user_list .='</tr>';
        } 
    } else {
        $user_list = "Það eru engir útsendarar til";
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
<title>Útsendarar</title>

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
  <h1>Útsendarar</h1>
        <div id="admin-container" style="width:98%; margin:0px auto;">
              <table class="cart_table">
                <form method="post" action="" onsubmit="return validateForm()" >
                    <h3>Bæta við útsendara</h3>
                    <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                        <td width="18%"> 
                            <input type="text" name="new_affiliate" class="input" placeholder="Nafn" />
                        </td>
                        <td width="10%">
                            <input type="text" name="new_phone"  class="input" placeholder="Símanúmer"/>
                        </td>
                        <td width="10%">
                            <input type="text" name="new_code" class="input" placeholder="Kóði" />
                        </td>
                        <td width="10%">
                          <input type="submit" class="button action" value="Skrá">                      
                        </td>
                    </tr>
                </form>
    </table>
                    <br/> <br/>
                <?php echo $pagination; ?>
                <br/>
    <table class="cart_table" id="results"  style="width:100%;">
                   <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                    <td width="18%">Nafn <br/></td>
                    <td width="10%">Sími</td>
                    <td width="10%">Kóði</td>
                    <td width="15%">Notað:</td>
                    </tr>
                    <?php echo $user_list;?>
    </table>
        </div>    






</body>
</html>
