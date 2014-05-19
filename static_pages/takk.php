<?php
ob_start();
session_start('');
global $faviconVersion;

include_once '../templates/storescripts/connect_to_mysql.php';

if (isset($_COOKIE{'uid'})){
//Be sure to check if the SESSION details are in fact in the database.
$userID = $_COOKIE{"uid"};
$userID = mysql_real_escape_string($userID);

$sql = mysql_query("SELECT * FROM users WHERE id = '$userID' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $name = $row['name'];
        $username = $row['username'];
        $address = $row['address'];
        $email = $row['email'];
      }
      $name = ucfirst($name); 
}
}
?>

<?php


//NETGÍRÓ BYRJAR

if(isset($_GET['transactionid'])){
    $transactionId = $_GET['transactionid'];
  //GET INFO ABOUT CUSTOMER
            if(isset($_GET['name'])){
              $name = $_GET['name'];
            }
            if(isset($_GET['referenceNumber'])){
              $referenceNumber = $_GET['referenceNumber'];
            }
            if(isset($_GET['email'])){
              $email = $_GET['email'];
            }
            if(isset($_GET['address'])){
              $address = $_GET['address'];
            }
            if(isset($_GET['city'])){
              $city = $_GET['city'];
            }
            if(isset($_GET['country'])){
              $country = $_GET['country'];
            }
            if(isset($_GET['zip'])){
              $zip = $_GET['zip'];
            }
            if(isset($_GET['customerMessage'])){
              $customerMessage = $_GET['customerMessage'];
            }
  //------
  //GET INFO ABOUT ORDER
            if(isset($_GET['coupon'])){
              $coupon = $_GET['coupon'];
            }
            if(isset($_GET['ReferenceNumber'])){
              $ReferenceNumber = $_GET['ReferenceNumber'];
            }
  //-------------
  //SKOÐA HVORT VERIÐ SÉ AÐ TVÍTELJA FÆRSLU
          $sql = mysql_query("SELECT * FROM orders WHERE TransactionNumber = '$referenceNumber' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
          $orderCount = mysql_num_rows($sql);
          if($orderCount>0){
            $message = 'Tvitalning a pontun atti ser stad. Leita af orsok.';
            mail('nomo@nomo.is', 'Tvitalning : Netgíró', $message, 'From: nomo@nomo.is');
            exit();
          }
  //---
  //INSERT INTO Customers AND get ID to put in orders.
    $customersql = mysql_query("SELECT * FROM customers WHERE email = '$email' LIMIT 1");
        $customerCount = mysql_num_rows($customersql);
        if($customerCount < 1){ /*Ef viðskiptavinur hefur ekki keypt áður*/
            mysql_query("INSERT INTO customers (name, email, address, zip, city, order_count, date_added )
            VALUES('$name','$email', '$address', '$zip', '$city', '1' , now())")
            or die(mysql_error());
              $customer_id = mysql_insert_id();  
        } else { /*Ef viðskiptavinur hefur keypt áður, bæta við einum í ordercount*/
          //Finna ID viðskiptavinarins sem er til í database
          while ($row = mysql_fetch_array($customersql)) {
              $customer_id = $row['id'];
          }
          //-------
          mysql_query("UPDATE customers
                       SET order_count = order_count + 1
                       WHERE email = '$email' ");
        } 
  //-------
  //BYRJA EMAILIÐ
    $message .= '<html><body background="">

                      <table width="100%" border="0" cellspacing="20px" cellpadding="0">
                          <tr>
                              <td align="center">
                                  <img src="http://nomo.is/images/nomologo.png" alt="NOMO"/>
                              </td>
                          </tr>
                          <tr>
                              <td align="center">
                                  <h3 style="margin: 0px auto;">Til hamingju '.$name.'!</h3> 
                                  <br/>
                                  <h3 style="margin: 0px auto;">Hér með er eftirfarandi pöntun staðfest:</h3>
                              </td>
                          </tr>
                          <tr>
                              <td align="center">
                                  <table style="border-color: #666; width:100%;" cellpadding="10">
                                  <tr style="background: #eee;">
                                  <td><strong>Verslun</strong> </td>
                                  <td><strong>Nafn Vöru</strong> </td>
                                  <td><strong>Stærð</strong> </td>
                                  <td><strong>Verð</strong> </td>
                                  <td><strong>ID</strong> </td>
                                  </tr>
                          ';
    $self_message .= '<html><body background="" width="100%">

                      <table width="100%" border="0" cellspacing="20px" cellpadding="0">
                          <tr>
                              <td align="center">
                                  <img src="http://nomo.is/images/nomologo.png" alt="NOMO"/>
                              </td>
                          </tr>
                          <tr>
                              <td align="center">
                                  <h3 style="margin: 0px auto;">Netgíró</h3> 
                                  <br/>
                                  <h3 style="margin: 0px auto;">Þessi pöntun var að berast:</h3>
                              </td>
                          </tr>
                          <tr>
                              <td align="center">
                                  <table style="border-color: #666; width:100%;" cellpadding="10">
                                  <tr style="background: #eee;">
                                    <td><strong>'.$name.'</strong> </td>
                                    <td><strong>'.$address.'</strong> </td>
                                    <td><strong>'.$email.'</strong> </td>
                                    <td><strong>'.$city.'</strong> </td>
                                  </tr>
                                  <tr style="background: #eee;">
                                    <td><strong>Verslun</strong> </td>
                                    <td><strong>Nafn Vöru</strong> </td>
                                    <td><strong>Stærð</strong> </td>
                                    <td><strong>Verð</strong> </td>
                                    <td><strong>ID</strong> </td>
                                  </tr>
                                  ';
    $store_message .= '<html><body background="" width="100%">

                      <table width="100%" border="0" cellspacing="20px" cellpadding="0">
                          <tr>
                              <td align="center">
                                  <img src="http://nomo.is/images/nomologo.png" alt="NOMO"/>
                              </td>
                          </tr>
                          <tr>
                              <td align="center">
                                  <h3 style="margin: 0px auto;">Til hamingju!</h3> 
                                  <br/>
                                  <h3 style="margin: 0px auto;">Eftirfarandi pöntun var að eiga sér stað:</h3>
                              </td>
                          </tr>
                          <tr>
                              <td align="center">
                                  <table style="border-color: #666; width:100%;" cellpadding="10">
                                  <tr style="background: #eee;">
                                  <td><strong>Verslun</strong> </td>
                                  <td><strong>Nafn Vöru</strong> </td>
                                  <td><strong>Stærð</strong> </td>
                                  <td><strong>Verð</strong> </td>
                                  <td><strong>ID</strong> </td>
                                  </tr>
                                  ';
    $headers = "From: nomo@nomo.is\r\n" . 
      'X-Mailer: PHP/' . phpversion() . "\r\n" . 
      "MIME-Version: 1.0\r\n" . 
      "Content-Type: text/html; charset=utf-8\r\n" . 
      "Content-Transfer-Encoding: 8bit\r\n\r\n"; 
  //----------
  //Sníða Reference number að okkar þörfum og stofna breytur fyrir framhald.
      $ReferenceNumber = rtrim($ReferenceNumber,",");
      $ReferenceNumberArray = explode(",", $ReferenceNumber);
      $totalamount = 0;
      $size_id = "";
  //---------
  //Brjóta upp ReferenceNumber og finna upplýsingar sem þar dvelja.
    foreach ($ReferenceNumberArray as $key => $value) {
      $product_info = explode("-", $value);
      $id = $product_info[0]; // GET ID
      $amount = $product_info[1]; // GET AMOUNT
      $size_id = $product_info[2]; // GET SIZE_ID
      $discount = $product_info[3]; // GET DISCOUNT
      $currenOrder = $product_info[4]; // GET CURRENT ORDER
      //SÆKJA STÆRÐINA
        $sql4 = mysql_query("SELECT * FROM sub_products WHERE id = '$size_id'");
        $sizeCount = mysql_num_rows($sql4);
            if($sizeCount>0){
                while ($row=mysql_fetch_array($sql4)) {
                    $size = $row{"size"};
                  }
                }
      //--------------
    //-----------
    //SÆKJA UPPLÝSINGAR UM VÖRUNA Í GAGNAGRUNN TIL ÞESS AÐ SENDA VERSLUNUM
      $sql = mysql_query("SELECT * FROM products WHERE id = '$id'");
        while ($row = mysql_fetch_array($sql)) {
            $store = $row{"store"};
            $sql = mysql_query("SELECT * FROM products WHERE id = '$id' AND store = '$store' LIMIT 1");
              while ($row = mysql_fetch_array($sql)) { 
                    $product_name = $row{"product_name"};
                    $price = $row{"price"};
                    $x = $row{"dcount"};
                    $category = $row{"category"};
                    $subcategory = $row{"subcategory"};
                    //EF AFSLÁTTUR, SETJA HANN Á VERÐIÐ
                    if ($x>0){
                          $realprice = round((1-($x/100))*$price);
                        } else {
                          $realprice = $price;
                        } //ELSE ENDAR
                    //------------
                } //WHILE ENDAR
            //FINNA EMAIL VERSLUNAR TIL ÞEIRRA EMAIL
              $sql = mysql_query("SELECT * FROM stores WHERE store_name = '$store' LIMIT 1");
              while ($row = mysql_fetch_array($sql)) {
              $store_email = $row['email'] ;
              } //LITLA WHILE ENDAR    
              $store_message .=  '<tr>
                                  <td><strong>'.$store.'</strong> </td>
                                  <td>'.$product_name.'</td>
                                  <td>'.$size.' </td>
                                  <td>'.$realprice.' kr.</td>
                                  <td>'.$transactionId.' </td>
                                  </tr>';
              $store_email_array .= $store_email.", ";
      } // STÓRA WHILE ENDAR
    //--------
  //SÆKJA UPPLÝSINGAR UM VÖRURNAR TIL ÞESS AÐ SETJA Í GAGNAGRUNN
    $sql = mysql_query("SELECT * FROM products WHERE id = '$id' LIMIT 1");
      while ($row = mysql_fetch_array($sql)) {
        $product_name = $row{"product_name"};
        $price = $row{"price"};
        $x = $row{"dcount"};
        $store = $row{"store"};
        $category = $row{"category"};
        $subcategory = $row{"subcategory"};
          //EF AFSLÁTTUR, SETJA HANN Á VERÐIÐ
          if ($x>0){
                $realprice = round((1-($x/100))*$price);
              } else {
                $realprice = $price;
              } //ELSE ENDAR
    //------------
    //BÆTA HVERRI EINUSTU VÖRU INN Í ORDERS ÞVÍ ALLAR BÚÐIR VERÐA AÐ SJÁ SÍNAR PANTANIR
    $sql = mysql_query("INSERT INTO orders (TransactionNumber, customer_id, price, customer_name, email, customer_address, customer_postal, customer_city, product_name, product_id, quantity, size, date_added, store, gender, status, discount, coupon)
                        VALUES('$referenceNumber', '$customer_id', '$realprice', '$name', '$email', '$address', '$zip', '$city', '$product_name', '$id' , '1', '$size', now(), '$store', '$category', '0', '$discount', '$coupon')")
                        or die(mysql_error());
    //---------
    //MINNKA AVAILABLE HJÁ HVERRI SUBPRODUCT UM EINN
    $sql = mysql_query("SELECT * FROM sub_products WHERE product_id = '$id'"); //SELECT * ÞÝÐIR SELECT ALL
        $productCount = mysql_num_rows($sql);
          if($productCount>0){
              while ($row=mysql_fetch_array($sql)) {
                  $subid = $row{"id"};
                  $subsize = $row{"size"};
                  $subavailable = $row{"available"};
                  $new_available = $subavailable - 1;
                  $sql = mysql_query("UPDATE sub_products SET available = '$new_available' WHERE product_id = '$id' AND size = '$size' ;")or die(mysql_error());
    //------------               
                  } //WHILE ENDAR
              } //IF ENDAR
                    $totalamount = $realprice + $totalamount;
                    $message .= ' <tr>
                                  <td>'.$store.' </td>
                                  <td>'.$product_name.' </td>
                                  <td>'.$size.' </td>
                                  <td>'.$realprice.'kr. </td>
                                  <td>'.$TransactionNumber.'</td>
                                  </tr>';
                    $self_message .= ' <tr>
                                  <td>'.$store.' </td>
                                  <td>'.$product_name.' </td>
                                  <td>'.$size.' </td>
                                  <td>'.$realprice.'kr. </td>
                                  <td>'.$orderid.'</td>
                                  </tr>';
            } //STÓRA WHILE ENDAR
          } //FOREACH ENDAR
  //KLÁRA SKILABOÐIN
      $message .='
            </td>
            </tr>
            <tr>
                    <td align="center">
            <h3 style="margin: 0px auto;">Þessi virkilega smekklega pöntun verður sent til "'.$address.','.$zip.' '.$city.'" innan 48 klst! <br/> Er þetta í alvöru svona einfalt? </h3>
                    </td>
            </tr>
            </table>
            <hr/>
            </body></html>
            '
            ;
      $self_message .='
            </td>
            </tr>
            <tr>
                  <td align="center">
            <h3 style="margin: 0px auto;">Jæja strákar, drullumst til að senda þessa pöntun til: '.$address.','.$zip.' '.$city.': innan 48 klst! <br/> #KOMASVO</h3>
                  </td>
            </tr>
            </table>
            <hr/>
            </body></html>
            '
            ;
      $store_message .='
            </td>
            </tr>
            <tr>
                  <td align="center">
            <h3 style="margin: 0px auto;">Vinsamlegast taktu frá þær vörur sem eru frá <span style="font-weight:thin">þinni verslun</span>, í þeim stærðum sem viðskiptavinur keypti þær í. <br/> Gerum kúnnanum greiða og látum þetta allt smella saman! <br/> #TeamWork</h3>
                  </td>
            </tr>
            </table>
            <hr/>
            </body></html>
            '
            ;
      $store_email_array = trim($store_email_array, ",");
      mail($store_email_array, 'Ný pöntun!', $store_message, $headers);
      mail($Email, 'Staðfesting á kaupum', $message, $headers);
      mail('sala@nomo.is', 'Money in da bank!', $self_message, $headers);
  //----------------

} //NETGÍRÓ ENDAR


?>

<?php 
// RUN A SELECT QUERY TO DISPLAY USER INFO

$sql = mysql_query("SELECT * FROM users WHERE id = $userID"); //SELECT * ÞÝÐIR SELECT ALL
$userCount = mysql_num_rows($sql);
if($userCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $id = $row{"id"};
        $email = $row{"email"};
    } 
} 
?>

<?php
//UNSET THE SESSION ARRAYS
    unset($_SESSION["cart_array"]);
    unset($_SESSION["cartTotal"]);

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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Takk fyrir að vera þú!</title>

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
<script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=234032696651711";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<?php include_once("../includes/header_1up.php"); ?>
<?php include_once("../includes/menu_1up.php"); ?>

<div id="main" style="background-image: url(../images/scribble_light.png)"> 
          <div id="email_wrapper"><div class="max-width">
          <h1 style="font-size:50px;">Takk kærlega!</h1>
            <p style="margin-bottom:20px; font-size:20px;" >Þú valdir vel og ert greinilega manneskja með stíl.</p>
            <p>Við kunnum virkilega vel við þig og það traust sem þú sýnir jafn ungu fyrirtæki og <strong>Nomo</strong> er. Við munum ganga yfir fjöll og fyrnindi til þess að koma sendingunni þinni heim til þín á réttum tíma og í toppstandi.</p>
              <div class="fb-like" data-href="https://www.facebook.com/pages/Nomo/576038825777229" data-width="450" data-layout="button_count" data-show-faces="true" data-send="true"></div>
              <br/><br/>
            </div>
<iframe width="560" height="315" src="//www.youtube.com/embed/TAryFIuRxmQ?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>        </div>
        <?php include_once("../includes/footer_1up.php"); ?>
</div>





</body>
</html>
