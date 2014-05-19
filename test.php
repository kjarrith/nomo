<?php
ob_start();

//CONNECT TO THE DATABASE
include 'storescripts/connect_to_mysql.php';
?>

<?php
$id = 250;
$image_list = '';
//Creating image_list 
if (file_exists(APPDIR . '/images/inventory/' . $id. '.jpg')) {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="/images/inventory/' . $id . '_thumb.jpg">
                <img class="etalage_source_image" src="/images/inventory/' . $id . '.jpg">
            </li>';
}

if (file_exists(APPDIR . '/images/inventory/' . $id. '-second.jpg')) {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="/images/inventory/' . $id . '-second_thumb.jpg">
                <img class="etalage_source_image" src="/images/inventory/' . $id . '-second.jpg">
            </li>';
}

if (file_exists(APPDIR . '/images/inventory/' . $id. '-third.jpg')) {
    $image_list .=  '<li>
                <img class="etalage_thumb_image" src="/images/inventory/' . $id . '-third_thumb.jpg">
                <img class="etalage_source_image" src="/images/inventory/' . $id . '-third.jpg">
            </li>';
}
?>

<!DOCTYPE html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Kjartan Þóris">
<meta name="keywords" content="föt, kringlan, fatamarkaður, verð, ódýrt, heimsending, nomo, miðpunktur, tísku, tíska, netverslun, ">

<link href="http://www.nomo.is/images/opengraphimg.jpg" rel="image_src"/>
<meta property="og:title" content="Nomo - Öll flottustu fötin á Íslandi" >
<meta property="og:image" content="http://www.nomo.is/images/opengraphimg.jpg" />
<meta property="og:url" content="http://www.nomo.is/velkomin" />

<title>Nomo - TEST</title>

<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
<link rel="icon" href="../images/favicon.ico" type="image/x-icon">

<link href="/css/styles.css" rel="stylesheet" type="text/css" />
<link href="/cssmenu/menu_assets/styles.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/demo/menu.css" type="text/css" media="screen" />
<link type="text/css" rel="stylesheet" href="/css/etalage.css" /> 

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
<script type="text/javascript" src="/js/jquery.etalage.min.js"></script> 

<script type="text/javascript">
$(document).ready(function(){
// If your <ul> has the id "etalage":
$('#etalage').etalage();
});
</script>

</head>
<body>

        <div class="etalage-container">    
            <ul id="etalage">
              <li>
                  <img class="etalage_thumb_image" src="images/inventory/250_thumb.jpg">
                  <img class="etalage_source_image" src="images/inventory/250.jpg">
              </li>
                          <li>
                  <img class="etalage_thumb_image" src="images/inventory/250_thumb.jpg">
                  <img class="etalage_source_image" src="images/inventory/250.jpg">
              </li>
                          <li>
                  <img class="etalage_thumb_image" src="images/inventory/250_thumb.jpg">
                  <img class="etalage_source_image" src="images/inventory/250.jpg">
              </li>
              <li>
                  <img class="etalage_thumb_image" src="images/inventory/250-second_thumb.jpg">
                  <img class="etalage_source_image" src="images/inventory/250.jpg">
              </li>
            </ul>
        </div>


</body>
</html>