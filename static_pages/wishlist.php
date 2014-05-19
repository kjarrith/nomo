<?php
global $cssVersion;
global $faviconVersion;
session_start('');
ob_start();
$userID ="";

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
//Eyða Wishlist item.
if(isset($_POST{'item_to_delete'})) {
    $item_to_delete = $_POST{'item_to_delete'};
    $sql = mysql_query("DELETE FROM wishlist WHERE id = '$item_to_delete' LIMIT 1") or die(mysql_error());
        header("location: fataskapurinn");
    exit();
}
?>

<?php
// PAGINATION STARTS
$pagination = "";
$dynamic_list ="";
//Counting items in database
if(isset($userID) & $userID != ""){
$count_sql = mysql_query("SELECT * FROM wishlist WHERE user_id = '$userID'"); //SELECT * ÞÝÐIR SELECT ALL
$count = mysql_num_rows($count_sql);

// Replacing everything from the GET variable except numbers
if(isset($_GET['page'])){
  $page = preg_replace("#[^0-9]#","", $_GET['page']);
} else {
  $page = 1;
}
//NUMBER OF ITEMS DISPLAYED
$perPage = 24;
$pages = ceil($count/$perPage);
//IF USER FUCKS WITH THE GET VARIABLE
if ($page < 1){
  $page = 1;
} else if($page > $pages) {
  $page = $pages;
}

//Creating the limit in the query
$limit = "LIMIT " . ($page - 1) * $perPage . ", $perPage";

if($pages !=1) {

  if($page != 1){
    $prev = $page - 1;
    $pagination .= '<a href="/verslanir/'.$prev.'"> < </a>';
  }else {
    $pagination .= '<';
  }
  if($pages > 1) {
    for($i = 1; $i <= $pages; $i++) {
        $active ="";
        if($i == $page){
          $active .= "active";
        }
        $pagination .= '<a href="/verslanir/'.$i.'" class="'.$active.'"> '.$i.' </a>';
    }
  }
  if($page != $pages){
    $next = $page + 1;
    $pagination .= '<a href="/verslanir/'.$next.'"> > </a>';
  } else {
    $pagination .= '>';
  }


}
// RUN A SELECT QUERY TO DISPLAY MY PRODUCTS ON MY DYNAMIC LIST

$dynamic_list = "";
if(isset($_COOKIE{'uid'})){
$wish_sql = mysql_query("SELECT * FROM wishlist WHERE user_id = '$userID' ORDER BY date_added DESC "); //SELECT * ÞÝÐIR SELECT ALL
$wishlistCount = mysql_num_rows($wish_sql);
    if($wishlistCount>0){
        while ($row=mysql_fetch_array($wish_sql)) {
          $product_id = $row{"product_id"};
          $wishlist_id = $row['id'];

          $sql = mysql_query("SELECT * FROM products WHERE status = 1 AND id = '$product_id' ORDER BY id DESC $limit"); //SELECT * ÞÝÐIR SELECT ALL
          $productCount = mysql_num_rows($sql);
          if($productCount>0){
              while ($row=mysql_fetch_array($sql)) {
                  $id = $row{"id"};
                  $product_name = $row{"product_name"};
                  $product_name = strtolower($product_name); 
                  $product_name = ucfirst($product_name); 
                  $price = $row{"price"};
                  $x = $row{"dcount"};
                  $store = $row{"store"};
                  $trademark = $row['trademark'];
                  $category = $row{"category"};
                  $subcategory = $row{"subcategory"};
                  if ($x>0){
                        $realprice = round((1-($x/100))*$price);
                      } else {
                        $realprice = $price;
                      }
                  if (isset($x)&&$x>0){
                    $discount_display = '<div class="discount">-'.$x.'%</div>';
                    $oldprice = '<span class="oldprice"> Var: ' . $price . 'kr. </span>';
                  } else {
                    $discount_display = '';
                    $oldprice = '';
                  }
                  $dynamic_list .= '
          <li> 
              <div class="p-wrapper" style="padding-bottom:55px;">
                <a href="/vara/' . $id . '"><img src="http://www.nomo.is/images/inventory/' . $id . '-second_thumb.jpg" class="p-image bottom"></a>
                <a href="/vara/' . $id . '"><img src="http://www.nomo.is/images/inventory/' . $id . '_thumb.jpg" class="p-image top"></a>
                <div class="p-info" style="height:auto;"> 
                    <h4> ' . $product_name . ' </h4>
                    <p> ' . $trademark . ' </p> <br/>
                    <div style="width:100%; text-align:center;">
                        <form action="" method="post">
                          <input type="hidden" name="item_to_delete" value="'.$wishlist_id.'">
                          <input type="submit" name="remove" class="button" style="margin:0px auto;" value="EYÐA"></div>
                        </form>
                        <br/>
                    </div>
                      '.$discount_display.'
                </div>
          </li>';
              } 
    }
    }
    } else {
      $dynamic_list = "Þú ert ekki með neinar flíkur í fataskápnum þínum.";
    }
} else {
  $dynamic_list = "Þú þarft að vera skráður inn til þess að geta sett vörur í fataskápinn þinn. <br/><br/>
  Skráðu þig inn hér.
  <br/><br/>
  Búðu til aðgang hér.
  ";
}
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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Fataskápurinn </title>

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
  
                <div class="category-info">
                  <div class="max-width">
                  <h1> Fataskápurinn minn </h1>
                  <p> Allt sem mig langar í</p>
                  </div>
                </div>
    <ul id="products">

           <div id="feedname">
                <span style="float:left">
                <?php echo $page ?>
              </span>
                <span style="float:right">
                <?php echo $pagination; ?>
              </span>
                <br/> 
                <hr/>
                
                <?php if(isset($dynamic_list) & $dynamic_list !=""){
                  echo $dynamic_list;
                } else {
                  echo "Þú þarft að vera skráður inn til þess að geta sett vörur í fataskápinn þinn. <br/><br/>
  <a href='/accounts-landing'>Skráðu þig inn hér.</a>
  ";
                }?>
                <br/><br/>
                <span style="float:left">
                <?php echo $page; ?>
              </span>
                <span style="float:right">
                <?php echo $pagination; ?>
              </span>  
              <br/>          
            </div>      
     </ul>
            

         <?php require_once(APPDIR . '/includes/footer_1up.php'); ?>
</div>

<!--
    <li> <div class="il"> <img src="images/prufa2.jpg" /><div class="price"> White T <br />30$ </div></div> </li>
-->




</body>
</html>
