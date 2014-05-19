<?php
global $cssVersion;
global $faviconVersion;
ob_start();
session_start('oid');
$discount = 0;
$coupon_code = "";

if (isset($_COOKIE{'uid'})){
//Be sure to check if the SESSION details are in fact in the database.
$userID = $_COOKIE{"uid"};

$sql = mysql_query("SELECT * FROM users WHERE id = '$userID' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $discount = $row['discount'];
      }
      $name = ucfirst($name); 
}


}

//CONNECT TO THE DATABASE
include 'storescripts/connect_to_mysql.php';
?>

<?php
//ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 'On');

?>

<?php

/*
Netgíró Post-ið
-------------*/
$ReikningsVidskipti ="";
if(isset($_POST{'name'})) {
    $name = mysql_real_escape_string($_POST['name']);
    $gender = mysql_real_escape_string($_POST['gender']);
    $zip = mysql_real_escape_string($_POST['postal_code']);    
    $address = mysql_real_escape_string($_POST['address']); 
    $city =  mysql_real_escape_string($_POST['city']); 
    $email =  mysql_real_escape_string($_POST['email']); 

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
      //SETJA SAMAN NETGÍRÓ TAKKANN
          $netgiro_btn .= '<form action="https://www.netgiro.is/SecurePay/" method="post">
          <input type="hidden" name="ApplicationID" value="6ca5c96d-cbc1-4d47-b988-1fcbca0520c8" />
          <input type="hidden" name="Iframe" value="true" />
          <input type="hidden" name="ReturnCustomerInfo" value="false" />
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
      $ReferenceNumber .= $id."-1-".$each_item['size']."-".$discount."-".$CurrentOrder.",";
  //---
        $i++;
          } //ENDA FOREACH
      $y1 = $y +1;
      if($cartTotal > $HeimsendingarMinimum) {
          $delivery = 0;
          $deliveryinfo = '';
      } else {
          $delivery = 1000;
  //HEIMSENDING
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
  //KLÁRA NETGÍRÓ TENGINGU
  $SecretKey = hash("sha256","Zoy3C2ANLEYySYLL43gvsp/5Ghty5A/IKRjsFh5X8MpRNirkhKMYXNRfSRRyK0KQsjaMxzsD9+6j+4WkIe6+P1zaFvgarpSW7LHpQ/2lzKVh/0abeIwBbJsDm57hUn9s9cf1eSq+FcvitLv7qtm2Nlz8eb9xTrQOPtOzXUkN2jU=" . "netgiro".$orderCount .$to_be_payed. "881E674F-7891-4C20-AFD8-56FE2624C4B5");
  $netgiro_btn .= '
  <input type="hidden" name="netgiroSignature" value="'.$SecretKey.'" />
  <input type="hidden" name="referenceNumber" value="netgiro'.$orderCount.'" />
  <input type="hidden" name="TotalAmount" value="'.$to_be_payed.'" />
  <input type="hidden" name="ShippingAmount" value="'.$delivery.'" />
  <input type="hidden" name="PaymentSuccessfulURL" value="http://nomo.is/static_pages/takk.php?coupon='.$coupon_code.'&ReferenceNumber='.$ReferenceNumber.'&name='.$name.'&email='.$email.'&city='.$city.'&zip='.$zip.'&address='.$address.'" />
  <input type="hidden" name="PaymentConfirmedURL" value="http://nomo.is/static_pages/myipn.php?coupon='.$coupon_code.'&ReferenceNumber='.$ReferenceNumber.'" />
  <input type="hidden" name="HandlingAmount" value="0" />
  ';
  //----
          $ReikningsVidskipti .= $netgiro_btn . ' <input type="submit" value="Ganga frá greiðslu" class="button action" style="width:250px;"></form>';
      }
} //CLOSE IF ISSET NAME

?>
<!DOCTYPE html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Búðu til aðgang!</title>

<link rel="shortcut icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>">
<link rel="icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>" type="image/x-icon">

<link href="../css/styles.css?v=<?php echo $cssVersion; ?>" rel="stylesheet" type="text/css" />

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

<?php require_once(APPDIR . '/includes/header_simple.php'); ?>

<div id="main-sans-menu" style="background-image: url('../images/fullscreen/nomohome-4.jpg'); padding-bottom: 50px;"> 
 
<div id="accounts-wrapper" >

                <?php if(isset($ReikningsVidskipti) & $ReikningsVidskipti != "")  {
                  echo $ReikningsVidskipti;
                } else {
                  echo '
                <form method="post" action="" onsubmit="return validateForm()" name="createaccount" >
                  <h3 style="font-size: 30px; color: #333;"> Upplýsingar um viðskiptavin<br/> </h3>
                    <p style="color:#555;">Vinsamlegast segðu okkur frá þér svo hægt sé að senda pöntunina heim til þín.</p>
                                      <p>
                        <input type="text" name="name" class="input bigger" placeholder="Fullt Nafn" />
                    </p>
                    <p>
                        <input type="email" name="email" class="input bigger" placeholder="Netfang" />
                    </p>  
                    <p>
                        <input type="text" name="postal_code"  class="input bigger" placeholder="Póstnr." size="3" maxlength="3"/>
                        <input type="text" name="city"  class="input bigger" placeholder="Borg" size="10" />
                    </p>
                    <p>
                        <input type="text" name="address"  class="input bigger" placeholder="Heimilisfang" size="10" />
                    </p>
              <div class="radio-toolbar">
                 
                  <input type="radio" id="radio1" name="gender" value="karl" checked>
                  <label for="radio1">Strákur</label>

                  <input type="radio" id="radio2" name="gender" value="kona">
                  <label for="radio2">Stelpa</label>
              </div> 
              <br/>
                    <p>
                        <input type="submit" class="button margin" value="HALDA ÁFRAM" name="submit" style="width:250px;" />
                    </p>
              
            </form>

                ';
                }?>
        </div>     
        <br/><br/>
    </div>

<script type="text/javascript">
  function validateForm()
  {
  var x=document.forms["createaccount"]["name"].value;
  if (x==null || x=="")
    {
    alert("Þú gleymdir að segja okkur hvað þú heitir :)");
    return false;
    }
  var x=document.forms["createaccount"]["email"].value;
  var atpos=x.indexOf("@");
  var dotpos=x.lastIndexOf(".");
  if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
    {
    alert("Ertu alveg viss um að þetta sé rétta netfangið þitt?");
    return false;
    }
  }

</script>

</body>
</html>