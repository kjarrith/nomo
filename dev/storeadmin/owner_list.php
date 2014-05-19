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

//ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>

<?php
//SPURNING TIL STJÓRNANDA UM HVORT EYÐA EIGI VÖRU OG EYÐA VÖRU EF ÞAÐ ER VALIÐ
if(isset($_GET{'deleteid'})) {
    echo 'Ertu alveg viss um ad thu viljir eyda voru NR. '. $_GET{'deleteid'}. '? <br/> <a href="owner_list.php?yesdelete='.$_GET{'deleteid'}.'">Ja</a>- <a href="inventory_list.php">Nei<a/> ';
    exit();
}

// REMOVE ITEM FROM SYSTEM AND DELETE ITS PICTURE
if(isset($_GET['yesdelete'])) {
    //DELETE FROM DATABASE
    $id_to_delete = $_GET['yesdelete'];
    $sql = mysql_query("DELETE FROM products WHERE id = '$id_to_delete' LIMIT 1") or die(mysql_error());
    //UNLINK IMAGE FROM SERVER
    $pictodelete = ("../images/inventory/$id_to_delete.jpg");
    if(file_exists($pictodelete)){
        unlink($pictodelete);
    }
    header("location: owner_list.php");
    exit();
}
?>


<?php
//Make product new again
if(isset($_POST['marknew'])) {
    $marknew_id = $_POST['marknew'];
        $sql = mysql_query("UPDATE products SET date_added = now() WHERE id = '$marknew_id';")or die(mysql_error());
        header('location:owner_list.php');
        exit();
}

//FELA
if(isset($_POST{'id_to_hide'})) {
$id_to_hide = $_POST{'id_to_hide'};
  $sql = mysql_query("UPDATE products SET status = 0 WHERE id = '$id_to_hide';")or die(mysql_error());
  header("location:owner_list.php");
  exit();
}
?>


<?php

// PAGINATION STARTS
$pagination ="";
//Counting items in database
$count_sql = mysql_query("SELECT * FROM products"); //SELECT * ÞÝÐIR SELECT ALL
$count = mysql_num_rows($count_sql);

// Replacing everything from the GET variable except numbers
if(isset($_GET['page'])){
  $page = preg_replace("#[^0-9]#","", $_GET['page']);
} else {
  $page = 1;
}

//ORDER BY:
if(isset($_GET['order'])){
  $order = mysql_real_escape_string($_GET['order']);
} else {
  $order = "ORDER BY id DESC";
}

//NUMBER OF ITEMS DISPLAYED
$perPage = 30;
$pages = ceil($count/$perPage);
//IF USER FUCKS WITH THE GET VARIABLE
if ($page < 1){
  $page = 1;
} else if($page > $pages) {
  $page = $pages;
}

//Creating the limit in the query
$limit = "LIMIT " . ($page - 1) * $perPage . ", $perPage";
$pagination = "";
if($pages !=1) {

  if($page != 1){
    $prev = $page - 1;
    $pagination .= '<a href="/storeadmin/owner_list.php?page='.$prev.'&order='.$order.'"> < </a>';
  }
if($pages > 1) {
    for($i = 1; $i <= $pages; $i++) {
        $active ="";
        if($i == $page){
          $active .= "active";
        }
        $pagination .= '<a href="/storeadmin/owner_list.php?page='.$i.'&order='.$order.'" class="'.$active.'"> '.$i.' </a>';
    }
  }
  if($page != $pages){
    $next = $page + 1;
    $pagination .= '<a href="/storeadmin/owner_list.php?page='.$next.'&order='.$order.'"> > </a>';
  }


}

//PRINTING OUT THE [PRODUCT] LIST AS STORE ADMIN -------------------
if(isset($_COOKIE['store'])){
$store = $_COOKIE{"store"};
}

if ($manager == "admin") {

   // PRINTING OUT PRODUCT LIST NOT AS ADMIN
    $product_list = "";
    $sql = mysql_query("SELECT * FROM products $order $limit"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql);
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $product_name = $row{"product_name"};
            $product_name = strtolower($product_name); 
            $product_name = ucfirst($product_name); 
            $style_id = $row{"style_id"};
            $price = $row{"price"};
            $visited = $row{"visited"};
            $subcategory = $row{"subcategory"};
            $store = $row{"store"};
            $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
            if($subcategory === '') {
                $subcategory = '-';
            }
            $product_list .='<tr>';
            $product_list .='<td> <img src="http://nomo.is/images/inventory/' . $id . '_thumb.jpg" alt="' . $product_name . '" width="80" height="100" border="1"></td>';            
            $product_list .='<td> ' . $store . '</td>';         
            $product_list .='<td><a href="owner_edit.php?id=' . $id . '">' . $product_name . '</a></td>';
            $product_list .='<td>' . $visited . ' sinnum</td>';
            $product_list .='<td> #' . $id . '</td>';
            $product_list .='<td>' . $price . 'kr.</td>';
            $product_list .='<td>' . $date_added . '</td>';
            $product_list .="<td><a class='button' href='owner_list.php?deleteid=$id' style='font-size:14px; margin:1px;padding:3px 7px; '>X</a> <a style='font-size:14px; margin:1px;padding:3px 7px; ' class='button' href='owner_edit.php?id=$id . ''>?</a>
            <form action ='' method='post'> 
                <input name='marknewbutton' type='submit' class='button' Value='!' style='font-size:14px; margin:1px;padding:3px 7px;'/> 
                <input name='marknew' type='hidden' value='" . $id . "'/>    
            </form>
            <form action='inventory_list.php' enctype='multipart/form-data' method='post'>
                <input name='button' class='button' type='submit' value='Fela' style='font-size:14px; margin:1px;padding:3px 7px; '/> 
                <input name='id_to_hide' class='button' type='hidden' value='" . $id . "'/> 
            </form>
             </td>" ;
            $product_list .='</tr>';
        } 
    } else {
        $product_list = "Það eru engar vörur í búðinni";
    } 

