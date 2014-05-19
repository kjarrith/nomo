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

<?php
//DEFINE VARIABLES FOR USE LATER
    $date="";
    $store_search ="";
    $today = date('d.M');
    $date1 = "";
    $date2 = "";
    $admin_store = "";
//-------------------
if(isset($_GET['id'])){
    $store_id = $_GET['id'];
}
$sql = mysql_query("SELECT * FROM stores WHERE id = '$store_id'"); //SELECT * ÞÝÐIR SELECT ALL
$storeCount = mysql_num_rows($sql);
if($storeCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $id = $row{"id"};
        $store_name = $row{"store_name"};
    } 
}
//PRINTING OUT THE [PRODUCT] LIST AS STORE ADMIN -------------------
            $RealTotal = 0;
            $vsk = 0;
            $NomoPart = 0;
            $totalNomo = 0;
            $totalamount = 0;
            $pricesansvsk = 0;
            $nomoperc = 0;
            $stkvsk = 0;
            $payout = 0;
            $product_list = "";
            $totalamount = 0;
            $NomoProsenta = 0;
//BÚA TIL DAGSETNINGU EF HÚN ER VALIN
    if(isset($_POST['season'])){
        $season = $_POST['season'];
    } else {
        $season_start = date("Y-", strtotime("now")).date("m-", strtotime("-1 month")). "22" ;
        $season_end =date("Y-", strtotime("now")).date("m-", strtotime("now")). "21" ;
        $season = $season_start .' til '.$season_end;
    }
    $date = explode("til", $season);
//--------------------
    if(isset($season) AND $season !="") {
    $sql = mysql_query(" SELECT * FROM orders WHERE store = '$store_name' AND date_added BETWEEN '$date[0]' AND '$date[1]' ORDER BY id ASC"); //SELECT * ÞÝÐIR SELECT ALL 
    } else {
    $sql = mysql_query(" SELECT * FROM orders WHERE store = '$store_name' AND MONTH(`date_added`) = MONTH(CURDATE()) AND YEAR(`date_added`) = YEAR(CURDATE()) ORDER BY id ASC"); //SELECT * ÞÝÐIR SELECT ALL
    }

    $productCount = mysql_num_rows($sql);
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $product_id = $row{"product_id"};
            $product_name = $row{"product_name"};
            $product_name = strtolower($product_name); 
            $product_name = ucfirst($product_name); 
            $price = $row{"price"};
            $store = $row{"store"};
            $TransactionNumber = $row{"TransactionNumber"};
            $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
            $NomoAfslattur = $row["discount"];
//FINNA NOMO PART HJÁ ÞEIRRI VERSLUN SEM VARAN ER HJÁ
            $sql2 = mysql_query("SELECT nomopart FROM stores WHERE store_name = '$store'"); //SELECT * ÞÝÐIR SELECT ALL
            while ($row=mysql_fetch_array($sql2)) {
                $NomoProsenta = $row{"nomopart"};
            }
            //STOFNA BREYTUR FYRIR STK
            $pricesansvsk = $price *0.745;
            $NomoPart = (($NomoProsenta - $NomoAfslattur)/100)*$price;
            $stkvsk = $price * 0.255;
            $payout = $price - ($price * ($NomoProsenta/100));
            $totalamount = $price + $totalamount;
            $totalNomo = $NomoPart + $totalNomo;
            //BÚA TIL PANTANALISTA
            $product_list .='<tr>';
            $product_list .='<td>' . $date_added . '</td>';
            $product_list .='<td> #' . $TransactionNumber . '</td>';
            $product_list .='<td>' . $product_name . '</td>';
            $product_list .='<td>' . $price . '</td>';
            $product_list .='<td>' . $stkvsk . '</td>';      
            $product_list .='<td>' . $NomoAfslattur . '%</td>'; 
            $product_list .='<td>' . $NomoPart . '</td>';
            $product_list .='<td>' . $payout . 'kr.</td>';
            $product_list .='</tr>';
            }
    } else {
        $response = "<strong>Það hafa engar pantanir átt sér stað í þessum mánuði</strong>";
    } 

     //FINNA VIRK ADDONS
    $addon_list = "";
    $response2 = "";
    $totaladdon = 0;
    $sql = mysql_query("SELECT * FROM addons WHERE date_added BETWEEN '$date1' AND '$date2' ORDER BY id DESC"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql);
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $type = $row["type"];
            $addon_store = $row["store"];
            $addon_amount = $row["amount"];
            $other = $row["other"];
            $addon_date_added = $row["date_added"];
            //Reikningar
            $notax = $addon_amount * 0.75;
            $tax = $addon_amount * 0.25;

            $totaladdon = $addon_amount + $totaladdon;
            //BÚA TIL PANTANALISTA
            $addon_list .='<tr>';
            $addon_list .='<td>' . $addon_date_added . '</td>';
            $addon_list .='<td>' . $type . '</td>';
            $addon_list .='<td>' . $other . ' stk.</td>';
            $addon_list .='<td>' . $notax . '</td>';
            $addon_list .='<td>' . $tax . 'kr.</td>';
            $addon_list .='</tr>';
            }
    } else {
        $response2 = "<strong>Þið hafið ekki keypt neinar nýjar viðbætur</strong>";
    }
            //BÚA TIL SAMTALSLISTA
            $TotalVsk = $totalamount * 0.255;
            $RealTotal = $totalamount * (1-($NomoProsenta/100));
