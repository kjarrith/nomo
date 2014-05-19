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

if (isset($_COOKIE['manager'])) {

$store_array ="";
$store_list = "";
$id_list = "";
$sql = mysql_query("SELECT * FROM stores where admin = '$manager' "); //SELECT * ÞÝÐIR SELECT ALL
$storeCount = mysql_num_rows($sql);
if($storeCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $store_name = $row{"store_name"};
        $store_id = $row{"id"};
        $store_pcount = $row['pcount'];
        $store_visited = $row['visited'];
        $store_list .= '<option value="'.$store_id.'">'.$store_name.'</option>';
        $store_array .= $store_name. " ";
        $id_array .= $store_id. " ";
    } 
    $pieces = explode(" ", $store_array);
    $pieces2 = explode(" ", $id_array);
} else {
    $store_list = "Þú ert ekki admin hjá neinni búð";
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
    echo 'Ertu alveg viss um ad thu viljir eyda voru NR. '. $_GET{'deleteid'}. '? <br/> <a href="inventory_list.php?yesdelete='.$_GET{'deleteid'}.'">Ja</a>- <a href="inventory_list.php">Nei<a/> ';
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
    header("location: inventory_list.php");
    exit();
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
        $quantity = 99;         //HÉR ERUM VIÐ AÐ 
    }
    if ($quantity < 1) {$quantity=1;}
     $sql = mysql_query("UPDATE products SET totalamount = '$quantity' WHERE id = '$item_to_adjust';")or die(mysql_error());
        header("location:inventory_list.php");
        exit();
}
?>

<?php
//ÞÁTTA UPPLÝSINGAR FRÁ FORMINU
$new_store_pcount = "";
if(isset($_POST{'product_name'}) && $_POST{'category'} != "") {
    $product_name = mysql_real_escape_string ($_POST{'product_name'});
    $category = mysql_real_escape_string($_POST{'category'});
    $add_store_id = mysql_real_escape_string($_POST{'store'});
//FINNA BÚÐINA SEM VERIÐ ER AÐ SETJA INN FYRIR
$sql = mysql_query("SELECT * FROM stores where id = '$add_store_id' "); //SELECT * ÞÝÐIR SELECT ALL
$storeCount2 = mysql_num_rows($sql);
if($storeCount2>0){
    while ($row=mysql_fetch_array($sql)) {
        $store_name2 = $row{"store_name"};
        $store_pcount2 = $row['pcount'];
        }
    }
//BÚIÐ AÐ FINNA BÚÐ
    $new_store_pcount = $store_pcount2 + 1;
    $new_style_id = $add_store_id .'-'.  $new_store_pcount;
if ($_POST{'category'} != "") {
//ADD [PRODUCT] TO DATABASE
    $sql = mysql_query("INSERT INTO products (product_name,category, date_added, store, style_id, status)
                        VALUES('$product_name','$category', now(), '$store_name2', '$new_style_id', '0')
                        ")or die(mysql_error());
    $pid = mysql_insert_id();
    $sql = mysql_query("UPDATE stores SET pcount = '$new_store_pcount' WHERE store_name = '$store_name2' ")or die(mysql_error());

//SEND US TO THE PAGE FOR ADDRESSING SIZES AND AVAILABLE AMOUNT
    header('location:product_edit.php?id=' . $pid . '');
    exit();
} else {
    $response = "Til þess að hægt sé að setja inn vöru þarf nafn, kyn og verð!";
}

}
?>

<?php
//VERÐBREYTINGAR

//BREYTA STORE_PRICE
if(isset($_POST{'dcount'})) {
$dcount = preg_replace("#[^0-9]#","", $_POST{'dcount'});
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE products SET dcount = '$dcount' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:inventory_list.php");
  exit();
}

//BREYTA PRICE
if(isset($_POST{'web_price'})) {
$web_price = preg_replace("#[^0-9]#","", $_POST{'web_price'});
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE products SET price = '$web_price' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:inventory_list.php");
  exit();
}

//FELA
if(isset($_POST{'id_to_hide'})) {
$id_to_hide = $_POST{'id_to_hide'};
  $sql = mysql_query("UPDATE products SET status = 0 WHERE id = '$id_to_hide';")or die(mysql_error());
  header("location:inventory_list.php");
  exit();
}

?>


<?php
// PAGINATION STARTS
//Counting items in database
$count_sql = mysql_query("SELECT * FROM products WHERE store = '$pieces[0]' OR store = '$pieces[1]' OR store = '$store_name'"); //SELECT * ÞÝÐIR SELECT ALL
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
$perPage = 15;
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
    $pagination .= '<a href="/admin/inventory_list.php?page='.$prev.'&order='.$order.'"> < </a>';
  }
