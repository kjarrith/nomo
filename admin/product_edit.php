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
                $product_name = strtolower($product_name); 
                $product_name = ucfirst($product_name); 
                $price = $row{"price"};
                $comment = $row{"comment"};
                $category = $row{"category"};
                $subcategory = $row['subcategory'];
                $details = $row['details'];
                $trademark = $row['trademark'];
                $style_id = $row['style_id'];
                $description = $row{"description"};
                $status = $row['status'];
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
                $product_name = strtolower($product_name); 
                $product_name = ucfirst($product_name); 
                $price = $row{"price"};
                $category = $row{"category"};
                $comment = $row{"comment"};
                $details = $row['details'];
                $trademark = $row['trademark'];
                $subcategory = $row['subcategory'];
                $style_id = $row['style_id'];
                $status = $row['status'];
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

if ($status === '2') {
    $newstatus = '0';
} else if ($status === '1'){
    $newstatus = '1';
} else {
    $newstatus = '0';
}

//Breyta öllu 

if (isset($_POST['change'])) {
    $subcategory = mysql_real_escape_string ($_POST{'subcategory'});
    $gender = mysql_real_escape_string ($_POST{'gender'});
    $name = mysql_real_escape_string ($_POST{'name'});
    $description = mysql_real_escape_string ($_POST{'description'});
    $details = mysql_real_escape_string ($_POST{'details'});
    $trademark = mysql_real_escape_string ($_POST{'trademark'});
    $price = preg_replace("#[^0-9]#","", $_POST{'price'});

    $sql = mysql_query("UPDATE products SET 
    subcategory = '$subcategory',
    category = '$gender', 
    product_name = '$name', 
    description = '$description', 
    details = '$details', 
    price = '$price', 
    status = '$newstatus',
    trademark = '$trademark'
    WHERE id = '$id';")or die(mysql_error());
    header("location:product_edit.php");
    exit();
}


//Eyða stærð

if(isset($_POST['deletebutton'])) {
    //DELETE FROM DATABASE
    $id_to_delete = $_POST['item_to_adjust'];
    $sql = mysql_query("DELETE FROM sub_products WHERE id = '$id_to_delete' LIMIT 1") or die(mysql_error());
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
        $size_list .= '
<li> 
' . $subsize . ' 
<form action ="product_edit.php" method="post"> 
    <input type="text" class="input align_left" name="quantity" value="' . $subavailable . '" size="1" maxlength="2"/>
    <input name="adjustbtn" type="submit" class="button" Value="Breyta" style="font-size:12px;"/>
    <input name="deletebutton" type="submit" class="button" Value="Eyða" style="font-size:12px;"/>
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
    if ($quantity < 1) {$quantity=0;}
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
   $sql = mysql_query("INSERT INTO sub_products (product_id, size, available) VALUES('$id', '$new_size', '$new_available') ")or die(mysql_error());
   $sql = mysql_query("UPDATE products SET status = '$newstatus' WHERE id = '$id';")or die(mysql_error());
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

<?php
//ADD IMAGE TO FOLDER
if (isset($_POST['done'])) {
    $pid = $id;
    include('../functions/image.php');

    $main = $pid;
    $second = $pid."-second";
    $third = $pid."-third";
    $fourth = $pid."-fourth";
    move_uploaded_file($_FILES['filefield']['tmp_name'], '../images/inventory/'.$main.'.jpg');
    move_uploaded_file($_FILES['filefield-second']['tmp_name'], '../images/inventory/'.$second.'.jpg');
    move_uploaded_file($_FILES['filefield-third']['tmp_name'], '../images/inventory/'.$third.'.jpg');
    move_uploaded_file($_FILES['filefield-fourth']['tmp_name'], '../images/inventory/'.$fourth.'.jpg');

    create_thumbnail('../images/inventory/'.$main.'.jpg', '../images/inventory/'.$main.'_thumb.jpg', 300, 400);
    if ($_FILES["filefield-second"]["error"] === 0 & file_exists('../images/inventory/' . $id. '-second.jpg')) {
            create_thumbnail('../images/inventory/'.$second.'.jpg', '../images/inventory/'.$second.'_thumb.jpg', 300, 400);
    }
    if ($_FILES["filefield-third"]["error"] === 0 & file_exists('../images/inventory/' . $id. '-third.jpg')) {
            create_thumbnail('../images/inventory/'.$third.'.jpg', '../images/inventory/'.$third.'_thumb.jpg', 300, 400);
    }
    if ($_FILES["filefield-fourth"]["error"] === 0 & file_exists('../images/inventory/' . $id. '-fourth.jpg')) {
            create_thumbnail('../images/inventory/'.$fourth.'.jpg', '../images/inventory/'.$fourth.'_thumb.jpg', 300, 400);
    }
}
?>

<?php
$image_list = '';
//Creating image_list 
if (file_exists('../images/inventory/' . $id . '.jpg')) {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="../images/inventory/' . $id . '_thumb.jpg">
                <img class="etalage_source_image" src="../images/inventory/' . $id . '.jpg">
            </li>';
}

if (file_exists('../images/inventory/' . $id . '-second.jpg')) {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="../images/inventory/' . $id . '-second_thumb.jpg">
                <img class="etalage_source_image" src="../images/inventory/' . $id . '-second.jpg">
            </li>';
}

if (file_exists('../images/inventory/' . $id . '-third.jpg')) {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="../images/inventory/' . $id . '-third_thumb.jpg">
                <img class="etalage_source_image" src="../images/inventory/' . $id . '-third.jpg">
            </li>';
}

if (file_exists('../images/inventory/' . $id. '-fourth.jpg')) {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="../images/inventory/' . $id . '-fourth_thumb.jpg">
                <img class="etalage_source_image" src="../images/inventory/' . $id . '-fourth.jpg">
            </li>';
}
?>

<!DOCTYPE html>
<head>

<script language="JavaScript" type="text/javascript">
function ajax_post(){
    // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "/functions/my_parse_file.php";
    var fn = document.getElementById("p_name").value;
    var id = document.getElementById("p_id").value;
    var vars = "p_name="+fn+"&p_id="+id;
    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
        if(hr.readyState == 4 && hr.status == 200) {
            var return_data = hr.responseText;
            document.getElementById("status").innerHTML = return_data;
        }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
    document.getElementById("status").innerHTML = "processing...";
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Vara</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../cssmenu/menu_assets/styles.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../demo/menu.css" type="text/css" media="screen" />
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

</head>

<body onload="process()">
    
<?php include_once("../includes/header_admin.php"); ?>
<?php include_once("../includes/menu-admin.php"); ?>

<div id="main-product"> 
    <div class="display-all-edit wrapper" style="width:1000px;">
        <div class="etalage-container">    
            <ul id="etalage">
                <?php if (isset($image_list) & $image_list != ""){
                    echo $image_list;
                    } else {
                        echo '        
            <div class="add-photos">
            <span style="font-size:14px;">Það kaupir engin vöru sem enginn sér!</span>
            <h3>Bættu við myndum! </h3>
<form action="" enctype="multipart/form-data" name="myform" id="myform" method="post">
<table style="border:0; width: 80%; margin:0px auto;">
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">AðalMynd: <br/></td>
                    <td width="6%"  style="text-align:left;"><input name="filefield" id="filefield" type="file" /><br/></td>
                </tr>
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">Mynd 2: <br/></td>
                    <td width="6%"  style="text-align:left;"><input name="filefield-second" id="filefield" type="file" /><br/></td>
                </tr>
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">Mynd 3: <br/></td>
                    <td width="6%"  style="text-align:left;"><input name="filefield-third" id="filefield" type="file" /> <br/></td>
                </tr>
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">Mynd 4: <br/></td>
                    <td width="6%"  style="text-align:left;"><input name="filefield-fourth" id="filefield" type="file" /> <br/></td>
                </tr>
        </table>
                <input name="done" type="hidden" value=""/> 
                <input name="button" class="button" type="submit" value="Staðfesta" style="margin-bottom:10px;"/> 
</form>
            </div>              
';
                    }
                ?>
            </ul>
        </div>
        <div class="display-right" style="width:600px;">
          <?php
          echo '
        <form action="product_edit.php" enctype="multipart/form-data" name="edit" method="post">
              <table style="border:0; width: 100%; margin:0px auto;">
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="10%" style="text-align:left;">Kyn: <br/></td>
                    <td width="50%"  style="text-align:left;"> 
                        <select name="gender" id="subcategory" style="margin-top:20px; height:30px;">
                            <option value="'.$category.'">('.$category.')</option>
                            <option value="menn">Menn</option>
                            <option value="konur">Konur</option>
                        </select> 
                    <td width="40%" style="text-align:left; border:0px solid #ccc; font-weight:100; font-size:12px;">Er varan fyrir menn eða konur?<br/></td>
                </tr>
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">Flokkur: <br/></td>
                    <td width="6%"  style="text-align:left;"> 
                        <select name="subcategory" id="subcategory" style="margin-top:20px; height:30px;">
                            <option value="'.$subcategory.'">('.$subcategory.')</option>
                            ' . $category_list . '
                         </select><br/></td>
                    <td width="3%" style="text-align:left; border:0px solid #ccc; font-weight:100; font-size:12px;">Veldu þann fataflokk sem best lýsir vörunni.<br/></td>
                </tr>
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">Heiti: <br/></td>
                    <td width="6%"  style="text-align:left;">    
                        <input name="name" id="p_name" class="input align_left" type="text" value="' . $product_name . '" >
                    <td width="3%" style="text-align:left; border:0px solid #ccc; font-weight:100; font-size:12px;">(T.d Levi´s blómabolur) <br/> Þetta er nafnið sem mun standa undir vörunni allstaðar þar sem hún er skoðuð.<br/><br/></td>
                </tr>
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">Fatamerki: <br/></td>
                    <td width="6%"  style="text-align:left;"> 
                        <input name="trademark" class="input align_left" type="text" value="' . $trademark . '">  <br/> </td>
                    <td width="3%" style="text-align:left; border:0px solid #ccc; font-weight:100; font-size:12px;">(T.d Levi´s) <br/> Hönnuður eða framleiðandi vörunnar. Ef varan er hönnuð af þér, þá seturu nafn verslunarinnar hér.<br/></td>
                </tr>
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">Vörulýsing: <br/></td>
                    <td width="6%"  style="text-align:left;"> 
                    <textarea wrap="soft" rows="4" cols="20" name="description" class="input align_left">' . $description . '</textarea>
                    <td width="3%" style="text-align:left; border:0px solid #ccc; font-weight:100; font-size:12px;">Þetta er lýsingin sem allir munu sjá.<br/></td>
                </tr>
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">Efnislýsing: <br/></td>
                    <td width="6%"  style="text-align:left;"> 
                    <textarea wrap="soft" rows="4" cols="20" name="details" class="input align_left">' . $details . '</textarea>
                    <td width="3%" style="text-align:left; border:0px solid #ccc; font-weight:100; font-size:12px;">(T.d: 80% Bómull-20% Pólýester, má þvo í vél.)<br/> Stutt lýsing á því hvernig skal meðhöndla vöruna í þvotti.</td>
                </tr>
                <tr style=" border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left; font-weight: bold;">Verð: <br/></td>
                    <td width="6%"  style="text-align:left;"> 
                    <input name="price"  class="input align_left" type="text" size="6" value="' . $price . '">
                    <td width="3%" style="text-align:left; border:0px solid #ccc; font-weight:100; font-size:12px;">(T.d 6980 kr.) <br/>Verð vöru til kaupanda, án afsláttar.<br/></td>
                </tr>
            </table>
            <br/>
            <div id="status"></div>
            <input name="change"  type="hidden"/>
            <input name="button" class="button action" type="submit" value="Uppfæra Upplýsingar"/>
            </form>   
          '
          ?>
            <?php if($status === '2'){
                    echo '<p class="comment">!! '. $comment. ' !!</p>';
                    }
            ?>
            <hr/>
            <h3>Stærðir</h3>
          <ul>
            <?php
              echo $size_list;
            ?>
            <form method="post" action="product_edit.php" name="create_size" >
                <input type="text" name="new_size"  class="input align_left" placeholder="Ný stærð" />
                <input type="text" name="new_available"  class="input align_left" placeholder="Fjöldi í stærð" size="9" maxlength="2"/>
                <input type="submit" class="button margin action" value="Búa til stærð" name="submit" />
            </form>
          </ul>
              
        </div>
    </div>
    <div style="min-width: 702px;">
  </div> 
</div>



</body>
</html>