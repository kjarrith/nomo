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
//Sánka að okkur ýmsum uppl.
$admin_store_list = "";
$sql = mysql_query("SELECT * FROM stores"); //SELECT * ÞÝÐIR SELECT ALL
$storeCount = mysql_num_rows($sql);
if($storeCount>0){
    while ($row=mysql_fetch_array($sql)) {
        $list_store = $row{"store_name"};
        $admin_store_list .= '<option value='.$list_store.'>'.$list_store.'</option>';
    } 
}
?>

<?php
//ÞÁTTA UPPLÝSINGAR FRÁ USERFORMINU

if(isset($_POST{'name'})) {

    $name = mysql_real_escape_string($_POST{'name'});
    $email = mysql_real_escape_string($_POST{'email'});
    $address = mysql_real_escape_string($_POST{'address'});
    $gender = mysql_real_escape_string($_POST{'gender'});
    $password = $_POST['password'];
//ADD [USER] TO DATABASE
if (strlen($password)>2) {
    $password = sha1($password);
    $sql = mysql_query("INSERT INTO users (name, email, address, gender, password, date_added) VALUES('$name', '$email', '$address', '$gender', '$password', now() ) ")or die(mysql_error());
header("location: stillingar.php");
exit();
}
}
?>

<?php
//ÞÁTTA UPPLÝSINGAR FRÁ ADMINFORMINU

if(isset($_POST['newadmin']) && isset($_POST['newpassword'])) {

    $newadmin = mysql_real_escape_string($_POST['newadmin']);
    $newpassword = $_POST['newpassword'];
    $newstore = mysql_real_escape_string($_POST['newstore']);
    $newemail = mysql_real_escape_string($_POST['newemail']);
//ADD [USER] TO DATABASE
if (strlen($newpassword)>2) {
    $newpassword = sha1($newpassword);
    $sql = mysql_query("INSERT INTO admin (username, password, store) VALUES('$newadmin', '$newpassword', '$newstore') ")or die(mysql_error());
    mysql_query("INSERT INTO stores (store_name, admin, nomopart, email) VALUES('$newstore', '$newadmin', '15', '$newemail') ") or die(mysql_error());
header("location: stillingar.php");
exit();
}
}
?>

<?php
//BÚA TIL NÝJAN FATAFLOKK

if(isset($_POST['category_name']) && isset($_POST['category_gender'])) {

    $category_name = mysql_real_escape_string($_POST['category_name']);
    $category_id = mysql_real_escape_string($_POST['category_id']);
    $category_description = mysql_real_escape_string($_POST['category_description']);
    $category_gender = mysql_real_escape_string($_POST['category_gender']);
//ADD [USER] TO DATABASE
    $sql = mysql_query("INSERT INTO category (category_name, category_id, category_description, category_gender) VALUES('$category_name', '$category_id', '$category_description', '$category_gender') ")or die(mysql_error());
header("location: stillingar.php");
exit();
}

?>

<?php
//Senda rukkun

if(isset($_POST['addon_store']) && isset($_POST['type'])) {

    $addon_store = mysql_real_escape_string($_POST['addon_store']);
    $other = mysql_real_escape_string($_POST['other']);
    $type = mysql_real_escape_string($_POST['type']);
    $amount = preg_replace('#{0-9}#i', '', $_POST{"amount"});
//ADD [ADDON] TO DATABASE
    $sql = mysql_query("INSERT INTO addons (type, store, amount, other, date_added) 
                        VALUES('$type', '$addon_store', '$amount', '$other', now() ) ")or die(mysql_error());
header("location: stillingar.php");
exit();
}

?>

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Admin</title>

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
<?php include_once("../includes/menu-owner.php"); ?>




<div id="main-admin"> 
    <h1 style="margin-top:30px;">Aðgerðir</h1>
    <table class="cart_table">
                <form method="post" action="" onsubmit="return validateForm()" >
                    <h3>Senda rukkun</h3>
                    <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                        <td width="18%"> 
                            <select name="addon_store" id="subcategory">
                                <option value="">Veldu búð</option>
                                <?php 
                                    echo $admin_store_list
                                ?>
                            </select>
                        </td>
                        <td width="10%"><input type="text" name="type" class="input" placeholder="Tegund Rukkunar" size="20"  /></td>
                        <td width="10%"><input type="text" name="amount" class="input" placeholder="Upphæð m.vsk" size="6"  /></td>
                        <td width="10%"><input type="text" name="other" class="input" placeholder="Annað" size="5" /></td>                  
                        <td width="15%"><input type="submit" class="button margin" value="Senda!" name="submit" /></td>
                    </tr>
                </form>
    </table>
    <table class="cart_table">
                <form method="post" action="" onsubmit="return validateForm()" >
                    <h3>Bæta við Notanda</h3>
                    <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                        <td width="18%"> 
                            <input type="text" name="name" class="input" placeholder="Fullt Nafn" />
                        </td>
                        <td width="10%"><input type="text" name="email" class="input" placeholder="Netfang" /></td>
                        <td width="10%"><input type="text" name="address"  class="input" placeholder="Heimilisfang"/></td>
                        <td width="10%"> 
                            <input type="radio" name="gender" value="karl">  Strákur<br/>
                            <input type="radio" name="gender" value="kona">  Stelpa
                        </td>
                        <td width="10%"><input type="password" name="password"  class="input" placeholder="Lykilorð"/></td>
                        <td width="15%"> <input type="submit" class="button margin" value="Komið!" name="submit" /></td>
                    </tr>
                </form>
    </table>
    <table class="cart_table">
                <form method="post" action="" onsubmit="return validateForm()" >
                    <h3>Bæta við Verslun</h3>
                    <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                        <td width="18%"> 
                            <input type="text" name="newadmin" class="input" placeholder="Notendanafn tengiliðs" />
                        </td>
                        <td width="10%">
                            <input type="password" name="newpassword"  class="input" placeholder="Lykilorð"/>
                        </td>
                        <td width="10%">
                            <input type="text" name="newstore" class="input" placeholder="Heiti Verslunar" />
                        </td>
                        <td width="10%">
                            <input type="text" name="newemail" class="input" placeholder="Netfang" />
                        </td>
                        <td width="15%">
                            <input type="submit" class="button margin" value="Komið!" name="submit" />
                        </td>
                    </tr>
                </form>
    </table>
        <table class="cart_table">
                <form method="post" action="" onsubmit="return validateForm()" >
                    <h3>Bæta við Fataflokk</h3>
                    <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                        <td width="18%"> 
                            <input type="text" name="category_name" class="input" placeholder="Nafn Flokks" />
                        </td>
                        <td width="10%">
                              <input type="text" name="category_id" class="input" placeholder="Id_nafn Flokks" />
                        </td>
                        <td width="10%">
                            <textarea rows="5" cols="20" name="category_description" class="input align_left" placeholder="Lýsing" maxlength="300"></textarea>
                        </td>
                        <td width="10%">
                            <select name="category_gender" style="font-size: 15px; height:40px;">
                                <option value="">Veldu Kyn</option>
                                <option value="menn">Menn</option>
                                <option value="konur">Konur</option>
                            </select> 
                        </td>
                        <td width="15%">
                            <input type="submit" class="button margin" value="Komið!" name="submit" />
                        </td>
                    </tr>
                </form>
    </table>

</div>






</body>
</html>