//Printing out [STORE_list] AS ADMIN ---------------------------

$admin_store = "";
$sql = mysql_query("SELECT * FROM stores"); //SELECT * ÞÝÐIR SELECT ALL
$storeCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $id = $row{"id"};
        $store = $row{"store_name"};
        $admin_store .= "<option value='$store'>$store</option>";
    } 
} else {
    $store_list = "Það eru engar vörur í búðinni";
} 

} else {
    // PRINTING OUT PRODUCT LIST NOT AS ADMIN
    $product_list = "";
    $sql = mysql_query("SELECT * FROM products WHERE store = '$store' ORDER BY id DESC"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql);
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $product_name = $row{"product_name"};
            $style_id = $row{"style_id"};
            $price = $row{"price"};
            $subcategory = $row{"subcategory"};
            $store = $row{"store"};
            $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
            if($subcategory === ''){
                $subcategory = '-';
            };
            $product_list .='<tr>';
            $product_list .='<td><a href="owner_edit.php?id=' . $id . '">' . $product_name . '</a></td>';
            $product_list .='<td> #' . $id . '</td>';
            $product_list .='<td> <img src="../images/inventory/' . $id . '.jpg" alt="' . $product_name . '" width="80" height="100" border="1"></td>';
            $product_list .='<td>' . $price . 'kr.</td>';
            $product_list .='<td>' . $date_added . '</td>';
            $product_list .="<td><a class='button' href='owner_list.php?deleteid=$id'>X</a> <a class='button' href='owner_edit.php?id=$id . ''>?</a>
            <form action ='' method='post'> 
                            <input name='marknewbutton' type='submit' class='button' Value='!'/> 
                            <input name='marknew' type='hidden' value='" . $id . "'/>    
                         </form> 
            <form action='inventory_list.php' enctype='multipart/form-data' method='post'>
                <input name='button' class='button' type='submit' value='Fela' style='font-size:14px; margin:1px;padding:3px 7px; '/> 
                <input name='id_to_hide' class='button' type='hidden' value='" . $id . "'/> 
            </form>
                         </td>";
            $product_list .='</tr>';
        } 
    } else {
        $product_list = "Það eru engar vörur í búðinni";
    } 

//Printing out [store_list] NOT AS ADMIN ---------------------- 

$store_list = "";
$sql = mysql_query("SELECT * FROM admin where username = '$manager' "); //SELECT * ÞÝÐIR SELECT ALL
$storeCount = mysql_num_rows($sql);
if($productCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $id = $row{"id"};
        $store = $row{"store"};
        $store_list .= "<option value='$store'>$store</option>";
    } 
} else {
    $store_list = "Þú ert ekki admin hjá neinni búð";
} 

}
?>

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Inventory List</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">


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
      $.post("/functions/closerlook.php",{searchDEV:value}, function(data){
        $("#results").html(data);
      });
  }
</script>
</head>

<body onload="process()">

<?php include_once("../includes/header_admin.php"); ?>
<?php include_once("../includes/menu-owner.php"); ?>

<div id="main-admin"> 
    <h1 style="margin-top:30px;">Allar vörur</h1>
                        <form action="" method="get" style="position:absolute; top:20px; right:20px;">
                <input name="page" type="hidden" value="<?php echo $page ?>">
                <select name="order">
                    <option value="">Raða eftir:</option>
                    <option value="ORDER BY product_name ASC">Nafni</option>
                    <option value="ORDER BY visited DESC ">Vinsældum</option>
                    <option value="ORDER BY id DESC ">Dagsetningu</option>
                    <option value="ORDER BY status ASC ">Stöðu</option>
                    <option value="ORDER BY price DESC ">Verði</option>
                </select>
                <br/>
                <input type="submit" class="button smaller" value="Endurraða">
            </form>
    <input type="text" onkeyup="getSearch(this.value)" class="input" placeholder="Leitaðu..."/>
    <div id="admin-container">
                        <?php echo $pagination; ?>
            <table id="results">
                        <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                            <td width="18%">Mynd</td>
                            <td width="18%">Verslun <br/></td>                                                      
                            <td width="18%">Nafn <br/></td>
                            <td width="6%">Skoðað <br/></td>
                            <td width="5%">ID númer</td>
                            <td width="16%">Verð</td>
                            <td width="9%">Bætt við</td>
                            <td width="9%">Breyta</td>
                        </tr>
                            <?php echo $product_list; ?>
            </table>
    </div>   

</div>






</body>
</html>
