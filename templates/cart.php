<?php
global $cssVersion;
global $faviconVersion;
ob_start();
session_start('oid');
$discount = 0;

if (isset($_COOKIE{'uid'})){
//Be sure to check if the SESSION details are in fact in the database.
$userID = $_COOKIE{"uid"};

$sql = mysql_query("SELECT * FROM users WHERE id = '$userID' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $name = $row['name'];
        $username = $row['username'];
        $address = $row['address'];
        $email = $row['email'];
        $discount = $row['discount'];
      }
      $name = ucfirst($name); 
}


}

?>
<?php
//ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', '1');

    $coupon_code = "";
if(isset($_POST['coupon'])){
    $coupon_code = $_POST['coupon'];
    $couponsql = mysql_query("SELECT * FROM affiliates WHERE code = '$coupon_code' "); //SELECT * ÞÝÐIR SELECT ALL  
    $couponCount = mysql_num_rows($couponsql);
        if($couponCount>0){
            $discount = 10;
        } else {
            $coupon_response = "Þessi afsláttarkóði er því miður ekki til. <br/>Ertu svindlari?";
        }
}

?>


<?php
//CREATING THE CART ARRAY
if (isset($_POST['id'])) {
    //HÉR SETUR ÞÚ LÍKA STÆRÐIR, LITI OG FLEIRA OG SKÝRIR ÞÁ Í LOCAL VARIABLES
    $id = $_POST['id'];
    $wasFound = false;
    $i = 0;
    if (isset($_POST['size'])){
      $tempsize = ($_POST{'size'});
    }
    $savesize = $size;
    //FINNA ID NÚMER STÆRÐARINNAR
    $sql = mysql_query("SELECT * FROM sub_products WHERE product_id = '$id' AND size = '$tempsize'"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql);
        if($productCount>0){
            while ($row=mysql_fetch_array($sql)) {
                $size = $row{"id"};
              }
            }
    //------------------
    //IF THE CART SESSION VARIABLE IS NOT SET OR CART ARRAY IS EMPTY
    //HÉR ÞARF AÐ SETJA COOKIE FILES EF ÞÚ VILT AÐ CARTIÐ BÍÐI LENGUR
    //MUNA AÐ BÆTA LÍKA VIÐ EF SETTIR ERU INN LITIR OG STÆRÐIR OG FJÖLDI ("1->$count")
    if(!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"])<1) {
        mysql_query("INSERT INTO cart_inputs (product_id, user_id, size, date_added) VALUES('$id', '$userID', '$size', now()) ")or die(mysql_error());
        $_SESSION['cart_array'] = array(0 => array("item_id" => $id, "quantity" => 1, "size" => $size, "sizename" => $tempsize));
    } else {
        //RUN IF THE CART HAS ONE OR MORE ITEMS
        foreach ($_SESSION["cart_array"] as $each_item) {
            $i++;
            while (list($key, $value) = each($each_item)) {
                if($key == "item_id" && $value == $id && $key == "size" && $value == $size) {
                    //THAT ITEM IS IN THE CART, SO LETS UP THE CUANTITY BY 1
                    array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $id, "quantity" => $each_item['quantity']+ 1, "size" => $size, "sizename" => $tempsize)));
                    $wasFound = true;
                }//close if
            }//close whie
        }//close foreach
        if ($wasFound == false) {
            array_push($_SESSION["cart_array"], array("item_id" => $id, "quantity" => 1, "size" => $size, "sizename" => $tempsize));
            mysql_query("INSERT INTO cart_inputs (product_id, user_id, size, date_added) VALUES('$id', '$userID','$size', now()) ")or die(mysql_error());       
        } //close if
    }//close else
    //Gerir það að verkum að nýrri vöru er ekki bætt við þegar að síða er refreshuð
    header("Location: karfa");
    exit();
} //close if
?>

<?php
// IF USER DECIDES TO EMPTY HIS SHOPPING CART
if (isset($_POST['empty'])) {
        unset($_SESSION["cart_array"]);
        unset($_SESSION["cartTotal"]);
        header("location:karfa");
        exit();
}
?>

