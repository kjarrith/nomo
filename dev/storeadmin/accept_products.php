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
if(isset($_POST{'item_to_delete'})) {
    $item_to_delete = $_POST{'item_to_delete'};
    $sql = mysql_query("DELETE FROM products WHERE id = '$item_to_delete' LIMIT 1") or die(mysql_error());
        header("location: accept_products.php");
    exit();
}
?>

<?php
//SAMÞYKKJA VÖRU, BÆTA HENNI INN Í PRODUCTS OG EYÐA FRÁ TESTERS

if(isset($_POST['item_to_accept'])) {
    $accept_id = $_POST['item_to_accept'];
         $sql = mysql_query("UPDATE products SET status = '1', date_added = now() WHERE id = '$accept_id';")or die(mysql_error());
        header("location:accept_products.php");
        exit();
}

if(isset($_POST['item_to_decline'])) {
    $decline_id = $_POST['item_to_decline'];
         $sql = mysql_query("UPDATE products SET status = '2' WHERE id = '$decline_id';")or die(mysql_error());
        header('location:owner_edit.php?id=' . $decline_id . '.php');
        exit();
}
?>

<?php
//VERÐBREYTINGAR

//BREYTA STORE_PRICE
if(isset($_POST{'dcount'})) {
$dcount = preg_replace("#[^0-9]#","", $_POST{'dcount'});
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE products SET dcount = '$dcount' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:accept_products.php");
  exit();
}

//BREYTA PRICE
if(isset($_POST{'web_price'})) {
$web_price = preg_replace("#[^0-9]#","", $_POST{'web_price'});
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE products SET price = '$web_price' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:accept_products.php");
  exit();
}

//BREYTA NAFNI
if(isset($_POST{'new_name'})) {
$new_name = $_POST{'new_name'};
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE products SET product_name = '$new_name' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:accept_products.php");
  exit();
}

//BREYTA VÖRUMERKI
if(isset($_POST{'new_description'})) {
$new_description = $_POST{'new_description'};
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE products SET description = '$new_description' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:accept_products.php");
  exit();
}

//BREYTA DETAILS
if(isset($_POST{'new_details'})) {
$new_details = $_POST{'new_details'};
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE products SET details = '$new_details' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:accept_products.php");
  exit();
}

//BREYTA LÝSINGU
if(isset($_POST{'new_trademark'})) {
$new_trademark = $_POST{'new_trademark'};
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE products SET trademark = '$new_trademark' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:accept_products.php");
  exit();
}

//BREYTA Módeli
if(isset($_POST{'model_id'})) {
$model_wears = $_POST['model_wears'];
$model_id = $_POST['model_id'];
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE products SET model_wears = '$model_wears', model_id = '$model_id' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:accept_products.php");
  exit();
}

?>

<?php
//BÚA TIL MÓDEL LISTA
$ModelList = "";
$sql = mysql_query("SELECT * FROM models ORDER BY name ASC"); //SELECT * ÞÝÐIR SELECT ALL
$modelCount = mysql_num_rows($sql);
if($modelCount>0){
   while ($row=mysql_fetch_array($sql)) {
        $Modelid = $row{"id"};
        $ModelName = $row["name"];
        //búa til listann sjálfan
        $ModelList .= '<option value="'.$Modelid.'">'.$ModelName.'</option>';
      }
}

