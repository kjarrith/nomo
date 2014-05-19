<?php

include '../templates/storescripts/connect_to_mysql.php';

//LEITA AF VÖRUM í DEV KERFINU
if (isset($_POST['searchDEV'])){
$searchDEV = $_POST['searchDEV'];

echo '           
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
        ';

$sql3 = mysql_query("SELECT * FROM products WHERE product_name LIKE '%$searchDEV%'
                     OR price LIKE '%$searchDEV%' 
                     OR trademark LIKE '%$searchDEV%' 
                     OR store LIKE '%$searchDEV%' 
                     OR category LIKE '%$searchDEV%' 
                     OR subcategory LIKE '%$searchDEV%'
                     OR id LIKE '%$searchDEV%' 
                     ORDER BY id DESC ");
 while ($row=mysql_fetch_array($sql3)) {
            $id = $row{"id"};
            $product_name = $row{"product_name"};
            $product_name = strtolower($product_name); 
            $product_name = ucfirst($product_name); 
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
            //-----BÚA TIL LISTANN SJÁLFANN
            echo '
            <tr>
            <td> <img src="http://nomo.is/images/inventory/' . $id . '_thumb.jpg" alt="' . $product_name . '" width="80" height="100" border="1"></td>           
            <td> ' . $store . '</td>       
            <td><a href="owner_edit.php?id=' . $id . '">' . $product_name . '</a></td>
            <td>' . $visited . ' sinnum</td>
            <td> #' . $id . '</td>
            <td>' . $price . 'kr.</td>
            <td>' . $date_added . '</td>
            <td><a style="font-size:14px; margin:1px;padding:3px 7px; " class="button" href="owner_list.php?deleteid=$id">X</a> <a class="button" style="font-size:14px; margin:1px;padding:3px 7px; " href="owner_edit.php?id=$id . "">?</a>
            <form action ="" method="post"> 
                            <input name="marknewbutton" type="submit" class="button" Value="!" style="font-size:14px; margin:1px;padding:3px 7px; "/> 
                            <input name="marknew" type="hidden" value="' . $id . '"/>    
            </form>
            <form action="inventory_list.php" enctype="multipart/form-data" name="edit" method="post">
            <input name="button" class="button" type="submit" value="Fela" style="font-size:14px; margin:1px;padding:3px 7px; "/> 
            <input name="id_to_hide" class="button" type="hidden" value="' . $id . '"/> 
            </form>
            </td>
            </tr>
            ';
    } 
}

//LEITA EFTIR VÖRUM Í ADMIN KERFINU
if (isset($_POST['searchAll'])){
$searchAll = $_POST['searchAll'];
$currentstore = $_POST['store'];

echo '           
        <tr style="background-color: #333; color:#fff; font-weight: bold; border-bottom: 1px solid #ccc; ">
            <td width="3%">Staða <br/></td>
            <td width="6%">Skoðað <br/></td>
            <td width="18%">Nafn <br/></td>
            <td width="4%">Style_id</td>
            <td width="10%">Mynd</td>
            <td width="13%">Verð Vöru</td>
            <td width="13%">Aflsáttarverð</td>
            <td width="5%">Afsláttur í %</td>
            <td width="9%">Bætt við</td>
            <td width="9%">Til í öllum stærðum?</td>
            <td width="9%">Breyta</td>
        </tr>
        ';

$sql3 = mysql_query("SELECT * FROM products WHERE store = '$currentstore' AND product_name LIKE '%$searchAll%'
                     OR store = '$currentstore' AND price LIKE '%$searchAll%' 
                     OR store = '$currentstore' AND trademark LIKE '%$searchAll%' 
                     OR store = '$currentstore' AND category LIKE '%$searchAll%' 
                     OR store = '$currentstore' AND subcategory LIKE '%$searchAll%'
                     OR store = '$currentstore' AND id LIKE '%$searchAll%'                      
                     ORDER BY id DESC ");
 while ($row=mysql_fetch_array($sql3)) {
            $id = $row{"id"};
            $product_name = $row{"product_name"};
            $product_name = strtolower($product_name); 
            $product_name = ucfirst($product_name); 
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
                    $subsql = mysql_query("SELECT * FROM sub_products WHERE style_id = '$style_id'"); //SELECT * ÞÝÐIR SELECT ALL
                    $subCount = mysql_num_rows($subsql);
                    $subsql2 = mysql_query("SELECT * FROM sub_products WHERE style_id = '$style_id' AND available > '0'"); //SELECT * ÞÝÐIR SELECT ALL
                    $subCount2 = mysql_num_rows($subsql2);
                    if($subCount === $subCount2 & $subCount != 0) {
                        $ertil = "<div class='status-1'></div>";
                    }else{
                        $ertil = "<div class='status-0'></div>";
                    }
            //AVAILABLE MAGNSKOÐUN TILBÚIN
            echo '
            <tr>
            <td><div class="status-'.$status.'"></div></td>
            <td>' . $visited . ' sinnum</td>
            <td><a href="product_edit.php?id=' . $id . '">' . $product_name . '</a></td>
            <td style="font-size:12px;"> ' . $style_id .'</td>
            <td> <img src="../images/inventory/' . $id . '_thumb.jpg" alt="' . $product_name . '" width="80" height="100" border="1"></td>
            <td> 
              <form action="inventory_list.php" enctype="multipart/form-data" name="edit" method="post">
                <input name="web_price" class="input align_left"  type="text" value="' . $price . '" size="7"> 
                <input name="button" class="button" type="submit" value="Breyta"/> 
                <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
              </form>
            </td>
            <td>' . $realprice . '</td>
            <td> 
              <form action="inventory_list.php" enctype="multipart/form-data" name="edit" method="post">
                <input name="dcount" class="input align_left"  type="text" value="' . $x . '" size="3" maxlength="3">
                <input name="button" class="button" type="submit" value="Breyta"/> 
                <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
              </form>
            </td>
            <td>' . $date_added . '</td>
            <td>' . $ertil . '</td>
            <td><a class="button" href="inventory_list.php?deleteid=$id">X</a> <a class="button" href="product_edit.php?id=$id . "">?</a> </td>
            </tr>
            ';
    } 
}

//LEITA EINUNGIS EFTIR NAFNI VÖRU
if (isset($_POST['search'])){
$search = $_POST['search'];

$sql3 = mysql_query("SELECT * FROM products WHERE product_name LIKE '%$search%' AND status = 1 OR id = '$search' AND status = 1 ORDER BY id DESC ");
 while ($row=mysql_fetch_array($sql3)) {
        $id = $row{"id"};
        $product_name = $row{"product_name"};
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '-second_thumb.jpg" class="p-image bottom"></a>
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '_thumb.jpg" class="p-image top"></a> 
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
        echo '
<li> 
    <div class="p-wrapper">
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '-second_thumb.jpg" class="p-image bottom"></a>
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '_thumb.jpg" class="p-image top"></a>        <div class="p-info"> 
            <h4> ' . $product_name . ' </h4>
            <p> ' . $trademark . ' </p>
            <div class="p-buy">
               <h4>' . $realprice . ' kr.'. $oldprice .'</h4>
            </div>
        </div>
    </div>
    '.$discount_display.'
</li>';
    } 
}

//LEIT EFTIR LIT 

if (isset($_POST['color'])){
$color = $_POST['color'];
$color2 = "111";
  $color = trim($color,"-");
  $colorArray = explode("-", $color);

    foreach ($colorArray as $key => $value) {
    $product_info = explode("-", $value);
    $color1 = $product_info[0]; // GET ID
    if(isset($product_info[1]) & $product_info[1] != ""){
        $color2 = $product_info[1]; // GET AMOUNT
    }
}

$sql3 = mysql_query("SELECT * FROM products WHERE color = '$color1' OR color = '$color2'  AND status = 1 ORDER BY id DESC ");
 while ($row=mysql_fetch_array($sql3)) {
        $id = $row{"id"};
        $product_name = $row{"product_name"};
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '-second_thumb.jpg" class="p-image bottom"></a>
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '_thumb.jpg" class="p-image top"></a> 
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
        echo '
<li> 
    <div class="p-wrapper">
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '-second_thumb.jpg" class="p-image bottom"></a>
      <a href="/vara/' . $id . '"><img src="/images/inventory/' . $id . '_thumb.jpg" class="p-image top"></a>        <div class="p-info"> 
            <h4> ' . $product_name . ' </h4>
            <p> ' . $trademark . ' </p>
            <div class="p-buy">
               <h4>' . $realprice . ' kr.'. $oldprice .'</h4>
            </div>
        </div>
    </div>
    '.$discount_display.'
</li>';
    } 
}
?>