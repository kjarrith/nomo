<?php
//STARTING A CONNECTION TO THE DATABASE
ob_start();
session_start();
include '../templates/storescripts/connect_to_mysql.php';

if (
    !isset($_COOKIE{"manager"})) {
    header("location:admin-login.php");
    exit();
}
//Be sure to check if the SESSION details are in fact in the database.
$managerID = preg_replace('#{0-9}#i','', $_COOKIE{"aid"});
$manager = preg_replace('#{^A-Za-z0-9}#i', '', $_COOKIE{"manager"});
$password = preg_replace('#{^A-Za-z0-9}#i', '', $_COOKIE{"password"});
    $currentdate=date('Y-m-d');

//CONNECT TO THE DATABASE
$sql = mysql_query("SELECT * FROM admin WHERE id='$managerID' AND username='$manager' AND password='$password' LIMIT 1");

//BE SURE THAT THE PERSON EXCISTS IN THE DATABASE
$exist_count = mysql_num_rows($sql); //count the rows in $sql
if ($exist_count==0) {
    echo "Upplýsingar þínar eru ekki í gagnagrunninum okkar";
    exit();
}
//GATHER INFO ABOUT STORE
$sql = mysql_query("SELECT * FROM stores WHERE admin='$manager' ");
    while ($row=mysql_fetch_array($sql)) {
                $storename = $row["store_name"];
                $NomoProsenta = $row["nomopart"];
                $kennitala = $row["kennitala"];
            }

if (isset($_COOKIE['manager'])) {
$store_array ="";
$store_list = "";
$id_list = "";
$sql = mysql_query("SELECT * FROM stores where admin = '$manager' "); //SELECT * ÞÝÐIR SELECT ALL
$storeCount = mysql_num_rows($sql);
if($storeCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $store_name = $row{"store_name"};
        $store_id = $row{"id"};
        $store_pcount = $row['pcount'];
        $store_list .= '<option value="'.$store_id.'">'.$store_name.'</option>';
        $store_array .= $store_name. " ";
        $id_array .= $store_id. " ";
    } 
    $pieces = explode(" ", $store_array);
    $pieces2 = explode(" ", $id_array);
} else {
    $store_list = "Þú ert ekki admin hjá neinni búð";
} 
}

?>

<?php

//ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>

<?php
    // PRINTING OUT PRODUCT LIST NOT AS ADMIN
    $product_list = "";
    $productCount ="";
    $a_sum ="";
    $ertil ="";
    $sql = mysql_query("SELECT * FROM products WHERE store = '$pieces[0]' OR store = '$pieces[1]' OR store = '$store_name' AND status = 1 "); //SELECT * ÞÝÐIR SELECT ALL
    if($sql != ""){
        $productCount = mysql_num_rows($sql);
    }
    if(isset($productCount) & $productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $product_name = $row{"product_name"};
            $product_name = strtolower($product_name); 
            $product_name = ucfirst($product_name); 
            $style_id = $row{"style_id"};
            $price = $row{"price"};
            $status = $row{"status"};
            $visited = $row{"visited"};
            $x = $row{"dcount"};
            $store = $row{"store"};
            $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
            if ($x>100) {
                $x=100;
            }
            if ($x>0){
            $realprice = round((1-($x/100))*$price);
            } else {
                $realprice = '-';
            }
            $style_id = (strlen($style_id) > 18) ? substr($style_id,0,15).'...' : $style_id;
            $product_list .='<tr style="border-bottom:3px solid #444;">';
            $product_list .='<td> <a href="product_edit.php?id=' . $id . '"><img src="../images/inventory/' . $id . '_thumb.jpg" alt="' . $product_name . '" width="100" height="133" border="1"></a></td>';
            $product_list .='<td>#' . $id . '</td>';
            $product_list .='<td><a href="product_edit.php?id=' . $id . '">' . $product_name . '</a></td>';
            $product_list .='</tr>';
        } 
    } else {
        $product_list = "Það eru engar vörur í búðinni";
    } 
?>



<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Vörulisti</title>

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
<style type="text/css">
   table { page-break-inside:auto }
   tr    { page-break-inside:avoid; page-break-after:auto }

</style>
</head>

<body onload="process()" style="padding:30px; background-color: #fff ;">

<h1 style="margin:10px 0px;"><?php echo $storename; ?></h1><br/>
<p>Vörulisti</p>
<p style=" margin: 0;"><?php echo $currentdate;?></p>
 
    <table id="results">
                <tr style="background-color: #333; color:#fff; font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="100px">Mynd <br/></td>
                    <td width="70px">ID Tala </td>
                    <td width="100px">Vöruheiti<br/></td>
                    <td width="500px">Krotsvæði <br/></td>
                </tr>
                    <?php echo $product_list; ?>
    </table>

</body>
</html>
