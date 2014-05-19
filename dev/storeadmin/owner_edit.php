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
                $price = $row{"price"};
                $category = $row{"category"};
                $comment = $row{"comment"};
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
                $price = $row{"price"};
                $category = $row{"category"};
                $comment = $row{"comment"};
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
//SPURNING TIL STJÓRNANDA UM HVORT EYÐA EIGI VÖRU OG EYÐA VÖRU EF ÞAÐ ER VALIÐ
if(isset($_GET{'deleteid'})) {
    echo 'Ertu alveg viss um ad thu viljir eyda voru NR. '. $_GET{'deleteid'}. '? <br/> <a href="owner_edit.php?yesdelete='.$_GET{'deleteid'}.'">Ja</a>- <a href="owner_edit.php">Nei<a/> ';
    exit();
}

// REMOVE ITEM FROM SYSTEM AND DELETE ITS PICTURE
if(isset($_GET['yesdelete'])) {
    //DELETE FROM DATABASE
    $id_to_delete = $_GET['yesdelete'];
    $sql = mysql_query("DELETE FROM sub_products WHERE id = '$id_to_delete' LIMIT 1") or die(mysql_error());
    header("location: owner_edit.php");
    exit();
}
?>

<?php
//Breyta þeim upplýsingum sem beðið erum að breyta í forminu.
//BREYTA UNDIRFLOKK
if(isset($_POST{'subcategory'})) {
$subcategory = $_POST{'subcategory'};
  $sql = mysql_query("UPDATE products SET subcategory = '$subcategory' WHERE id = '$id';")or die(mysql_error());
  header("location:owner_edit.php");
  exit();
}

//BREYTA NAFNI
if(isset($_POST{'name'})) {
$name = $_POST{'name'};
  $sql = mysql_query("UPDATE products SET product_name = '$name' WHERE id = '$id';")or die(mysql_error());
  header("location:owner_edit.php");
  exit();
}
//BREYTA LÝSINGU
if(isset($_POST{'description'})) {
$description = $_POST{'description'};
  $sql = mysql_query("UPDATE products SET description = '$description' WHERE id = '$id';")or die(mysql_error());
  header("location:owner_edit.php");
  exit();
}
//BREYTA VERÐI
if(isset($_POST{'price'})) {
$price = $_POST{'price'};
  $sql = mysql_query("UPDATE products SET price = '$price' WHERE id = '$id';")or die(mysql_error());
  header("location:owner_edit.php");
  exit();
}
//BREYTA gender
if(isset($_POST{'category'})) {
$category = $_POST{'category'};
  $sql = mysql_query("UPDATE products SET category = '$category' WHERE id = '$id';")or die(mysql_error());
  header("location:owner_edit.php");
  exit();
}
//BREYTA NEITUNARKÓÐA
if(isset($_POST{'comment'})) {
$comment = $_POST{'comment'};
  $sql = mysql_query("UPDATE products SET comment = '$comment' WHERE id = '$id';")or die(mysql_error());
  header("location:owner_edit.php");
  exit();
}
?>

<?php
//DISPLAY SIZES WITH THE SAME STYLE ID
$size_list = "";
$sql = mysql_query("SELECT * FROM sub_products WHERE product_id = '$id'"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $subid = $row{"id"};
        $subsize = $row{"size"};
        $subavailable = $row{"available"};
        $size_list .= '<tr>';
$size_list .= '<td> '. $subsize . '</td>';
$size_list .= '<td> <form action ="owner_edit.php" method="post"> 
    <input type="text" class="input align_left" name="quantity" value="' . $subavailable . '" size="1" maxlength="2"/>
    <input name="adjustbtn" type="submit" class="button" Value="Breyta" style="font-size:12px;"/>
    <a class="button" href="owner_edit.php?deleteid='.$subid.'">X</a>
    <input name="item_to_adjust" type="hidden" value="' . $subid . '"/> 
                            </form>                    
<br/>
</td>';
$size_list .= '</tr>'
;
    } 
} else {
    $response = '<h5>Það eru engar stærðir til, búðu til nýja!</h5>';
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
    if ($quantity < 1) {$quantity=0;}
     $sql = mysql_query("UPDATE sub_products SET available = '$quantity' WHERE id = '$item_to_adjust';")or die(mysql_error());
        header("location:owner_edit.php");
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
header("location:owner_edit.php");
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

<?php
$image_list = '';

$file = 'http://nomo.is/images/inventory/' . $id . '.jpg';
$file_headers = @get_headers($file);
if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
    $exists = false;
}
else {
        $image_list .=  '<li>
                <img class="etalage_thumb_image" src="http://nomo.is/images/inventory/' . $id . '_thumb.jpg">
                <img class="etalage_source_image" src="http://nomo.is/images/inventory/' . $id . '.jpg">
            </li>';
}

