<?php
global $cssVersion;
global $faviconVersion;
ob_start();
session_start('oid');

include '../templates/storescripts/connect_to_mysql.php';

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
//GET-ing the search parameters
if(isset($_GET['search']))
{
//STEP 3 Declair Variables
  $search = $_GET['search'];
  $search = strtolower($search); 
  $search = mysql_real_escape_string($search);
}

// PAGINATION STARTS
//Counting items in database
$count_sql = mysql_query("SELECT * FROM products WHERE product_name LIKE '%$search%' AND status = 1 ORDER BY id DESC "); //SELECT * ÞÝÐIR SELECT ALL
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
    $pagination .= '<a href="leitarnidurstodur.php?search='.$_GET['search'].'&page='.$prev.'"> < </a>';
  }else {
    $pagination .= '<';
  }
  if($pages > 1) {
    for($i = 1; $i <= $pages; $i++) {
            $active ="";
        if($i == $page){
          $active .= "active";
        }
        $pagination .= '<a href="leitarnidurstodur.php?search='.$_GET['search'].'&page='.$i.'" class="'.$active.'"> '.$i.' </a>';
    }
  }
  if($page != $pages){
    $next = $page + 1;
    $pagination .= '<a href="leitarnidurstodur.php?search='.$_GET['search'].'&page='.$next.'"> > </a>';
  } else {
    $pagination .= '>';
  }


}
// RUN A SELECT QUERY TO DISPLAY MY PRODUCTS ON MY DYNAMIC LISt
$dynamic_list = "";
$sql = mysql_query("SELECT * FROM products WHERE product_name LIKE '%$search%' OR id = '$search' AND status = 1 ORDER BY id DESC "); //SELECT * ÞÝÐIR SELECT ALL
$productCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $id = $row{"id"};
        $product_name = $row{"product_name"};
        $price = $row{"price"};
        $x = $row{"dcount"};
        $store = $row{"store"};
        $trademark = $row ['trademark'];
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
        $product_name = (strlen($product_name) > 36) ? substr($product_name,0,33).'...' : $product_name;
        /*FINNA HVORT SÉ UPPSELD */
        $sql2 = mysql_query("SELECT * FROM sub_products WHERE product_id = '$id' AND available > 0"); //SELECT * ÞÝÐIR SELECT ALL
        $productCount2 = mysql_num_rows($sql2);
        if($productCount2 < 1){
            $uppselt = '<span class="uppselt">UPPSELT</span>';
        } else {
          $uppselt = "";
        }
        /*FINNA HVORT SÉ UPPSELD */
        $dynamic_list .= '
<li> 
      <div class="p-wrapper">
        <a href="/vara/' . $id . '"><img src="http://www.nomo.is/images/inventory/' . $id . '-second_thumb.jpg" class="p-image bottom"></a>
        <a href="/vara/' . $id . '"><img src="http://www.nomo.is/images/inventory/' . $id . '_thumb.jpg" class="p-image top"></a>        <div class="p-info"> 
              <a href="/vara/' . $id . '"><h4> ' . $product_name .'</h4></a>
              <p> <span style="color:#555;">' . $realprice . 'kr.</span>'. $oldprice .' </p>
              <div class="p-buy">
                 <p>' . $trademark .'</p>
              </div>
          </div>
        '.$discount_display.$uppselt.'
      </div>
  </li>';
    } 
} else {
    $dynamic_list = "Leitarskilyrði skila engum niðurstöðum <br/> Prufaðu að leita eftir undirflokkunum hér að ofan";
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

<script type="text/javascript"src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title><?php echo "Leitarniðurstöður" ?> </title>

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

<script type="text/javascript">
  function getSearch(value) {
      $.post("/functions/closerlook.php",{search:value}, function(data){
        $("#results").html(data);
      });
  }
</script>

</head>

<body onload="process()">

<?php include_once("../includes/header_1up.php"); ?>
<?php include_once("../includes/menu_1up.php"); ?>

<div id="main"> 
  
                <div class="category-info">
                  <div class="max-width">
                  <h1> Leitarniðurstöður </h1>
                  <p> Stundum þarf maður bara að grafa eftir því eina rétta. Hér eru vonandi allir þeir hlutir sem þú varst að leita að. <br/> <br/> Ef ekki, endilega leitaðu aftur:</p>
                        <input type="text" onkeyup="getSearch(this.value)" class="input" placeholder="Leitaðu..."/>
                        <br/> <br/>
                  </div>
                </div>
    <ul id="products">

           <div id="feedname">
                <span class="pagination">
                <?php echo $pagination; ?>
              </span>
                <br/> 
                <hr/>
                <div id="results">
                <?php echo $dynamic_list; ?>
              </div>
                <br/><br/>
                <span class="pagination">
                <?php echo $pagination; ?>
              </span>  
              <br/>          
            </div>      
     </ul>
            

         <?php include_once("../includes/footer_1up.php"); ?> 
</div>

<!--
    <li> <div class="il"> <img src="images/prufa2.jpg" /><div class="price"> White T <br />30$ </div></div> </li>
-->




</body>
</html>