if($pages > 1) {
    for($i = 1; $i <= $pages; $i++) {
        $active ="";
        if($i == $page){
          $active .= "active";
        }
        $pagination .= '<a href="/admin/inventory_list.php?page='.$i.'&order='.$order.'" class="'.$active.'"> '.$i.' </a>';
    }
  }
  if($page != $pages){
    $next = $page + 1;
    $pagination .= '<a href="/admin/inventory_list.php?page='.$next.'&order='.$order.'"> > </a>';
  }


}
    // PRINTING OUT PRODUCT LIST NOT AS ADMIN
    $product_list = "";
    $productCount ="";
    $a_sum ="";
    $ertil ="";
    $sql = mysql_query("SELECT * FROM products WHERE store = '$pieces[0]' OR store = '$pieces[1]' OR store = '$store_name' $order $limit"); //SELECT * ÞÝÐIR SELECT ALL
    if($sql != ""){
        $productCount = mysql_num_rows($sql);
    }
    if(isset($productCount) & $productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $product_name = $row{"product_name"};   
            $style_id = $row{"style_id"};
            $price = $row{"price"};
            $status = $row{"status"};
            $visited = $row{"visited"};
            $x = $row{"dcount"};
            $store = $row{"store"};
            $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
            if ($x>100) {
                $x=100;
            }
            if ($x>0){
            $realprice = round((1-($x/100))*$price);
            } else {
                $realprice = '-';
            }
            //BÚA TIL AVAILABLE MAGN SKOÐUN
                    $subsql = mysql_query("SELECT * FROM sub_products WHERE product_id = '$id'"); //SELECT * ÞÝÐIR SELECT ALL
                    $subCount = mysql_num_rows($subsql);
                    $subsql2 = mysql_query("SELECT * FROM sub_products WHERE product_id = '$id' AND available > '0'"); //SELECT * ÞÝÐIR SELECT ALL
                    $subCount2 = mysql_num_rows($subsql2);
                    if($subCount === $subCount2 & $subCount != 0) {
                        $ertil = "<div class='status-1'></div>";
                    }else{
                        $ertil = "<div class='status-0'></div>";
                    }
            //AVAILABLE MAGNSKOÐUN TILBÚIN
            $style_id = (strlen($style_id) > 18) ? substr($style_id,0,15).'...' : $style_id;
            $product_list .='<tr>';
            $product_list .='<td><div class="status-'.$status.'"></div></td>';
            $product_list .='<td> <a href="product_edit.php?id=' . $id . '"><img src="../images/inventory/' . $id . '_thumb.jpg" alt="' . $product_name . '" width="80" height="100" border="1"></a></td>';
            $product_list .='<td>' . $visited . ' sinnum</td>';
            $product_list .='<td><a href="product_edit.php?id=' . $id . '">' . $product_name . '</a></td>';
            $product_list .='<td> <form action="inventory_list.php" enctype="multipart/form-data" name="edit" method="post">
             <input name="web_price" class="input align_left"  type="text" value="' . $price . '" size="7"> 
            <input name="button" class="button" type="submit" value="Breyta"/> 
            <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
          </form></td>';
            $product_list .='<td>' . $realprice . '</td>';
            $product_list .='<td> <form action="inventory_list.php" enctype="multipart/form-data" name="edit" method="post">
          <input name="dcount" class="input align_left"  type="text" value="' . $x . '" size="3" maxlength="3">
            <input name="button" class="button" type="submit" value="Breyta"/> 
            <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
          </form></td>';
            $product_list .='<td>' . $date_added . '</td>';
            $product_list .='<td>' . $ertil . '</td>';
            $product_list .='<td>
            <a class="button" href="inventory_list.php?deleteid='.$id.'" style="font-size:14px; margin:1px;padding:3px 7px; ">Eyða</a> 
            <a class="button" href="product_edit.php?id='.$id.'" style="font-size:14px; margin:1px;padding:3px 7px; ">Skoða</a>
            <form action="inventory_list.php" enctype="multipart/form-data" name="edit" method="post">
            <input name="button" class="button" type="submit" value="Fela" style="font-size:14px; margin:1px;padding:3px 7px; "/> 
            <input name="id_to_hide" class="button" type="hidden" value="' . $id . '"/> 
            </form>
             </td>
            ' ;
            $product_list .='</tr>';
        } 
    } else {
        $product_list = "Það eru engar vörur í búðinni";
    } 

}
?>

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Vörulisti</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
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
      $.post("/functions/closerlook.php",{searchAll:value, store: "<?php echo $store_name;?>"}, function(data){
        $("#results").html(data);
      });
  }