$file2 = 'http://nomo.is/images/inventory/' . $id . '-second.jpg';
$file_headers = @get_headers($file2);
if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
    $exists = false;
}
else {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="http://nomo.is/images/inventory/' . $id . '-second_thumb.jpg">
                <img class="etalage_source_image" src="http://nomo.is/images/inventory/' . $id . '-second.jpg">
            </li>';
}

$file3 = 'http://nomo.is/images/inventory/' . $id . '-third.jpg';
$file_headers = @get_headers($file3);
if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
    $exists = false;
}
else {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="http://nomo.is/images/inventory/' . $id . '-third_thumb.jpg">
                <img class="etalage_source_image" src="http://nomo.is/images/inventory/' . $id . 's-third.jpg">
            </li>';
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

<link type="text/css" rel="stylesheet" href="../css/etalage.css" /> 

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
<script type="text/javascript" src="../js/jquery.etalage.min.js"></script> 

<script type="text/javascript">
$(document).ready(function(){
// If your <ul> has the id "etalage":
$('#etalage').etalage();
});
</script>


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
    <div class="display-all-edit">
        <div class="display-left">
                    <?php
          echo '
          <form action="owner_edit.php" enctype="multipart/form-data" name="edit" method="post">
                    '.$category.' - 
                <select name="category" id="subcategory" style="margin-top:20px; height:30px;">
                <option>Veldu kyn!</option>
                  <option value="menn">karl</option>
                  <option value="konur">kona</option>    
                </select> 
                <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>  
          <form action="owner_edit.php" enctype="multipart/form-data" name="edit" method="post">
                    '.$subcategory.' - 
                <select name="subcategory" id="subcategory" style="margin-top:20px; height:30px;">
                <option>Veldu flokk!</option>
                    ' . $category_list . '
                </select> 
                <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>       
          <form action="owner_edit.php" enctype="multipart/form-data" name="edit" method="post">
            <p style="font-size:12px;"> Nafn: </p> <input name="name" class="input align_left" type="text" placeholder="' . $product_name . '">
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          <form action="owner_edit.php" enctype="multipart/form-data" name="edit" method="post">
            <p style="font-size:12px;"> Lýsing:</p> <input name="description" class="input align_left"  type="text" placeholder="' . $description . '"> 
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          <form action="owner_edit.php" enctype="multipart/form-data" name="edit" method="post">
            <p style="font-size:12px;"> Verð: </p> <input name="price"  class="input align_left" type="text" size="6" placeholder="' . $price . '">
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          <form action="owner_edit.php" enctype="multipart/form-data" name="edit" method="post">
            <p style="font-size:12px;"> Neitunarástæða: </p> <input name="comment"  class="input align_left" type="text" size="12" value="' . $comment . '">
            <input name="button" class="button" type="submit" value="Breyta"/> 
          </form>
          '
          ?>
          <hr/>
        <div class="etalage-container">    
            <ul id="etalage">
                <?php if (isset($image_list)){
                    echo $image_list;
                    }
                ?>
            </ul>
        </div>
        </div>
        <div class="display-right">
            <hr/>
            <table>
                  <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                    <td width="5%">Stærð</td>
                    <td width="5%">Fjöldi</td>
                </tr>
            <?php
              echo $size_list;
            ?>
          </table>

          <?php
            if(isset($response)){
              echo $response;
            }
          ?>
            <form method="post" action="owner_edit.php" name="create_size" >
                <input type="text" name="new_size"  class="input align_left" placeholder="Ný stærð" />
                <input type="text" name="new_available"  class="input align_left" placeholder="Fjöldi í stærð" size="9" maxlength="2"/>
                <input type="submit" class="button margin" value="Búa til stærð" name="submit" />
</form>
          </ul>
              
        </div>
    </div>
    <div style="min-width: 702px;">
  </div> 
</div>



</body>
</html>