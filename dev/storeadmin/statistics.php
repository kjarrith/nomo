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

//PRINTING OUT THE [PRODUCT] LIST AS STORE ADMIN -------------------
if(isset($_SESSION['store'])){
$store = $_SESSION{"store"};
    // PRINTING OUT PRODUCT LIST NOT AS ADMIN
    $product_list = "";
    $sql = mysql_query("SELECT * FROM orders WHERE store = '$store' ORDER BY id DESC"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql);
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $product_name = $row{"product_name"};
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '-second_thumb.jpg" class="p-image bottom"></a>
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '_thumb.jpg" class="p-image top"></a>
            $style_id = $row{"style_id"};
            $price = $row{"price"};
            $store = $row{"store"};
            $totalamount = $row{"totalamount"};
            $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
            $product_list .='<tr>';
            $product_list .='<td><a href="product_edit.php?id=' . $id . '">' . $product_name . '</a></td>';
            $product_list .='<td> #' . $id . '</td>';
            $product_list .='<td style="font-size:12px;"> ' . $style_id .'
            </td>';
            $product_list .='<td> <img src="../images/inventory/' . $id . '.jpg" alt="' . $product_name . '" width="80" height="100" border="1"></td>';
            $product_list .='<td>' . $price . 'kr.</td>';
            $product_list .='<td>' . $date_added . '</td>';
            $product_list .="<td><a class='button' href='inventory_list.php?deleteid=$id'>X</a> <a class='button' href='product_edit.php?id=$id . ''>?</a> </td>" ;
            $product_list .='</tr>';
        } 
    } else {
        $product_list = "<strong>Það hafa engar pantanir átt sér stað í þessum mánuði</strong>";
    } 


}
?>

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Inventory List</title>

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

    <table class="cart_table">
                <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                    <td width="18%">Nafn <br/></td>
                    <td width="10%">ID númer</td>
                    <td width="15%">Style_id</td>
                    <td width="18%">Mynd</td>
                    <td width="16%">Verð</td>
                    <td width="9%">Bætt við</td>
                    <td width="9%">Breyta</td>
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
