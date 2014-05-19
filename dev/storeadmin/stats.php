<?php
//STARTING A CONNECTION TO THE DATABASE
ob_start();
session_start();
include '../templates/storescripts/connect_to_mysql.php';


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

//ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>

<?php
//COUNT THE CART INPUTS FROM YESTERDAY
$sql = mysql_query("SELECT * FROM cart_inputs WHERE DATE(`date_added`) = CURDATE() - 1 ");
$cart_inputs_today = mysql_num_rows($sql); 

//COUNT HOW MANY ORDERS WE HAVE RECEIVED FROM EACH CATEGORY // BOYS
$cat_list_girls ="";
$sql1 = mysql_query("SELECT * FROM category WHERE category_gender = 'menn' ");
while ($row=mysql_fetch_array($sql1)) {
                //ENDURSTILLA $CAT_SUM EFTIR HVERN HRING
                $cat_sum = 0;
                //SÆKJA UPPLÝSINGAR UM FLOKKINN
                $catid = $row{"category_id"};
                $catname = $row['category_name'];
                $cat_list_boys .= '<tr><td width="70%">'.$catname.' <br/></td>' ;
                //SÆKJA ÖLL VÖRU ID SEM ERU Í ÞESSUM FLOKK
                  $sql2 = mysql_query("SELECT * FROM products WHERE subcategory = '$catid'");
                  while ($row=mysql_fetch_array($sql2)) {
                    $pid = $row ['id'];
                    //SÆKJA ALLAR PANTANIR SEM HAFA ÁTT SÉR STAÐ Á ÞESSARI VÖRU
                    $sql3 = mysql_query("SELECT * FROM orders WHERE product_id = '$pid' ");
                    $cat_orders = mysql_num_rows($sql3);
                    $cat_sum = $cat_sum + $cat_orders;
                  } // WHILE SQL2 ENDAR
                  $cat_list_boys .= '<td width="70%">'.$cat_sum.' <br/></td></tr>'  ; 
        } //WHILE SQL ENDAR

$sql1 = mysql_query("SELECT * FROM category WHERE category_gender = 'konur' ");
while ($row=mysql_fetch_array($sql1)) {
                //ENDURSTILLA $CAT_SUM EFTIR HVERN HRING
                $cat_sum = 0;
                //SÆKJA UPPLÝSINGAR UM FLOKKINN
                $catid = $row{"category_id"};
                $catname = $row['category_name'];
                $cat_list_girls .= '<tr><td width="70%">'.$catname.' <br/></td>' ;
                //SÆKJA ÖLL VÖRU ID SEM ERU Í ÞESSUM FLOKK
                  $sql2 = mysql_query("SELECT * FROM products WHERE subcategory = '$catid'");
                  while ($row=mysql_fetch_array($sql2)) {
                    $pid = $row ['id'];
                    //SÆKJA ALLAR PANTANIR SEM HAFA ÁTT SÉR STAÐ Á ÞESSARI VÖRU
                    $sql3 = mysql_query("SELECT * FROM orders WHERE product_id = '$pid' ");
                    $cat_orders = mysql_num_rows($sql3);
                    $cat_sum = $cat_sum + $cat_orders;
                  } // WHILE SQL2 ENDAR
                  $cat_list_girls .= '<td width="70%">'.$cat_sum.' <br/></td></tr>'  ; 
        } //WHILE SQL ENDAR

?>

<!DOCTYPE html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, valkvíði">
<title>Stats</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />

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
      $.post("closerlook.php",{search:value}, function(data){
        $("#results").html(data);
      });
  }
</script>

</head>

<body >

<?php include_once("../includes/header_admin.php"); ?>
<?php include_once("../includes/menu-owner.php"); ?>

<div id="main-admin" style="text-align: center;"> 
  <h1>Stats</h1>
        <div id="admin-container" style="width:98%; margin:0px auto;">
    <h3>Fjöldi vörunúmera í körfu í gær</h3>
    <table class="cart_table" id="results"  style="width:400px; margin:0px auto;">
                  <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                    <td width="70%">Sett í körfu í Gær <br/></td>
                    <td width="30%"><?php echo $cart_inputs_today ?></td>
                  </tr>
    </table>
    <h3>Fjöldi pantana í vöruflokkum : Karlar</h3>
      <table class="cart_table" id="results"  style="width:400px; margin:0px auto;">
                          <?php echo $cat_list_boys;?>

    </table>
        </table>
    <h3>Fjöldi pantana í vöruflokkum : Konur</h3>
      <table class="cart_table" id="results"  style="width:400px; margin:0px auto;">
                          <?php echo $cat_list_girls;?>

    </table>
        </div>    






</body>
</html>