</script>

</head>

<body onload="process()">

<?php include_once("../includes/header_admin.php"); ?>
<?php include_once("../includes/menu-admin.php"); ?>

<div id="main-admin"> 
  
        <div id="add-product">

    <h5>Bættu við nýrri vöru!</h5>
<form action="inventory_list.php" enctype="multipart/form-data" name="myform" id="myform" method="post">

        <table style="border:0; width: 80%; margin:0px auto;">
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">Heiti: <br/></td>
                    <td width="6%"  style="text-align:left;"><input name="product_name" type="text" class="input align_left" size="20"/> <br/></td>
                </tr>
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">Kyn: <br/></td>
                    <td width="6%" style="text-align:left;">
                        <select name="category" id="category">
                            <option value="">Veldu kyn</option>
                            <option value="menn">Menn</option>
                            <option value="konur">Konur</option>
                        </select>
                    </td>
                </tr>
                <tr style="font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%" style="text-align:left;">Verslun: <br/></td>
                    <td width="6%" style="text-align:left;">
                        <select name="store" id="subcategory"  >
                    <?php 
                        echo $store_list;
                    ?>
                        </select>
                    </td>
                </tr>
        </table>
                        <hr/>
                <input name="button" class="button" type="submit" value="Staðfesta" style="margin-bottom:10px;"/>  
                    <?php
                        if(isset($response)) {
                             echo "<h4>" . $response . "</h4>";
                         }
                    ?>                
</form> 
        </div>

        <div id="admin-container">

            <h3 align='center'> <?php if($manager!="admin") {echo $store_array;} else {echo "Pulsa";}?></h3> <br/>
            <p>Það hafa <strong><?php echo $store_visited?> manns</strong> komið inn í þína Nomo-Netverslun.<p>
            <input type="text" onkeyup="getSearch(this.value)" class="input" placeholder="Leitaðu..."/>
                    <br/> <br/>
                <?php echo $pagination; ?>
                <br/>
            <form action="" method="get" style="position:absolute; top:20px; right:20px;">
                <input name="page" type="hidden" value="<?php echo $page ?>">
                <select name="order">
                    <option value="">Raða eftir:</option>
                    <option value="ORDER BY product_name ASC">Nafni</option>
                    <option value="ORDER BY visited DESC ">Vinsældum</option>
                    <option value="ORDER BY id DESC ">Dagsetningu</option>
                    <option value="ORDER BY status ASC ">Stöðu</option>
                    <option value="ORDER BY price ASC ">Verði</option>
                    <option value="ORDER BY style_id ASC ">Style_id</option>
                </select>
                <br/>
                <input type="submit" class="button smaller" value="Endurraða">
            </form>

    <table id="results">
                <tr style="background-color: #333; color:#fff; font-weight: bold; border-bottom: 1px solid #ccc; ">
                    <td width="3%">Staða <br/></td>
                    <td width="10%">Mynd</td>
                    <td width="6%">Skoðað <br/></td>
                    <td width="18%">Nafn <br/></td>
                    <td width="13%">Verð Vöru</td>
                    <td width="13%">Aflsáttarverð</td>
                    <td width="5%">Afsláttur í %</td>
                    <td width="9%">Bætt við</td>
                    <td width="9%">Til í öllum stærðum?</td>
                    <td width="60px">Aðgerðir</td>
                </tr>
                    <?php echo $product_list; ?>
    </table>
            <br/><br/>
                <?php echo $pagination; ?>
                <br/>    
<a name="addproduct"></a>
        </div>    
</div>






</body>
</html>
