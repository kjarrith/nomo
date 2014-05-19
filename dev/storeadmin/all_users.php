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
//SPURNING TIL STJÓRNANDA UM HVORT EYÐA EIGI Notanda OG EYÐA VÖRU EF ÞAÐ ER VALIÐ
if(isset($_POST{'user_to_delete'})) {
    $item_to_delete = $_POST{'user_to_delete'};
    $sql = mysql_query("DELETE FROM users WHERE id = '$item_to_delete' LIMIT 1") or die(mysql_error());
        header("location: all_users.php");
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
$count_sql = mysql_query("SELECT * FROM users"); //SELECT * ÞÝÐIR SELECT ALL
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

    $sql2 = mysql_query("SELECT * FROM users"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql2);

    $sql = mysql_query("SELECT * FROM users ORDER BY id DESC $limit"); //SELECT * ÞÝÐIR SELECT ALL
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $name = $row{"name"};
            $address = $row{"address"};
            $postal_code = $row['postal_code'];
            $email = $row{"email"};
            $curr_discount = $row["discount"];
            $gender = $row{"gender"};
            $phone = $row{"phone"};
            $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
            $user_list .='<tr>';
            $user_list .='<td>' . $name . '</td>';
            $user_list .='<td> ' . $postal_code .'</td>';
            $user_list .='<td> ' . $address .'</td>';
            $user_list .='<td>'. $email . '</td>';
            $user_list .='<td>' . $gender . '</td>';
            $user_list .='<td> <form action="" enctype="multipart/form-data" name="edit" method="post">
            <input name="discount" class="input align_left"  type="text" value="' . $curr_discount . '" size="3" > 
            <input name="button" class="button" type="submit" value="Breyta"/> 
            <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
          </form></td>';
            $user_list .='<td>' . $phone . '</td>';
            $user_list .='<td>' . $date_added . '</td>';
            $user_list .='<td>            <form action ="all_users.php" method="post"> 
    <input name="acceptbtn" type="submit" class="button" Value="X" style="font-size:12px;"/> 
    <input name="user_to_delete" type="hidden" value="' . $id . '"/>   
            </form> 
            <a class="button" href="product_edit.php?id='.$id.'">?</a> </td>';
            $user_list .='</tr>';
        } 
    } else {
        $user_list = "Það eru engar vörur í búðinni";
    } 

//Printing out [STORE_list] AS ADMIN ---------------------------

}

//FINNA UPPLÝSINGAR UM NOTENDUR
    $boysql = mysql_query("SELECT * FROM users WHERE gender = 'karl'"); //SELECT * ÞÝÐIR SELECT ALL
    $BoyCount = mysql_num_rows($boysql);
    $girlsql = mysql_query("SELECT * FROM users WHERE gender = 'kona'"); //SELECT * ÞÝÐIR SELECT ALL
    $GirlCount = mysql_num_rows($girlsql);
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
  <h1 style="margin-top:30px;">Leitaðu af notenda</h1>
      <input type="text" onkeyup="getSearch(this.value)" class="input"/>
        <div id="admin-container" style="width:98%; margin:0px auto;">

            <?php
echo 'Fjöldi skráðra notanda: <strong>'. $productCount. '</strong><br/>
Fjöldi stelpna: <strong>'.$GirlCount.'</strong> Fjöldi stráka: <strong>'.$BoyCount.'</strong>
';
?>
                    <br/> <br/>
                <?php echo $pagination; ?>
                <br/>
    <table class="cart_table" id="results"  style="width:100%;">
                   <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                    <td width="18%">Nafn <br/></td>
                    <td width="10%">Póstnúmer</td>
                    <td width="10%">Heimilisfang</td>
                    <td width="15%">E-Mail</td>
                    <td width="8%">Kyn</td>
                    <td width="8%">Afsláttur</td>
                    <td width="10%">Sími</td>
                    <td width="9%">Bætt við</td>
                    <td width="2%">Breyta</td>
                    </tr>
                    <?php echo $user_list;?>
    </table>
        </div>    






</body>
</html>