<?php
//IF USER CHOOSES TO ADJUST ITEM QUANTITY
if(isset($_POST['item_to_adjust'])&& $_POST['item_to_adjust'] !="") {
    //ADJUST THE QUANTITY
    $item_to_adjust = $_POST['item_to_adjust'];
    $savesize = $_POST['size_of_item'];
    $quantity = $_POST['quantity'];
    $quantity = preg_replace('#[^0-9]#i', '', $quantity);
     foreach ($_SESSION["cart_array"] as $each_item) {
            $i++;
            while (list($key, $value) = each($each_item)) {
                if($key == "item_id" && $value == $item_to_adjust)  {
                    //THAT ITEM IS IN THE CART, SO LETS UP THE CUANTITY BY 1
                    array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $quantity, "size" => $savesize)));
                }//close if
            }//close whie
        }//close foreach
        header("location:karfa");
        exit();
}
?>

<?php
//AÐ EYÐA VÖRU ÚR KÖRFUNNI
if(isset($_POST['index_to_delete']) && $_POST['index_to_delete'] !="") {
    //FARA Í ARRAYIN OG EYÐA ÞVÍ ARRAY INDEXI SEM Á VIÐ.
    $key_to_remove = $_POST['index_to_delete'];
    if (count($_SESSION["cart_array"])<= 1) {
        unset($_SESSION["cart_array"]);
        unset($_SESSION["cartTotal"]);
    } else {
        unset($_SESSION["cart_array"]["$key_to_remove"]);
        sort($_SESSION["cart_array"]);
        }
        header("location:karfa");
        exit();
}

?>

