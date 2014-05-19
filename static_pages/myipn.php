<?php
include '../templates/storescripts/connect_to_mysql.php';

//STELA GET BREYTUM OG SETJA INN Í KERFIÐ SEM EITT STK PÖNTUN!

if(isset($_GET['TransactionNumber'])){ // VALITOR BYRJAR
        if (1===1/*$_GET['DigitalSignatureResponse'] === $hash*/ ) { //CHECK IF LEGAL
          if(isset($_GET['Name'])){
            $Name = $_GET['Name'];
          }
          if(isset($_GET['ReferenceNumber'])){
            $ReferenceNumber = $_GET['ReferenceNumber'];
          }
          if(isset($_GET['Address'])){
            $Address = $_GET['Address'];
          }
          if(isset($_GET['PostalCode'])){
            $PostalCode = $_GET['PostalCode'];
          }
          if(isset($_GET['City'])){
            $City = $_GET['City'];
          }
          if(isset($_GET['Email'])){
            $Email = $_GET['Email'];
          }
          if(isset($_GET['Phone'])){
            $Phone = $_GET['Phone'];
          }
          if(isset($_GET['TransactionNumber'])){
            $TransactionNumber = $_GET['TransactionNumber'];
          }
          if(isset($_GET['coupon'])){
            $coupon = $_GET['coupon'];
          }

          //Athuga hvort sé verið að tvítelja færsluna
          $sql = mysql_query("SELECT * FROM orders WHERE TransactionNumber = '$TransactionNumber' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
          $productCount = mysql_num_rows($sql);
          if($productCount>0){
            $message = 'Tvitalning a pontun atti ser stad. Leita af orsok.';
            mail('nomo@nomo.is', 'Tvitalning : Valitor', $message, 'From: nomo@nomo.is');
            exit();
          } //Ef ekki tvítalið, þá er haldið áfram.

        mysql_query("INSERT INTO transactions (transaction, date_added)
                            VALUES('$TransactionNumber', now())
                            ")
                            or die(mysql_error());
            //INSERT INTO Customers AND get ID to put in orders.
                $sql = mysql_query("SELECT * FROM customers WHERE email = '$Email' LIMIT 1");
                    $customerCount = mysql_num_rows($sql);
                    if($customerCount<1){ /*Ef viðskiptavinur hefur ekki keypt áður*/
                        mysql_query("INSERT INTO customers (name, email, address, zip, city, order_count, date_added )
                        VALUES('$Name','$Email', '$Address', '$PostalCode', '$City', 1 , now())")
                        or die(mysql_error());
                          $customer_id = mysql_insert_id();  
                    } else { /*Ef viðskiptavinur hefur keypt áður, bæta við einum í ordercount*/
                      //Finna ID viðskiptavinarins sem er til í database
                      while ($row = mysql_fetch_array($sql)) {
                          $customer_id = $row['id'];
                      }
                      //-------
                      mysql_query("UPDATE customers
                                   SET order_count = order_count + 1
                                   WHERE email = '$Email' ");
                    } 
            //-------

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
                              <h3 style="margin: 0px auto;">Valitor</h3> 
                              <br/>
                              <h3 style="margin: 0px auto;">Þessi pöntun var að berast:</h3>
                          </td>
                      </tr>
                      <tr>
                          <td align="center">
                              <table style="border-color: #666; width:100%;" cellpadding="10">
                              <tr style="background: #eee;">
                                <td><strong>'.$Name.'</strong> </td>
                                <td><strong>'.$Address.'</strong> </td>
                                <td><strong>'.$Email.'</strong> </td>
                                <td><strong>'.$Phone.'</strong> </td>
                                <td><strong>'.$City.'</strong> </td>
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
          //Brjóta upp söluna og sækja upplýsingar um vörurnar.
          $ReferenceNumber = rtrim($ReferenceNumber,",");
          $ReferenceNumberArray = explode(",", $ReferenceNumber);
          $totalamount = 0;
          $size_id = "";
          //---------
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
            $sql = mysql_query("SELECT * FROM products WHERE id = '$id'");
            while ($row = mysql_fetch_array($sql)) {
                $store = $row{"store"};
                $sql = mysql_query("SELECT * FROM products WHERE id = '$id' AND store = '$store' LIMIT 1");
                while ($row = mysql_fetch_array($sql)) { 
                  $product_name = $row{"product_name"};
                  $product_name = strtolower($product_name); 
                  $product_name = ucfirst($product_name); 
                  $price = $row{"price"};
                  $x = $row{"dcount"};
                  $category = $row{"category"};
                  $subcategory = $row{"subcategory"};
                  if ($x>0){
                        $realprice = round((1-($x/100))*$price);
                      } else {
                        $realprice = $price;
                      } //ELSE ENDAR
                } //WHILE ENDAR
               $sql = mysql_query("SELECT * FROM stores WHERE store_name = '$store' LIMIT 1");
                  while ($row = mysql_fetch_array($sql)) {
                  $store_email = $row['email'] ;
                  } //LITLA WHILE ENDAR    
                $store_message .=  '<tr>
                                    <td><strong>'.$store.'</strong> </td>
                                    <td>'.$product_name.'</td>
                                    <td>'.$size.' </td>
                                    <td>'.$realprice.' kr.</td>
                                    <td>'.$TransactionNumber.' </td>
                                    </tr>';
                $store_email_array .= $store_email.", ";
            } // STÓRA WHILE ENDAR

            //SÆKJA UPPLÝSINGAR UM VÖRURNAR TIL AÐ SENDA OKKUR OG HINUM
            $sql = mysql_query("SELECT * FROM products WHERE id = '$id' LIMIT 1");
              while ($row = mysql_fetch_array($sql)) {
                $product_name = $row{"product_name"};
                $price = $row{"price"};
                $x = $row{"dcount"};
                $store = $row{"store"};
                $category = $row{"category"};
                $subcategory = $row{"subcategory"};
                    if ($x>0){
                        $realprice = round((1-($x/100))*$price);
                      } else {
                        $realprice = $price;
                      } //ELSE ENDAR
                //BÆTA HVERRI EINUSTU VÖRU INN Í ORDERS ÞVÍ ALLAR BÚÐIR VERÐA AÐ SJÁ SÍNAR PANTANIR
                $sql = mysql_query("INSERT INTO orders (TransactionNumber, customer_id, customer_phone, price, customer_name, email, customer_address, customer_postal, customer_city, product_name, product_id, quantity, size, date_added, store, gender, status, discount, coupon)
                                    VALUES('$TransactionNumber', '$customer_id', '$phone', '$realprice', '$Name', '$Email', '$Address', '$PostalCode', '$City', '$product_name', '$id','1', '$size', now(), '$store', '$category', '0', '$discount', '$coupon')")
                                    or die(mysql_error());
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
                                <td>'.$TransactionNumber.'</td>
                                </tr>';
          } //STÓRA WHILE ENDAR
        } //FOREACH ENDAR
            $message .='
                  </td>
                  </tr>
                  <tr>
                          <td align="left">
                  <h3 style="margin: 0px auto;">Þessi virkilega smekklega pöntun verður sent á '.$Address.','.$PostalCode.' '.$City.' innan 48 klst! <br/> Er þetta í alvöru svona einfalt? <br/> já. <br/> #Nomo</h3>
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
                  <h3 style="margin: 0px auto;">Jæja strákar, drullumst til að senda þessa pöntun til: '.$Address.','.$PostalCode.' '.$City.': innan 48 klst! <br/> #KOMASVO</h3>
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

        } /*SIGNATURE CHECK IF ENDAR*/ else {
            $message = 'DigitalSignatureResponse ekki rett stillt';
            mail('nomo@nomo.is', 'Digital Response Code', $message, 'From: nomo@nomo.is');
        } //ELSE ENDAR
} //VALITOR ENDAR

?>
<html>
<head>
</head>

<body >
Hello World
<br/>
<?php echo $product_name; ?>
</body>
</html>
