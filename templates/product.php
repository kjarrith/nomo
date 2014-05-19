<?php
global $cssVersion;
global $faviconVersion;
session_start('');
ob_start();

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
          }
          $name = ucfirst($name); 
    }
}

//ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>

<?php
//HÉR ER VERIÐ AÐ GÁ HVORT URL BREYTAN SÉ TIL Í KERFINU
if(isset($_GET['id'])){
    $P_id = preg_replace('#[^0-9]#i', '', $_GET['id']);
    $P_id = htmlspecialchars(mysql_real_escape_string($P_id));
        $sql = mysql_query("SELECT * FROM products WHERE id='$P_id' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
        $productCount = mysql_num_rows($sql);
        if($productCount>0){
            //NÁ Í ALLAR UPPLÝSINGAR UM VÖRUNA OG VERA MEÐ ÞÆR TIL STAÐAR
            while ($row=mysql_fetch_array($sql)) {
                $id = $row{"id"};
                $product_name = $row{"product_name"};
                $price = $row{"price"};
                $category = $row{"category"};
                $subcategory = $row['subcategory'];
                $x = $row{"dcount"};
                $description = $row{"description"};
                $store = $row{"store"};
                $style_id = $row{"style_id"};
                $model_id = $row["model_id"];
                $model_wears = $row["model_wears"];
                $visited = $row{"visited"};
                $description = (strlen($description) > 150) ? substr($description,0,147).'...' : $description;
                $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
        if ($x>0){
              $realprice = round((1-($x/100))*$price) . '';
            } else {
              $realprice = $price;
            }
                if (isset($x)&&$x>0){
                    $discount_display = '<div class="discount">-'.$x.'</div>';
                    $oldprice = '<span class="oldprice"> Var: ' . $price . 'kr. </span>';
                } else {
                    $discount_display = '';
                    $oldprice = '';
                }
                }
        } else {
            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $pieces = explode("?", $actual_link);
            
            header("location:".$pieces[0]." ");
            exit();
        }
        $sql = mysql_query("UPDATE products SET visited = visited + 1 WHERE id='$id' LIMIT 1");
} else {
    echo "Error";
    exit();

}

//SETJA INN UPPL Í PRODUCT-USER-LOG
if (isset($_COOKIE{'uid'})){
$P_id = preg_replace('#[^0-9]#i', '', $_GET['id']);
$P_id = htmlspecialchars(mysql_real_escape_string($P_id));
$userID = $_COOKIE{"uid"};

$sql = mysql_query("INSERT INTO product_user_log (user_id, product_id, datetime) 
                                VALUES('$userID', '$P_id', now()) ")
                                or die(mysql_error());

}
?>

<?php 
// BÚA TIL "SVIPAÐAR VÖRUR"

$similar_list = "";
$similarsql = mysql_query("SELECT * FROM products WHERE category = '$category' AND subcategory = '$subcategory' AND id != '$id' AND status = 1 ORDER BY RAND() LIMIT 5"); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($similarsql);
if($productCount>0){
    while ($row=mysql_fetch_array($similarsql)) {
        $pid = $row{"id"};
        $similar_list .= '
<li> 
      <a href="../vara/' . $pid . '"><img src="http://www.nomo.is/images/inventory/' . $pid . '_thumb.jpg" class="p-image"></a>
</li>';
    } 
} else {
    $similar_list = "Það eru engar vörur í búðinni";
} 
?>

<?php
//UPPLÝSINGAR FUNDNAR UM MÓDEL
$ModelList = "";
$sql = mysql_query("SELECT * FROM models WHERE id = '$model_id' ORDER BY name ASC"); //SELECT * ÞÝÐIR SELECT ALL
$modelCount = mysql_num_rows($sql);
if($modelCount>0){
   while ($row=mysql_fetch_array($sql)) {
        $ModelName = $row["name"];
        $chest = $row["chest"];
        $height = $row["height"];
        $waist = $row["waist"];
        //búa til listann sjálfan
        $ModelInfo = '<strong>Hæð Módels:</strong> '.$height.'<br/>
                    <strong>Mittismál:</strong> '.$waist.'<br/>
                    <strong>Ummál Brjóstkassa:</strong> '.$chest.'<br/><br/>
                    <strong>Módel er í stærð:</strong>  '.$model_wears.' <br/> <br/>';
      }
} else {
    $ModelInfo = 'Ekki eru til upplýsingar um stærðir módelsins <br/><br/>';
}
//HÉR ER STÆRÐARLISTINN BÚINN TIL

$size_list = "";
$sql = mysql_query("SELECT * FROM sub_products WHERE product_id = '$id' AND available > 0 "); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $subid = $row{"id"};
        $subsize = $row{"size"};
        $subavailable = $row{"available"};
        $size_list .= '
            <option value"' . $subsize . ' "> 
            ' . $subsize . ' 
            </option>';
    } 
} else {
    $response = 'Uppselt í bili!';
} 
?>