<?php
    //TELJA HVE MARGAR PANTANIR HAFA VERIÐ GERÐAR HINGAÐ TIL
    $ordersql = mysql_query("SELECT * FROM orders");
    $orderCount = mysql_num_rows($ordersql);
    //Mikilvægar og Breytanlegar stærðir
    $HeimsendingarMinimum = 0; //EF =0 ÞÁ ER HEIMSENDING ÓKEYPIS
    $delivery = 0;             //HEIMSENDINGARKOSTNAÐURINN
    $CurrentOrder = $orderCount + 1;
    //RENDER THE CART FOR THE USER TO VIEW
    $available_input = "";
    $cartOutput = "";
    $cartTotal = "";
    $y = 0;
    $ordersize = "";
    $valitor_btn = '';
    $valitorinfo = '';
    $DebitCard ="";
    $netgiro_btn ="";
    $ReikningsVidskipti ="";
    $CreditCard ="";
    $delivery = "";
    $ReferenceNumber = "";
    if(!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"])<1) {
        $response = "<h3> Karfan þín er tóm</h3>";
    } else {
    //SETJA SAMAN VALITOR TAKKANN
        $valitor_btn .= '<form action="https://greidslusida.valitor.is/" method="post">
        <input type="hidden" name="MerchantID" value="719">
        <input type="hidden" name="AuthorizationOnly" value="0">
        <input type="hidden" name="Currency" value="ISK">
        ';
    //--
    //SETJA SAMAN NETGÍRÓ TAKKANN
        $netgiro_btn .= '<form action="https://www.netgiro.is/SecurePay/" method="post">
        <input type="hidden" name="ApplicationID" value="6ca5c96d-cbc1-4d47-b988-1fcbca0520c8" />
        <input type="hidden" name="Iframe" value="true" />
        <input type="hidden" name="ReturnCustomerInfo" value="true" />
        <input type="hidden" name="ConfirmationType" value="0" />
        ';
    //--
        $i = 0;
        foreach ($_SESSION["cart_array"] as $each_item) {
            $item_id = $each_item['item_id'];
            $sql = mysql_query("SELECT * FROM products WHERE id='$item_id' LIMIT 1");
            while ($row=mysql_fetch_array($sql)) {
                //SÆKJA UPPLÝSINGAR UM VÖRUNA
                $id = $row{"id"};
                $product_name = $row{"product_name"};
                $product_name = strtolower($product_name); 
                $product_name = ucfirst($product_name); 
                $description = $row{"description"};
                $style_id = $row{"style_id"};
                $x = $row{"dcount"};
                $price = $row{"price"};
                $store = $row{"store"};
                $category = $row{"category"};
                //---
                    //REIKNA VERÐ, SÉ AFSLÁTTUR
                    if ($x>0){
                      $realprice = round((1-($x/100))*$price);
                    } else {
                      $realprice = round($price);
                    }
                    //---
            } //END OF WHILE LOOP
//KOMA Í VEG FYRIR AÐ ÞAÐ SÉ HÆGT AÐ KAUPA 0.3 BOLI       
    if ($each_item['quantity'] < 1) {
        $each_item['quantity'] = 1;
        }
//---
//BÚA TIL LOKAVERÐ OG AFSLÁTT
    $pricetotal = $realprice * $each_item['quantity'];
    $FinalDiscount = round($realprice * ($discount/100));
//BÚA TIL SAMTALS Í KÖRFU BREYTUNA
    $cartTotal = $pricetotal + $cartTotal;
    $_SESSION{"cartTotal"} = $cartTotal. " kr."; 
//---
//SETJA SAMAN VÖRULISTA FYRIR VALITOR   
    $y = $i + 1;
    $valitor_btn .= '
    <input type="hidden" name="Product_'.$y.'_Description" value="'.$product_name.'">
    <input type="hidden" name="Product_'.$y.'_Quantity" value="1">
    <input type="hidden" name="Product_'.$y.'_Price" value="'.$realprice.'">
    <input type="hidden" name="Product_'.$y.'_Discount" value="'.$FinalDiscount.'">
    ';
//---
//SETJA SAMAN VÖRULISTA FYRIR NETGÍRÓ 
    $n = ($i-1) +1;
    $netgiro_btn .= '
        <input type="hidden" name="Items['.$n.'].ProductNo" value="'.$id.'">
        <input type="hidden" name="Items['.$n.'].Name" value="'.$product_name.'">
        <input type="hidden" name="Items['.$n.'].Description" value="'.$description.'">
        <input type="hidden" name="Items['.$n.'].UnitPrice" value="'.$realprice.'">
        <input type="hidden" name="Items['.$n.'].Amount" value="'.$realprice.'">
        <input type="hidden" name="Items['.$n.'].Quantity" value="1000">
    ';
//---
//CREATE THE REFERENCE NUMBER FOR VALITOR
    /*$parts = explode(' ', $each_item['size']);
    foreach($parts as $part) {
        $ordersize .= $part;
    }*/

    $ReferenceNumber .= $id."-1-".$each_item['size']."-".$discount."-".$CurrentOrder.",";
//---
//CREATING THE TABLE FOR VIEWING
    $cartOutput .='<tr>';
    $cartOutput .='<td><a href="/vara/' . $id . '"><br/> <img src="../images/inventory/' . $id . '_thumb.jpg" alt="' . $product_name . '" width="80" height="100" border="1"></a></td>';    
    $cartOutput .='<td><a href="/vara/' . $id . '">' . $product_name . '</a><br/></td>';
    $cartOutput .='<td style="text-align:left;">' . $description . '</td>';
    $cartOutput .='<td>' . $each_item['sizename'] . '</td>';
    $cartOutput .='<td>' . $realprice . 'kr.</td>';
    $cartOutput .='<td>1</td>';
    $cartOutput .='<td> <form action ="karfa" method="post"> <input name="delete' . $id . '" type="submit" class="button" Value="Eyða"> <input name="index_to_delete" type="hidden" value="' . $i . '"> </form> </td>';
    $cartOutput .='</tr>';
//---
//BÚA TIL PRODUCT_X_Y
$valitorinfo .= '1' . $realprice . $FinalDiscount;
//---
            $i++;
        } //ENDA FOREACH
    $y1 = $y +1;
    if($cartTotal > $HeimsendingarMinimum) {
        $delivery = 0;
        $deliveryinfo = '';
    } else {
        $delivery = 1000;
//HEIMSENDING FYRIR VALITOR
        $valitor_btn .= '
            <input type="hidden" name="Product_'.$y1.'_Description" value="Heimsending">
            <input type="hidden" name="Product_'.$y1.'_Quantity" value="1">
            <input type="hidden" name="Product_'.$y1.'_Price" value="'.$delivery.'">
            <input type="hidden" name="Product_'.$y1.'_Discount" value="0">
        ';
//-------
        $deliveryinfo = '1' . $delivery . '0';
    }
    $to_be_payed = $delivery + $cartTotal;
    $_SESSION["to_pay"] = $to_be_payed;
//KLÁRA VALITOR TENGINGU-
$DigitalSignature = md5('kb4qtxhop39j94z0'.$valitorinfo.$deliveryinfo.'719'.$ReferenceNumber.'http://nomo.is/static_pages/takk.phphttp://nomo.is/static_pages/myipn.php?coupon='.$coupon_code.'ISK');
$valitor_btn .= '
    <input type="hidden" name="DigitalSignature" value="'.$DigitalSignature.'">
    <input type="hidden" name="PaymentSuccessfulURL" value="http://nomo.is/static_pages/takk.php">
    <input type="hidden" name="PaymentSuccessfulServerSideURL" value="http://nomo.is/static_pages/myipn.php?coupon='.$coupon_code.'">
    <input type="hidden" name="PaymentSuccessfulURLText" value="Aftur á Nomo!">
    <input type="hidden" name="PaymentSuccessfulAutomaticRedirect" value="1">
    <input type="hidden" name="DisplayBuyerInfo" value="1">
    <input type="hidden" name="RequireName" value="1">
    <input type="hidden" name="RequireAddress" value="1">
    <input type="hidden" name="RequirePostalCode" value="1">
    <input type="hidden" name="RequireCity" value="1">
    <input type="hidden" name="RequirePhone" value="1">
    <input type="hidden" name="HideSSN" value="1">
    <input type="hidden" name="HideCountry" value="1">
    <input type="hidden" name="HideComments" value="1">
    <input type="hidden" name="ReferenceNumber" value="'.$ReferenceNumber.'">
';
//----
//KLÁRA NETGÍRÓ TENGINGU
$SecretKey = hash("sha256","Zoy3C2ANLEYySYLL43gvsp/5Ghty5A/IKRjsFh5X8MpRNirkhKMYXNRfSRRyK0KQsjaMxzsD9+6j+4WkIe6+P1zaFvgarpSW7LHpQ/2lzKVh/0abeIwBbJsDm57hUn9s9cf1eSq+FcvitLv7qtm2Nlz8eb9xTrQOPtOzXUkN2jU=" . "netgiro".$orderCount .$to_be_payed. "881E674F-7891-4C20-AFD8-56FE2624C4B5");
$netgiro_btn .= '
<input type="hidden" name="netgiroSignature" value="'.$SecretKey.'" />
<input type="hidden" name="referenceNumber" value="netgiro'.$orderCount.'" />
<input type="hidden" name="TotalAmount" value="'.$to_be_payed.'" />
<input type="hidden" name="ShippingAmount" value="'.$delivery.'" />
<input type="hidden" name="PaymentSuccessfulURL" value="http://nomo.is/static_pages/takk.php?coupon='.$coupon_code.'&ReferenceNumber='.$ReferenceNumber.'" />
<input type="hidden" name="PaymentConfirmedURL" value="http://nomo.is/static_pages/myipn.php?coupon='.$coupon_code.'&ReferenceNumber='.$ReferenceNumber.'" />
<input type="hidden" name="HandlingAmount" value="0" />
';
//----
        $CreditCard .= $valitor_btn . ' <input type="submit" value="KREDITKORT" class="button payment" style="width:250px; background-color: #009ddf;"></form>';
        $DebitCard .= $valitor_btn . ' <input type="submit" value="DEBETKORT" class="button payment " style="width:250px; background-color: #00a7b8;"></form>';
        $ReikningsVidskipti = ' <a href="/netgiro"><div class="button payment" style="width:218px; font-weight: bold; background-color: #72c09c;"> NETGÍRÓ</div></a>';
    }
