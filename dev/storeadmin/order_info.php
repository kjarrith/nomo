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
//MERKJA VÖRU SEM SÓTTA
if(isset($_POST['item_arrived'])) {
    $arrived_id = $_POST['item_arrived'];
         $sql = mysql_query("UPDATE orders SET status = '1' WHERE id = '$arrived_id';")or die(mysql_error());
        header('location:order_info.php');
        exit();
}

//MERKJA VÖRU SEM EKKI SÓTTA
if(isset($_POST['item_not_arrived'])) {
    $not_arrived_id = $_POST['item_not_arrived'];
         $sql = mysql_query("UPDATE orders SET status = '0' WHERE id = '$not_arrived_id';")or die(mysql_error());
        header('location:order_info.php');
        exit();
}
?>

<?php

//PRINTING OUT THE [PRODUCT] LIST AS STORE ADMIN -------------------
if(isset($_COOKIE['oid'])){
    // PRINTING OUT PRODUCT LIST NOT AS ADMIN
    $product_list = "";
    $sql = mysql_query("SELECT * FROM orders WHERE status = 1 ORDER BY id DESC LIMIT 20"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql);
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            //PRODUCT
            $id = $row{"id"};
            $product_id = $row{"product_id"};
            $product_name = $row{"product_name"};
            $product_name = strtolower($product_name); 
            $product_name = ucfirst($product_name); 
            $price = $row{"price"};
            $status = $row{"status"};
            $size = $row['size'];
            $store = $row{"store"};
            $TransactionNumber = $row{"TransactionNumber"};
            $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
            //CUSTOMER
            $customer_name = $row['customer_name'];
            $customer_phone = $row['customer_phone'];          
            $customer_address = $row['customer_address'];
            $customer_postal = $row['customer_postal'];
            $customer_city = $row['customer_city'];
            $customer_email = $row['email'];
            if($row{"date_added"} = date("y-m-d")){
                $new = "<span style='color:#111'>KOM Í DAG!</span>";
            } else {
                $new = "Eldri...";
            }
            $product_list .='<tr>';
            $product_list .='<td> <img src="http://nomo.is/images/inventory/' . $product_id . '_thumb.jpg" alt="' . $product_name . '" class="table_img"></td>';
            $product_list .='<td><div class="status-'.$status.'"></div></td>';
            $product_list .='<td><a href="http://nomo.is/vara/' . $product_id . '">' . $product_name . '</a></td>';
            $product_list .='<td>'.$size.'</td>';            
            $product_list .='<td> #' . $TransactionNumber . '</td>';
            $product_list .='<td>' . $price . 'kr.</td>';
            $product_list .='<td>' . $date_added . '</td>';
            $product_list .='<td><form action ="" method="post"> 
                            <input name="acceptbtn" type="submit" class="button" Value="Ekki sótt" style="font-size:12px;"/> 
                            <input name="item_not_arrived" type="hidden" value="' . $id . '"/>    
                         </form>
                     </td>' ;
            $product_list .='</tr>';
            $product_list .='<tr style="background-color:#f1f1f1;">';
            $product_list .='<td>'.$store.'</td>';
            $product_list .='<td>'.$customer_name.'</td>'; 
            $product_list .='<td>'.$customer_phone.'</td>';          
            $product_list .='<td>'.$customer_email.'</td>';
            $product_list .='<td>'.$customer_postal.'</td>';
            $product_list .='<td>'.$customer_city.'</td>';
            $product_list .='<td>'.$customer_address.'</td>';
            $product_list .='</tr>';
        } 
    } else {
        $product_list = "<br/><strong>Ekki er búið að senda heim neinar pantanir.</strong><br/>";
    } 

    $new_list = "";
    $sql = mysql_query("SELECT * FROM orders WHERE status = 0 ORDER BY id DESC"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql);
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            //PRODUCT
            $id = $row{"id"};
            $product_id = $row{"product_id"};
            $product_name = $row{"product_name"};
            $product_name = strtolower($product_name); 
            $product_name = ucfirst($product_name); 
            $price = $row{"price"};
            $status = $row{"status"};
            $size = $row['size'];
            $store = $row{"store"};
            $TransactionNumber = $row{"TransactionNumber"};
            $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
            //CUSTOMER
            $customer_name = $row['customer_name'];
            $customer_address = $row['customer_address'];
            $customer_postal = $row['customer_postal'];
            $customer_phone = $row['customer_phone'];
            $customer_city = $row['customer_city'];
            $customer_email = $row['email'];
            if($row{"date_added"} = date("y-m-d")){
                $new = "<span style='color:#111'>KOM Í DAG!</span>";
            } else {
                $new = "Eldri...";
            }
            $new_list .='<tr>';
            $new_list .='<td> <img src="http://nomo.is/images/inventory/' . $product_id . '_thumb.jpg" alt="' . $product_name . '" class="table_img"></td>';
            $new_list .='<td><div class="status-'.$status.'"></div></td>';
            $new_list .='<td><a href="http://nomo.is/vara/' . $product_id . '">' . $product_name . '</a></td>';
            $new_list .='<td>'.$size.'</td>';
            $new_list .='<td> #' . $TransactionNumber . '</td>';
            $new_list .='<td>' . $price . 'kr.</td>';
            $new_list .='<td>' . $date_added . '</td>';
            $new_list .='<td><form action ="" method="post"> 
                                    <input name="acceptbtn" type="submit" class="button" Value="Sótt" style="font-size:12px;"/> 
                                    <input name="item_arrived" type="hidden" value="' . $id . '"/>    
                                </form>
                            </td>' ;
            $new_list .='</tr>';
            $new_list .='<tr style="background-color:#f1f1f1; border-bottom:2px solid #666;">';
            $new_list .='<td>'.$store.':</td>';
            $new_list .='<td>'.$customer_name.'</td>';            
            $new_list .='<td>'.$customer_phone.'</td>';             
            $new_list .='<td>'.$customer_email.'</td>';
            $new_list .='<td>'.$customer_postal.'</td>';
            $new_list .='<td>'.$customer_city.'</td>';
            $new_list .='<td>'.$customer_address.'</td>';
            $new_list .='</tr>';
        } 
    } else {
        $new_list = "<strong>Það eru engar ósóttar pantanir.</strong>";
    } 
}
?>