if ($manager == "admin") {
// RUN A SELECT QUERY TO DISPLAY MY PRODUCTS ON MY DYNAMIC LIST

$dynamic_list = "";
$sql = mysql_query("SELECT * FROM products WHERE status = 0 ORDER BY date_added DESC "); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $id = $row{"id"};
        $product_name = $row{"product_name"};
        $price = $row{"price"};
        $x = $row{"dcount"};
        $store = $row{"store"};
        $category = $row{"category"};
        $style_id = $row{"style_id"};
        $details = $row['details'];
        $trademark = $row{"trademark"};
        $subcategory = $row{"subcategory"};
        $special = $row{"special"};
        $description = $row{"description"};
        $model_wears = $row["model_wears"];
        if($model_wears === ""){
          $model_wears = "Stærð";
        }
        if ($x>0){
              $realprice = round((1-($x/100))*$price);
            } else {
              $realprice = $price;
            }
        if (isset($x)&&$x>0){
          $discount_display = '<div class="discount">-'.$x.'%</div>';
          $oldprice = '<span class="oldprice"> Var: ' . $price . 'kr. </span>';
        } else {
          $discount_display = '';
          $oldprice = '';
        }
//BÚA TIL AVAILABLE MAGN SKOÐUN
                    $subsql = mysql_query("SELECT * FROM sub_products WHERE product_id = '$id'"); //SELECT * ÞÝÐIR SELECT ALL
                    $subCount = mysql_num_rows($subsql);
                    $subsql2 = mysql_query("SELECT * FROM sub_products WHERE product_id = '$id' AND available > '0'"); //SELECT * ÞÝÐIR SELECT ALL
                    $subCount2 = mysql_num_rows($subsql2);
                    if($subCount === $subCount2 & $subCount != 0) {
                        $ertil = "<div class='status-1'></div>";
                    }else{
                        $ertil = "<div class='status-0'></div>";
                    }
//AVAILABLE MAGNSKOÐUN TILBÚIN
      $dynamic_list .='<tr>';
      $dynamic_list .='<td> <a href="http://nomo.is/vara/' . $id . '"><img src="http://nomo.is/images/inventory/' . $id . '_thumb.jpg" alt="' . $product_name . '" width="120" height="140" border="1"></a></td>';
      $dynamic_list .='<td> <form action="" enctype="multipart/form-data" name="edit" method="post">
      <input name="new_name" class="input align_left"  type="text" value="' . $product_name . '" size="7"> 
      <input name="button" class="button" type="submit" value="Breyta"/> 
      <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
      </form></td>';
      $dynamic_list .='<td> '.$store.'</td>';
      $dynamic_list .='<td> <form action="" enctype="multipart/form-data" name="edit" method="post">
      <textarea wrap="soft" rows="3" cols="10" name="new_description" class="input align_left">' . $description . '</textarea>
      <input name="button" class="button" type="submit" value="Breyta"/> 
      <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
      </form></td>';      
      $dynamic_list .='<td> <form action="" enctype="multipart/form-data" name="edit" method="post">
       <input name="new_trademark" class="input align_left"  type="text" value="' . $trademark . '" size="7"> 
      <input name="button" class="button" type="submit" value="Breyta"/> 
      <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
      </form></td>';
      $dynamic_list .='<td> <form action="" enctype="multipart/form-data" name="edit" method="post">
       <input name="web_price" class="input align_left"  type="text" value="' . $price . '" size="7"> 
      <input name="button" class="button" type="submit" value="Breyta"/> 
      <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
    </form></td>';
      $dynamic_list .='<td> <form action="" enctype="multipart/form-data" name="edit" method="post">
      <input name="model_wears" class="input align_left"  type="text" placeholder="' . $model_wears . '" size="4">
                          <select name="model_id" id="model_id">
                               <option value="">Veldu Módel</option>
                               <option value="0">Ekkert módel</option>
                               '.$ModelList.'
                           </select>
      <input name="button" class="button" type="submit" value="Breyta"/> 
      <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
    </form></td>';
      $dynamic_list .='<td>' . $ertil . '</td>';
      $dynamic_list .='<td>
<form action ="" method="post"> 
    <input name="acceptbtn" type="submit" class="button" Value="Samþykkja" style="font-size:12px;"/> 
    <input name="item_to_accept" type="hidden" value="' . $id . '"/>    
        </form>
        <form action ="" method="post"> 
    <input name="acceptbtn" type="submit" class="button" Value="Hafna" style="font-size:12px;"/> 
    <input name="item_to_decline" type="hidden" value="' . $id . '"/>    
          </form>
      </td>' ;
      $dynamic_list .='</tr>';

    } 
} else {
    $dynamic_list = "Það á ekki eftir að samþykkja neinar vörur";
} 
}
?>

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Samþykktir</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>

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
        <h1 style="margin-top:30px;">Vörusamþykktir</h1>
        <div id="admin-container">
            <?php
echo 'Fjöldi Vara <strong>'. $productCount. '</strong><br/>';
?>

    <table class="">
                <tr style="background-color: #333; color:#fff; font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="10%">Mynd</td>
                    <td width="18%">Nafn <br/></td>
                    <td width="18%">Efnislýsing <br/></td>
                    <td width="18%">Lýsing <br/></td>
                    <td width="18%">Fatamerki <br/></td>
                    <td width="13%">Verð Vöru</td>
                    <td width="13%">Módel</td>
                    <td width="9%">Til í öllum stærðum?</td>
                    <td width="9%">Samþykkja</td>
                </tr>
                    <?php echo $dynamic_list; ?>
    </table>
            
</div>






</body>
</html>