?>

<?php 
// CATEGORY MENN

$category_list_menn = "";
$sql = mysql_query("SELECT * FROM category WHERE category_gender = 'menn' ORDER BY category_name ASC"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $cid = $row{"id"};
        $category_name = $row['category_name'];
        $category_id = $row['category_id'];
        $category_description = $row['category_description'];
        $category_gender = $row['category_gender'];
        $category_list_menn .= '
<a href="/flokkur/' . $cid . '"><li>' . $category_name . '</li></a>
';
    } 
} else {
    $category_list_menn = "Það eru engar vörur í búðinni";
} 

// CATEGORY KONUR

$category_list_konur = "";
$sql = mysql_query("SELECT * FROM category WHERE category_gender = 'konur' ORDER BY category_name ASC"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $cid = $row{"id"};
        $category_name = $row['category_name'];
        $category_id = $row['category_id'];
        $category_description = $row['category_description'];
        $category_gender = $row['category_gender'];
        $category_list_konur .= '
<a href="/flokkur/' . $cid . '"><li>' . $category_name . '</li></a>
';
    //UPPERCASING VARIABLES
$category_gender = ucfirst($category_gender);
$category_name = ucfirst($category_name); 
    } 
} else {
    $category_list_konur = "Það eru engar vörur í búðinni";
} 
?>

<?php 
// RUN A SELECT QUERY TO DISPLAY MY CATEGORIES IN THE MENU

$store_list = "";
$sql = mysql_query("SELECT * FROM stores ORDER BY store_name ASC"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $storeid = $row{"id"};
        $store_name = $row['store_name'];
        $store_list .= '
<a href="/verslun/' . $storeid . '"><li>' . $store_name . '</li></a>
';
    } 
} else {
    $category_list_konur = "Engar búðir ná að birtast";
} 
?>


