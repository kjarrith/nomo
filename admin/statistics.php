<?php
//STARTING A CONNECTION TO THE DATABASE
ob_start();
session_start();

if (
    !isset($_COOKIE{"manager"})) {
    header("location:admin-login.php");
    exit();
}
//Be sure to check if the SESSION details are in fact in the database.
$managerID = preg_replace('#{0-9}#i','', $_COOKIE{"aid"});
$manager = preg_replace('#{^A-Za-z0-9}#i', '', $_COOKIE{"manager"});
$password = preg_replace('#{^A-Za-z0-9}#i', '', $_COOKIE{"password"});

//CONNECT TO THE DATABASE
include '../templates/storescripts/connect_to_mysql.php';
$sql = mysql_query("SELECT * FROM admin WHERE id='$managerID' AND username='$manager' AND password='$password' LIMIT 1");

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

//PRINTING OUT THE [PRODUCT] LIST AS STORE ADMIN -------------------
if(isset($_COOKIE['store'])){
$store = $_COOKIE{"store"};
    // PRINTING OUT PRODUCT LIST NOT AS ADMIN
    $product_list = "";
    $sql = mysql_query("SELECT * FROM orders WHERE store = '$store' AND status = 1 ORDER BY id DESC"); //SELECT * ÞÝÐIR SELECT ALL
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
            $product_list .='<td><a href="http://nomo.is/images/inventory/' . $product_id . '_thumb.jpg">' . $product_name . '</a></td>';
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
            $product_list .='<td>Viðskiptavinur:</td>';
            $product_list .='<td>'.$customer_name.'</td>';            
            $product_list .='<td>'.$customer_email.'</td>';
            $product_list .='<td>'.$customer_postal.'</td>';
            $product_list .='<td>'.$customer_city.'</td>';
            $product_list .='<td>'.$customer_address.'</td>';
            $product_list .='<td>'.$size.'</td>';
            $product_list .='</tr>';
        } 
    } else {
        $product_list = "<strong>Engum pöntunum hefur verið komið til kaupanda í þessum mánuði</strong>";
    } 

        $new_list = "";
    $sql = mysql_query("SELECT * FROM orders WHERE store = '$store' AND status = 0 ORDER BY id DESC"); //SELECT * ÞÝÐIR SELECT ALL
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
            $customer_city = $row['customer_city'];
            $customer_email = $row['email'];
            if($row{"date_added"} = date("y-m-d")){
                $new = "<span style='color:#111'>KOM Í DAG!</span>";
            } else {
                $new = "Eldri...";
            }
            $new_list .='<tr>';
            $new_list .='<td> <img src="http://nomo.is/images/inventory/' . $product_id . '_thumb.jpg" alt="' . $product_name . '" class="table_img" style="width:100px; height:133px;"></td>';
            $new_list .='<td><div class="status-'.$status.'"></div></td>';
            $new_list .='<td><a href="http://nomo.is/images/inventory/' . $product_id . '_thumb.jpg">' . $product_name . '</a></td>';
            $new_list .='<td> #' . $TransactionNumber . '</td>';
            $new_list .='<td>' . $price . 'kr.</td>';
            $new_list .='<td>' . $date_added . '</td>';
            $new_list .='</tr>';
            $new_list .='<tr style="background-color:#f1f1f1; border-bottom:2px solid #666;">';
            $new_list .='<td>'.$customer_name.'</td>';            
            $new_list .='<td>'.$customer_email.'</td>';
            $new_list .='<td>'.$customer_postal.'</td>';
            $new_list .='<td>'.$customer_city.'</td>';
            $new_list .='<td>'.$customer_address.'</td>';
            $new_list .='<td>Stærð: <strong>'.$size.'</strong></td>';
            $new_list .='</tr>';
        } 
    } else {
        $new_list = "<strong>Það eru engar ósóttar pantanir.</strong>";
    } 


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
<?php include_once("../includes/menu-admin.php"); ?>

<div id="main-admin"> 
  
    <a href ="#addproduct">
        <br/>
        </div>
    </a>

        <div id="admin-container">

            <h3 align='center'> <?php if($manager!="admin") {echo $store;} else {echo "Pulsa";}?></h3>
<h1 align='left' style="margin:20px;"> Ósóttar pantanir</h1>
    <table class="cart_table" style="width:80%; margin:0px auto;">
                <tr style="background-color: #f1f1f1; color:#111; font-weight: bold; ">
                    <td width="18%">Mynd</td>                  
                    <td width="3%">Staða</td>
                    <td width="20%">Nafn Vöru <br/></td>
                    <td width="10%">Pöntunarnúmer</td>
                    <td width="15%">Verð</td>
                    <td width="16%">Dagsetning</td>
                </tr>
                    <?php echo $new_list; ?>
    </table>
<h1 align='left' style="margin:20px;"> Allar fullunnar pantanir</h1>
    <table class="cart_table">
                <tr style="background-color: #f1f1f1; color:#111; font-weight: bold; ">
                    <td width="18%">Mynd</td>                  
                    <td width="3%">Staða</td>
                    <td width="20%">Nafn Vöru <br/></td>
                    <td width="10%">Pöntunarnúmer</td>
                    <td width="15%">Verð</td>
                    <td width="16%">Dagsetning</td>
                </tr>
                    <?php echo $product_list; ?>
    </table>

        </div>    


            

     <div id="footer-sub">
	All rights reserved
    </div>
</div>






</body>
</html>