<?php
//MERKJA ÓSÓTTAR VÖRUR SEM SÓTTAR.
if(isset($_POST['mark-sent'])) {
    mysql_query("UPDATE orders SET status = 1 WHERE status = 0"); //SELECT * ÞÝÐIR SELECT ALL 
    header("location:order_info.php");
    exit();
}
?>

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Pantanir</title>

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
        <h1 style="margin-top:30px;">Pantanir Allra Búða</h1>
        <div id="admin-container">
            <br/><br/>
    <table class="cart_table">
                <tr style="background-color: #f1f1f1; color:#111; font-weight: bold; ">
                    <td width="18%">Mynd</td>                     
                    <td width="5%">Staða <br/></td>
                    <td width="18%">Nafn Vöru <br/></td>
                    <td>Stærð</td>
                    <td width="10%">Sölunúmer</td>
                    <td width="15%">Verð</td>
                    <td width="16%">Dagsetning</td>
                    <td width="9%">Sótt</td>
                </tr>
                    <?php echo $new_list; ?>
    </table>
    <br/><hr/>
<h1 align='center' style="margin:20px;"> Seinustu 20</h1>
    <table class="cart_table">
                <tr style="background-color: #f1f1f1; color:#111; font-weight: bold; ">
                    <td width="18%">Mynd</td>                     
                    <td width="5%">Staða <br/></td>
                    <td width="18%">Nafn Vöru <br/></td>
                                        <td>Stærð</td>
                    <td width="10%">Sölunúmer</td>
                    <td width="15%">Verð</td>
                    <td width="16%">Dagsetning</td>
                    <td width="9%">Sótt</td>
                </tr>
                    <?php echo $product_list; ?>
    </table>
<br/><br/>
        </div>    

</div>






</body>
</html>