<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Karfan Þín</title>

<link rel="shortcut icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>">
<link rel="icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>" type="image/x-icon">

<link href="../css/styles.css?v=<?php echo $cssVersion; ?>" rel="stylesheet" type="text/css" />
<link href="../cssmenu/menu_assets/styles.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../demo/menu.css" type="text/css" media="screen" />

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

<?php require_once(APPDIR . '/includes/header_1up.php'); ?>
<?php require_once(APPDIR . '/includes/menu_1up.php'); ?>


<div id="main"> 
    <div id="product-wrapper" style="width:80%; min-width:800px; position:relative; top:20px;">
        <div style="    height: auto;
    min-height: 400px;
    margin: 20px auto;
    margin-bottom: 200px;
">
            <?php if(isset($response)) {echo $response;}?>
            <?php if(isset($cartOutput) && $cartOutput != "") {
                echo'
                <h1>Karfan þín!</h1><br/>
            <table class="cart_table_user" style="width:100%">
                <tr style="background-color: #333; color:#fff; font-weight: bold; height:40px; ">
                    <td width="10%">Mynd</td>
                    <td width="14%">Nafn</td>
                    <td width="34%">Vörulýsing</td>
                    <td width="7%">Stærð</td>
                    <td width="10%">Verð</td>
                    <td width="9%">Fjöldi</td>
                    <td width="9%">Fjarlægja</td>
                </tr>
                ';
            }
            ?>
                    <?php echo $cartOutput; ?>
            </table>
            <hr>
            <?php
            if(isset($cartOutput) && $cartOutput != ""){
                echo '<p>Samtals í körfu: <strong>' . $cartTotal . '</strong>kr.<br/>';
                if($discount > 0){
                    echo '<span style="color:red;">-'.$discount.'% afsláttur</span><br/>';
                }
                if($cartTotal<=0){
                    echo "+ Heimsending:" . $delivery . "kr. <br/> <p style='font-size:12px'>Viltu sleppa við heimsendingargjaldið? </br><span style='color: #00B2A0'> Heimsending er ókeypis ef keypt er fyrir 15.000kr eða meira.</span></p>";
                } else {
                    echo "<br/><span style='font-size:18px;'>Frí heimsending, hvert á land sem er!</span> <br/><br/>
                    ";
                }
                echo '<ul>
                <li style="width:250px; text-align:center; margin:10px;">'.$CreditCard.'<hr/>Fylltu inn í upplýsingaformið þegar þú ert kominn inn á <strong>Valitor Greiðslusíðuna</strong>.</li>
                <li style="width:250px; text-align:center; margin:10px;">'.$DebitCard.'<hr/>Smelltu á <strong>"Veskið"</strong> þegar þú ert kominn inn á Valitor greiðslusíðuna.</li>
                <li style="width:250px; text-align:center; margin:10px; position: relative; top:-2px;">'.$ReikningsVidskipti.'<hr/>Fáðu reikning sendan á heimabanka og greiddu reikninginn eftir <strong>14 daga</strong>!</li>
                </ul>';
                if($discount <=0){
                    echo "<br/><form action='' method='post'>
                            <input onchange='moveButton(this.value)' type='text' name='coupon' placeholder='Afsláttarkóði' class='input' style='text-align:center;'>
                            <input type='submit' id='moveButton' value='Staðfesta' class='button' style='background-color:#f9f9f9; color:#444; border:1px solid #ccc; height:33px;position:relative;left:-5px;top:-1px; padding-top: 6px;'>
                        </form>";
                }
                if(isset($coupon_response)){
                    echo $coupon_response;
                }                
                echo '<br/>
                <img src="/images/icons/logo_200.png"> <br/> &<br/><br/>
                <img src="http://www.valitor.com/library/Template/Logos/logo.png">
                <br/><br/>
            <form action="" method="post">
                <input type="hidden" name="empty">
                <input type="submit" class="button" value="Eyða öllu úr körfu">
            </form>
                <br/></p>'
            ;
            } else {
                echo '<br/><br/><img src="/images/icons/clock-icon-150.png"> <br/><br/>
                <h1>Flýttu þér að setja eitthvað í hana. <br/> Henni líður illa þegar hún er tóm.</h1>';
            }
            ?>
            
        </div>
    </div>
            <?php require_once(APPDIR . '/includes/footer_1up.php'); ?>  
</div>

<script type="text/javascript">
  function moveButton(val){
      $('#moveButton').animate({marginLeft: '150px' }, 2000);
  };
</script>

</body>
</html>