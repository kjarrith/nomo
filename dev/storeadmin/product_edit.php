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
//HÉR ER VERIÐ AÐ GÁ HVORT URL BREYTAN SÉ TIL Í KERFINU
if(isset($_GET['id'])){
    $id = preg_replace('#[^0-9]#i', '', $_GET['id']);
    $_SESSION{"editid"} = $id;
        $sql = mysql_query("SELECT * FROM products WHERE id='$id' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
        $productCount = mysql_num_rows($sql);
        if($productCount>0){
            //NÁ Í ALLAR UPPLÝSINGAR UM VÖRUNA OG VERA MEÐ ÞÆR TIL STAÐAR
            while ($row=mysql_fetch_array($sql)) {
                $id = $row{"id"};
                $product_name = $row{"product_name"};
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '-second_thumb.jpg" class="p-image bottom"></a>
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '_thumb.jpg" class="p-image top"></a>
                $price = $row{"price"};
                $category = $row{"category"};
                $subcategory = $row['subcategory'];
                $style_id = $row['style_id'];
                $description = $row{"description"};
                $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));

            }
        } else {
            echo "Thad er ekki til vara med thessu ID numeri i kerfinu";
            exit();
        }
} else {
  $id =  $_SESSION{"editid"};
$sql = mysql_query("SELECT * FROM products WHERE id='$id' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
        $productCount = mysql_num_rows($sql);
        if($productCount>0){
            //NÁ Í ALLAR UPPLÝSINGAR UM VÖRUNA OG VERA MEÐ ÞÆR TIL STAÐAR
            while ($row=mysql_fetch_array($sql)) {
                $id = $row{"id"};
                $product_name = $row{"product_name"};
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '-second_thumb.jpg" class="p-image bottom"></a>
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '_thumb.jpg" class="p-image top"></a>
                $price = $row{"price"};
                $category = $row{"category"};
                $subcategory = $row['subcategory'];
                $style_id = $row['style_id'];
                $description = $row{"description"};
                $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));

            }
        } else {
            echo "Thad er ekki til vara med thessu ID numeri i kerfinu";
            exit();
        }
} 
?>

<?php
//Breyta þeim upplýsingum sem beðið erum að breyta í forminu.

//BREYTA UNDIRFLOKK
if(isset($_POST{'subcategory'})) {
$subcategory = $_POST{'subcategory'};
  $sql = mysql_query("UPDATE waitlist SET subcategory = '$subcategory' WHERE id = '$id';")or die(mysql_error());
  header("location:product_edit.php");
  exit();
}

//BREYTA NAFNI
if(isset($_POST{'name'})) {
$name = $_POST{'name'};
  $sql = mysql_query("UPDATE waitlist SET product_name = '$name' WHERE id = '$id';")or die(mysql_error());
  header("location:product_edit.php");
  exit();
}
//BREYTA LÝSINGU
if(isset($_POST{'description'})) {
$description = $_POST{'description'};
  $sql = mysql_query("UPDATE waitlist SET description = '$description' WHERE id = '$id';")or die(mysql_error());
  header("location:product_edit.php");
  exit();
}
//BREYTA VERÐI
if(isset($_POST{'price'})) {
$price = $_POST{'price'};
  $sql = mysql_query("UPDATE waitlist SET price = '$price' WHERE id = '$id';")or die(mysql_error());
  header("location:product_edit.php");
  exit();
}
?>

<?php
//DISPLAY SIZES WITH THE SAME STYLE ID
$size_list = "";
$sql = mysql_query("SELECT * FROM sub_products WHERE style_id = '$style_id'"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $subid = $row{"id"};
        $subsize = $row{"size"};
        $subavailable = $row{"available"};
        $size_list .= '
<li> 
' . $subsize . ' 
<form action ="product_edit.php" method="post"> 
    <input type="text" class="input align_left" name="quantity" value="' . $subavailable . '" size="1" maxlength="2"/>
    <input name="adjustbtn" type="submit" class="button" Value="Breyta" style="font-size:12px;"/> 
    <input name="item_to_adjust" type="hidden" value="' . $subid . '"/> 
                            </form>                    
<br/>
</li>';
    } 
} else {
    $size_list = 'Það eru engar stærðir til, búðu til nýja.';
} 
?>
<?php
//IF USER CHOOSES TO ADJUST ITEM QUANTITY
if(isset($_POST['item_to_adjust'])&& $_POST['item_to_adjust'] !="") {
    //ADJUST THE QUANTITY
    $item_to_adjust = $_POST['item_to_adjust'];
    $quantity = $_POST['quantity'];
    $quantity = preg_replace('#[^0-9]#i', '', $quantity);
    if ($quantity >= 100) {
        $quantity = 99;         //HÉR ERUM VIÐ AÐ GERA ÞAÐ AÐ VERKUM AÐ EKKI SÉ HÆGT AÐ HAFA FLEIRI EN 100 EÐA FÆRRI EN 1
    }
    if ($quantity < 1) {$quantity=1;}
     $sql = mysql_query("UPDATE sub_products SET available = '$quantity' WHERE id = '$item_to_adjust';")or die(mysql_error());
        header("location:product_edit.php");
        exit();
}
?>

