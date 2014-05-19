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
//BREYTA PRICE
if(isset($_POST{'nomopart'})) {
$nomopart = preg_replace("#[^0-9]#","", $_POST{'nomopart'});
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE stores SET nomopart = '$nomopart' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:all_stores.php");
  exit();
}

//BREYTA LOCATION
if(isset($_POST{'location'})) {
$new_location = $_POST{'location'};
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE stores SET location = '$new_location' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:all_stores.php");
  exit();
}

//BREYTA EMAIL
if(isset($_POST{'email'})) {
$new_email = $_POST{'email'};
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE stores SET email = '$new_email' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:all_stores.php");
  exit();
}

//BREYTA Kennitölu
if(isset($_POST{'kennitala'})) {
$new_kennitala = $_POST{'kennitala'};
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE stores SET kennitala = '$new_kennitala' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:all_stores.php");
  exit();
}

//BREYTA about
if(isset($_POST{'about'})) {
$new_about = $_POST{'about'};
$id_to_change = $_POST{'id_to_change'};
  $sql = mysql_query("UPDATE stores SET about = '$new_about' WHERE id = '$id_to_change';")or die(mysql_error());
  header("location:all_stores.php");
  exit();
}
?>

<?php
// PAGINATION STARTS
//Counting items in database
$count_sql = mysql_query("SELECT * FROM stores"); //SELECT * ÞÝÐIR SELECT ALL
$count = mysql_num_rows($count_sql);

// Replacing everything from the GET variable except numbers
if(isset($_GET['page'])){
  $page = preg_replace("#[^0-9]#","", $_GET['page']);
} else {
  $page = 1;
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

if($pages !=1) {

  if($page != 1){
    $prev = $page - 1;
    $pagination .= '<a href="all_stores.php?page='.$prev.'"> < </a>';
  }else {
    $pagination .= '<';
  }
  if($pages > 1) {
    for($i = 1; $i <= $pages; $i++) {
            $active ="";
        if($i == $page){
          $active .= "active";
        }
        $pagination .= '<a href="all_stores.php?page='.$i.'" class="'.$active.'"> '.$i.' </a>';
    }
  }
  if($page != $pages){
    $next = $page + 1;
    $pagination .= '<a href="all_stores.php?page='.$next.'"> > </a>';
  } else {
    $pagination .= '>';
  }
} else {
    $pagination = "";
}


if ($manager == "admin") {
// PRINTING OUT the users
    $user_list = "";

    $sql2 = mysql_query("SELECT * FROM stores"); //SELECT * ÞÝÐIR SELECT ALL
    $productCount = mysql_num_rows($sql2);

    $sql = mysql_query("SELECT * FROM stores ORDER BY id DESC $limit"); //SELECT * ÞÝÐIR SELECT ALL
    if($productCount>0){
        while ($row=mysql_fetch_array($sql)) {
            $id = $row{"id"};
            $name = $row{"store_name"};
            $location = $row{"location"};
            $email = $row{"email"};
            $kennitala = $row{"kennitala"};
            $curr_nomopart = $row["nomopart"];
            $user_list .='<tr>';
            $user_list .='<td>' . $name . '</td>';
            $user_list .='<td> <form action="" enctype="multipart/form-data" name="edit" method="post">
            <input name="kennitala" class="input align_left"  type="text" value="' . $kennitala . '" > 
            <input name="button" class="button" type="submit" value="Breyta"/> 
            <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
          </form></td>';
            $user_list .='<td> <form action="" enctype="multipart/form-data" name="edit" method="post">
            <input name="location" class="input align_left"  type="text" value="' . $location . '" > 
            <input name="button" class="button" type="submit" value="Breyta"/> 
            <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
          </form></td>';
            $user_list .='<td> <form action="" enctype="multipart/form-data" name="edit" method="post">
            <input name="email" class="input align_left"  type="text" value="' . $email . '" > 
            <input name="button" class="button" type="submit" value="Breyta"/> 
            <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
          </form></td>';
            $user_list .='<td> <form action="" enctype="multipart/form-data" name="edit" method="post">
            <input name="nomopart" class="input align_left"  type="text" value="' . $curr_nomopart . '" size="3" > 
            <input name="button" class="button" type="submit" value="Breyta"/> 
            <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
          </form></td>';
            $user_list .='</tr>';
        } 
    } else {
        $user_list = "Það eru engar vörur í búðinni";
    } 

//Printing out [STORE_list] AS ADMIN ---------------------------

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
  <h1 style="margin-top:30px;">Allar Verslanir</h1>
            <span style="float:right">
                <?php echo $pagination; ?>
            </span>
        <div id="admin-container">
            <?php
echo 'Fjöldi Verslana: <strong>'. $productCount. '</strong>';
?>
    <table class="cart_table">
                <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                    <td width="18%">Verslun <br/></td>
                    <td width="10%">Kennitala</td>
                    <td width="10%">Staðsetning</td>
                    <td width="8%">Netfang</td>
                    <td width="8%">NOMO %</td>
                </tr>
                    <?php echo $user_list; ?>
    </table>
            <span style="float:right">
                <?php echo $pagination; ?>
            </span> 
        </div>    






</body>
</html>