<?php
$image_list = '';
//Creating image_list 
if (file_exists(APPDIR . '/images/inventory/' . $id. '.jpg')) {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="http://www.nomo.is/images/inventory/' . $id . '_thumb.jpg">
                <img class="etalage_source_image" src="http://www.nomo.is/images/inventory/' . $id . '.jpg">
            </li>';
}

if (file_exists(APPDIR . '/images/inventory/' . $id. '-second.jpg')) {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="http://www.nomo.is/images/inventory/' . $id . '-second_thumb.jpg">
                <img class="etalage_source_image" src="http://www.nomo.is/images/inventory/' . $id . '-second.jpg">
            </li>';
}

if (file_exists(APPDIR . '/images/inventory/' . $id. '-third.jpg')) {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="http://www.nomo.is/images/inventory/' . $id . '-third_thumb.jpg">
                <img class="etalage_source_image" src="http://www.nomo.is/images/inventory/' . $id . '-third.jpg">
            </li>';
}

if (file_exists(APPDIR . '/images/inventory/' . $id. '-fourth.jpg')) {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="http://www.nomo.is/images/inventory/' . $id . '-fourth_thumb.jpg">
                <img class="etalage_source_image" src="http://www.nomo.is/images/inventory/' . $id . '-fourth.jpg">
            </li>';
}
?>

<?php
//SETJA VÖRU Í FATASKÁP EF ÞAÐ ER VALIÐ
if (isset($_COOKIE{'uid'})){
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        $product_id = $_POST['product_id'];

        $sql = mysql_query("INSERT INTO wishlist (user_id, product_id, date_added) 
                                        VALUES('$user_id', '$product_id', now()) ")
                                        or die(mysql_error());

        header("Location: /fataskapurinn");
        exit();
    }
    } else {
        if (isset($_POST['user_id'])) {
            header("Location: /fataskapurinn");
            exit(); 
        }
    }

?>

<?php