<?php
//BÚA TIL NÝJA STÆRÐ

if(isset($_POST{'new_size'})) {
    $new_size = ($_POST{'new_size'});
    $new_size = mysql_real_escape_string($new_size);
    $new_available = ($_POST{'new_available'});
    $new_available = mysql_real_escape_string($new_available);
    $new_available = preg_replace('#[^0-9]#i', '', $new_available);
        if ($new_available >= 100) {
        $new_available = 99;         //HÉR ERUM VIÐ AÐ 
            }
        if ($new_available < 1) {$new_available=1;}
//ADD [USER] TO DATABASE
    $sql = mysql_query("INSERT INTO sub_products (style_id, size, available) VALUES('$style_id', '$new_size', '$new_available') ")or die(mysql_error());
header("location:product_edit.php");
exit();

}
?>

<?php 
// RUN A SELECT QUERY TO DISPLAY MY CATEGORIES IN THE MENU

$category_list = "";
$sql = mysql_query("SELECT * FROM category WHERE category_gender = '$category' ORDER BY id DESC LIMIT 20"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $cat_id = $row{"id"};
        $category_name = $row['category_name'];
        $category_id = $row['category_id'];
        $category_description = $row['category_description'];
        $category_gender = $row['category_gender'];
        $category_list .= '
<option value="' . $category_id . '">' . $category_name . '</option>
';
    } 
} else {
    $category_list = "Það eru engar vörur í búðinni";
} 
?>

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title><?php echo $product_name; ?></title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />

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

<div id="main"> 
    <div class="display-all">
        <div class="display-left">
             <div class="img-container">
        <div class="bigpic">
            <img src="../images/inventory/<?php echo $id; ?>.jpg">

            <div class="more-imgs"> <h3> Fleiri myndir </h3></div>
        </div>

    </div>
        </div>
        <div class="display-right">
          <?php
          echo '
                    <form action="product_edit.php" enctype="multipart/form-data" name="edit" method="post">
                    '.$subcategory.' - 
                <select name="subcategory" id="subcategory" style="margin-top:20px; height:30px;">
                <option>Veldu flokk!</option>
                    ' . $category_list . '
                </select> 
                <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          <form action="product_edit.php" enctype="multipart/form-data" name="edit" method="post">
            <p style="font-size:12px;"> Nafn: </p> <input name="name" class="input align_left" type="text" placeholder="' . $product_name . '">
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          <form action="product_edit.php" enctype="multipart/form-data" name="edit" method="post">
            <p style="font-size:12px;"> Lýsing:</p> <input name="description" class="input align_left"  type="text" placeholder="' . $description . '"> 
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          <form action="product_edit.php" enctype="multipart/form-data" name="edit" method="post">
            <p style="font-size:12px;"> Verð: </p> <input name="price"  class="input align_left" type="text" size="6" placeholder="' . $price . '">
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          '
          ?>
            <hr/>
          <ul>
            <?php
              echo $size_list;
            ?>
            <form method="post" action="product_edit.php" name="create_size" >
                <input type="text" name="new_size"  class="input align_left" placeholder="Ný stærð" />
                <input type="text" name="new_available"  class="input align_left" placeholder="Fjöldi í stærð" size="9" maxlength="2"/>
                <input type="submit" class="button margin" value="Búa til stærð" name="submit" />
</form>
          </ul>
              
        </div>
    </div>
    <div style="min-width: 702px;">
<?php require_once(APPDIR . '/includes/footer_1up.php'); ?> 
  </div> 
</div>



</body>
</html>