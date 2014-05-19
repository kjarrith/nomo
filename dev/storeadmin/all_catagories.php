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
//SPURNING TIL STJÓRNANDA UM HVORT EYÐA EIGI Notanda OG EYÐA VÖRU EF ÞAÐ ER VALIÐ
if(isset($_GET{'deleteid'})) {
    echo 'Ertu alveg viss um ad thu viljir eyda Vöru NR. '. $_GET{'deleteid'}. '? <br/> <a href="all_catagories.php?yesdelete='.$_GET{'deleteid'}.'">Ja</a>- <a href="all_catagories.php">Nei<a/> ';
    exit();
}

// REMOVE ITEM FROM SYSTEM AND DELETE ITS PICTURE
if(isset($_GET['yesdelete'])) {
    //DELETE FROM DATABASE
    $id_to_delete = $_GET['yesdelete'];
    $sql = mysql_query("DELETE FROM category WHERE id = '$id_to_delete' LIMIT 1") or die(mysql_error());
    header("location: all_catagories.php");
    exit();
    }
?>

<?php
//ÞÁTTA UPPLÝSINGAR FRÁ FORMINU

if(isset($_POST{'user_name'})) {
    $user_name = mysql_real_escape_string($_POST{'user_name'});
    $user_password = mysql_real_escape_string($_POST{'user_password'});
    $user_username = mysql_real_escape_string($_POST{'user_username'});
    $user_address = mysql_real_escape_string($_POST{'user_address'});
    $user_email = mysql_real_escape_string($_POST{'user_email'});
    $user_phone = mysql_real_escape_string($_POST{'user_phone'});
    $user_gender = mysql_real_escape_string($_POST{'user_gender'});
    if (strlen($user_password)>2) {
    //ADD [USER] TO DATABASE
    $sql = mysql_query("INSERT INTO users (username, password, email, address, phone, gender, name, date_added)
                        VALUES('$user_username', '$user_password', '$user_email', '$user_address', '$user_phone', '$user_gender', '$user_name', now())
                        ")or die(mysql_error());
       header("location: all_users.php");
          exit();
    }
}
?>

<?php

if ($manager == "admin") {
// PRINTING OUT the users
    $user_list = "";
    $sql = mysql_query("SELECT * FROM category ORDER BY category_gender DESC"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql);
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $category_name = $row{"category_name"};
            $category_description = $row{"category_description"};
            $category_id = $row{"category_id"};
            $category_gender = $row{"category_gender"};
            $user_list .='<tr>';
            $user_list .='<td>' . $id . '</td>';
            $user_list .='<td>'. $category_gender . '</td>';
            $user_list .='<td> <form action="all_catagories.php" enctype="multipart/form-data" name="edit" method="post">
             <input name="category_name" class="input align_left"  type="text" value="' . $category_name . '" size="10"> 
            <input name="button" class="button" type="submit" value="Breyta"/> 
            <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
          </form></td>';
            $user_list .='<td> <form action="all_catagories.php" enctype="multipart/form-data" name="edit" method="post">
            <p style="font-size:12px;"> Lýsing:</p> <input name="category_description" class="input align_left"  type="text" value="' . $category_description . '" size="50"> 
            <input name="button" class="button" type="submit" value="Breyta"/> 
            <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
          </form></td>';
            $user_list .='<td>'. $category_id . '</td>';
            $user_list .="<td><a class='button' href='all_catagories.php?deleteid=$id'>X</a> <a class='button' href='product_edit.php?id=$id . ''>?</a> </td>" ;
            $user_list .='</tr>';
        } 
    } else {
        $user_list = "Það eru engar vörur í búðinni";
    } 

//Printing out [STORE_list] AS ADMIN ---------------------------

}
?>

<?php
//Breyta þeim upplýsingum sem beðið erum að breyta í forminu.

//BREYTA Description
if(isset($_POST{'category_description'})) {
$category_description = $_POST{'category_description'};
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE category SET category_description = '$category_description' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:all_catagories.php");
  exit();
}

//BREYTA Nafni
if(isset($_POST{'category_name'})) {
$category_name = $_POST{'category_name'};
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE category SET category_name = '$category_name' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:all_catagories.php");
  exit();
}

?>

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>UserList</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>

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
  
    <a href ="#addproduct">
        <div id="add-product"> 
            <h3> Bæta við flokk <br/> + </h3>
        <br/>
        </div>
    </a>

        <div id="admin-container">

            <h3 align='center'> Notendur</h3>

    <table class="cart_table" >
                <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                    <td width="5%">id</td>
                    <td width="5%">Kyn</td>
                    <td width="5%">Nafn <br/></td>
                    <td width="30%">Lýsing</td>
                    <td width="9%">id Nafn</td>
                    <td width="5%">Breyta</td>
                </tr>
                    <?php echo $user_list; ?>
    </table>

        </div>    

<a name="addproduct"></a>

<form action="all_users.php" enctype="multipart/form-data" name="myform" id="myform" method="post">
      <table width="90%" border=border="0" cellspacing="0" cellpadding="6" id="addtable">
        <tr>
            <td width="20%" align="right"> Nafn Flokks: <br/> </td>
            <td width="80%"> <label>
                <input name="user_name" type="text" class="input align_left" size="64"/>
             </label> </td>
        </tr>
         <tr>
            <td width="20%" align="right"> Notendanafn: <br/> </td>
            <td width="80%"> <label>
                <input name="user_username" type="text" class="input align_left" size="64"/>
             </label> </td>
        </tr>
         <tr>
            <td width="20%" align="right"> Lykilorð: <br/> </td>
            <td width="80%"> <label>
                <input name="user_password" type="password" class="input align_left" size="64"/>
             </label> </td>
        </tr>
        <tr>
            <td width="20%" align="right"> Heimilsfang:</td>
            <td width="80%"> <label>
                <input name="user_address" type="text" class="input align_left" size="64"/>
             </label> </td>
        </tr>
        <tr>
            <td align="right"> Email: </td>
            <td width="80%"> <label>
                <input name="user_email" id="price" type="text" class="input align_left"/>
                </label> 
            </td>
        </tr>
         <tr>
            <td align="right">  Kyn: </td>
            <td width="80%"> <label>
                <select name="user_gender" id="category">
                    <option value="">Veldu kyn</option>
                    <option value="karl">Karl</option>
                    <option value="kona">Kona</option>
                </select>    
                </label> 
            </td>
        </tr>
         <tr>
            <td align="right">  Símanúmer: </td>
            <td width="80%"> <label>
                <input name="user_phone" id="price" type="text"  size="12" class="input align_left"/>
                </label> 
            </td>
        </tr>
         <tr>
            <td align="right">  Bæta notanda við: <br/> <p style="font-size: 12px;">Farðu vel yfir og gáðu hvort eitthvað hafi nokkuð gleymst.</p> </td>
            <td > 
            <label>
                <input name="button" class="button" type="submit" value="Staðfesta"/>                
            </label> 
            </td>
        </tr>
      </table>  
</form>   
            

     <div id="footer-sub">
	All rights reserved
    </div>
</div>






</body>
</html>