//BÚA TIL LINK FYRIR VERSLUNINA
$storeid = "";
$sqllink = mysql_query("SELECT * FROM stores WHERE store_name = '$store' LIMIT 1"); //SELECT * ÞÝÐIR SELECT ALL
if($sqllink){
    while ($row=mysql_fetch_array($sqllink)) {
            $storeid = $row["id"];
      }
      $storelink = "http://nomo.is/verslun/".$storeid;
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

<?php
//CREATE URL VARIABLES
$thisurl = 'http://nomo.is/vara/'.$id;
$imageurl = 'http://nomo.is/images/inventory/'.$id.'.jpg';
//---
?>


<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">

<link href="<?php echo $imageurl; ?>" rel="image_src"/>
<meta property="og:title" content="<?php echo $product_name; ?>" >
<meta property="og:image" content="<?php echo $imageurl; ?>" />
<meta property="og:url" content="<?php echo $thisurl; ?>" />

<title><?php echo $product_name; ?></title>

<link rel="shortcut icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>">
<link rel="icon" href="/images/favicon.ico?v=<?php echo $faviconVersion; ?>" type="image/x-icon">

<link href="../css/styles.css?v=<?php echo $cssVersion; ?>" rel="stylesheet" type="text/css" />
<link href="../cssmenu/menu_assets/styles.css" rel="stylesheet" type="text/css">
<link href="../demo/menu.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/etalage.css" type="text/css" rel="stylesheet" /> 

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

<script type="text/javascript">
  function getSearch(value) {
      $.post("addtocart.php",{add:value}, function(data){
        $("#results").html(data);
      });
  }
</script>

</head>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=234032696651711";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<body>
    
<?php require_once(APPDIR . '/includes/header_1up.php'); ?>
<?php require_once(APPDIR . '/includes/menu_1up.php'); ?>

<div id="main-product">
<div id="product-wrapper">
    <div class="display-all wrapper">

        <div class="etalage-container">    
            <ul id="etalage">
                <?php if (isset($image_list)){
                    echo $image_list;
                    }
                ?>
            </ul>
            <br/>
        </div>

        <div class="display-right">
            <div class="right-info">
                <h1> <?php echo $product_name; ?></h1>
                <div class="description"> <?php echo $description; ?></div>
                <p class="price-text" style="color: #010101;"> <?php echo '<a href="'.$storelink.'">'.$store.'</a>' ;?></p>
                <h4> <?php echo $realprice?>kr <h4>
                     <?php echo $oldprice?>   
                    <form id="add2cart" name="add2cart" method="post" action="/karfa" onsubmit="return validateForm()">
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" >
                            <hr/>
                                <?php
                                    if (isset($size_list) & $size_list != ''){
                                        echo ' <h3> Vel Valið</h3>
                                    <select name="size" class="selector" style="font-size:16px; min-width:50px;">
                                    <option value="">Veldu stærð</option>
                                        '.$size_list.'
                                    </select>
                                    ';
                                    }
                                ?>
                            </br>
                                <?php if (isset($response)) {
                                    echo '<input type="submit" disabled name="button cart" class="button margin" value="UPPSELT" style="width:170px; border: 2px solid #2a2a2a; float:left; opacity:0.7;">';
                                    } else {
                                    echo '<input type="submit" name="button" class="button cart margin" value="SETJA Í KÖRFU" style="width:170px; float:left;">';
                                    }
                                ?>
                    </form>
                    <form method="post" action="">
                        <input type="hidden" name="product_id" id="id" value="<?php echo $id; ?>" >
                        <input type="hidden" name="user_id" id="id" value="<?php echo $userID; ?>" >
                        <input type="submit" name="button" class="button wishlist" value="GEYMA Í FATASKÁP" style="width:170px; float:right; margin-top:10px;">
                    </form>
                    <br/>
                    <div class="fb-like" data-href="<?php echo $thisurl; ?>" data-width="450" data-layout="button_count" data-show-faces="true" data-send="true"></div>
            </div>
            <br/>
            <div id="product-info">
                <span style="text-align: center;"><h3 style="margin:0; padding:0; line-height: 20px;">Veldu rétta stærð</h3> <hr/></span>
                <div class="product-info-text"> 
                    <?php echo $ModelInfo;?>
                    <a href="http://nomo.is/images/sizes/<?php echo $category;?>.pdf" target="_blank">Stærðatöflur</a>
                </div>
            </div>
        </div>
                    <div class="similarProducts">
                <ul>
                    <hr/>
                        <span style="margin-bottom:10px;">VIÐ MÆLUM MEÐ</span>
                    <hr/>
                    <?php echo $similar_list;?>
                </ul>
            </div>
            <br/><br/>
    </div>
</div> 
    <div style="min-width: 702px;">
<?php require_once(APPDIR . '/includes/footer_1up.php'); ?> 
  </div> 

</div>


<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=234032696651711";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script type="text/javascript">
  function validateForm()
  {
  var x=document.forms["add2cart"]["size"].value;
  if (x==null || x=="")
    {
    alert("Ekki gleyma að velja stærð!");
    return false;
    }
  }

</script>

</body>
</html>