?>

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Sölutölur</title>

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

<body onload="process()" style="padding:30px; background-color: #fff ;">

<h1 style="margin:10px 0px;">NOMOCO EHF.</h1><br/>
<p style=" margin: 0;">KT: 450310-0690</p>
<p style=" margin: 0;">Suðurlandsbraut 10</p><br/>
<p>Reikningsskil</p>
<p style=" margin: 0;"><?php echo $lastmonth;?></p>
    <span class="no-print"style="text-align:center;">
            <form name="date" action="" method="post"> 
                <span style="font-size: 0.8em;">Veldu Tímabil</span><br/>
                    <select name="season">
                        <option value=""> í dag er <?php echo $today; ?></option>
                        <option value="2014-01-21 til 2014-02-22">22.janúar - 21. febrúar</option>
                        <option value="2014-02-21 til 2014-03-22">22.febrúar - 21. mars</option>
                        <option value="2014-03-21 til 2014-04-22">22.mars - 21. apríl</option>
                        <option value="2014-04-21 til 2014-05-22">22.apríl - 21. maí</option>
                        <option value="2014-05-21 til 2014-06-22">22.maí - 21. júní</option>
                        <option value="2014-06-21 til 2014-07-22">22.júní - 21. júlí</option>
                        <option value="2014-07-21 til 2014-08-22">22.ágúst - 21. september</option>
                    </select><br/>
                    <input type="submit" class="button " value="STAÐFESTA">
            </form> <br/>
            <?php echo $user_list;?>
        </span>
            <hr/>
            <h3 align='center'> <?php echo $store_name; ?></h3>
            <h3 align='center'> <?php echo $season; ?></h3>
            <h3 align='left'> FULLGREIDDAR PANTANIR:</h3>  
            <div style="width:300px; border-bottom: 1px solid #ccc; margin-bottom: 15px;"></div>   
                <table class="cart_table">
                    <tr style="background-color: #f1f1f1; color:#111; font-weight: bold; ">
                        <td width="10%">Dagsetning<br/></td>
                        <td width="10%">Pöntunarnúmer</td>
                        <td width="20%">Nafn Vöru</td>
                        <td width="15%">Söluverð (án VSK)</td>
                        <td width="16%">VSK</td>
                        <td width="16%">Nomo Afsláttur</td>                      
                        <td width="16%">Hlutur NOMO</td>
                        <td width="9%">Hlutur Verslunar</td>
                    </tr>
                        <?php echo $product_list; ?>
                </table>
                <br/>
            <h3 align='left'> SAMANTALNING:</h3>  
            <div style="width:300px; border-bottom: 1px solid #ccc;"></div>   

            <table style="width:80%; text-align: left; margin:0px auto;">
                <br/>
                <tr style="font-weight: bold; ">
                    <td width="70%"> <br/></td>
                    <td width="30%" style="text-align: right;"> Upphæð: <br/></td>
                </tr>
                <tr style=" border-bottom: 1px solid #ccc; ">
                    <td width="70%"> Heildarsala án skatts: <br/></td>
                    <td width="30%" style="text-align: right;"> <?php echo $totalamount ?> kr.<br/></td>
                </tr>
                <tr style=" border-bottom: 1px solid #ccc;background-color: #f1f1f1; ">
                    <td width="70%"> Þar af skattur: <br/></td>
                    <td width="30%" style="text-align: right;"> (<?php echo $TotalVsk ?> kr.)<br/></td>
                </tr>
                <tr style="border-bottom: 1px solid #ccc;background-color: #f1f1f1; ">
                    <td width="70%"> Greitt Til Verslunar: <br/></td>
                    <td width="30%" style="text-align: right;"> <?php echo $RealTotal ?> kr.<br/></td>
                </tr> 
                <tr style=" border-bottom: 1px solid #ccc; font-weight: bold; ">
                    <td width="70%"> Nomo græðir: <br/></td>
                    <td width="30%" style="text-align: right;"> <?php echo $totalNomo ?> kr.<br/></td>
                </tr>    
            </table>
            <?php if(isset($response)){
                echo $response;
            }
            ?>  






</body>
</html>
