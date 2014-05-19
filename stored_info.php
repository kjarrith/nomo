Stored info


//Notað þegar notandi ýtir á LOGIN en er nú þegar loggaður inn og er þá sendur aftur á index.php
<?php

ob_start();
session_start();
if (isset($_SESSION{"username"})) {
   header("location:index.php");
    exit();
}
?>

//DROPDOWN MENU HTML KÓÐINN, CSS ER Í CSSMENU/MENU_ASSETS/STYLES.CSS

<div id='cssmenu'>
<ul>

   <li class='has-sub '><a href='#'><span>Menn</span></a>
      <ul>
             <li class='has-sub '><a href='#'><span>Bolir</span></a>
                <ul>
                   <li><a href='#'><span>Sub Product</span></a></li>
                   <li><a href='#'><span>Sub Product</span></a></li>
                </ul>
             </li>
             <li class='has-sub '><a href='#'><span>Product 2</span></a>
                <ul>
                   <li><a href='#'><span>Sub Product</span></a></li>
                   <li><a href='#'><span>Sub Product</span></a></li>
                </ul>
             </li>
      </ul>
   </li>

   <li class='has-sub '><a href='#'><span>Konur</span></a>
      <ul>
         <li class='has-sub '><a href='#'><span>Bolir</span></a>
            <ul>
               <li><a href='#'><span>Sub Product</span></a></li>
               <li><a href='#'><span>Sub Product</span></a></li>
            </ul>
         </li>
         <li class='has-sub '><a href='#'><span>Product 2</span></a>
            <ul>
               <li><a href='#'><span>Sub Product</span></a></li>
               <li><a href='#'><span>Sub Product</span></a></li>
            </ul>
         </li>
      </ul>
   </li>

   <li class='has-sub '><a href='#'><span>Börn</span></a>
      <ul>
         <li class='has-sub '><a href='#'><span>Bolir</span></a>
            <ul>
               <li><a href='#'><span>Sub Product</span></a></li>
               <li><a href='#'><span>Sub Product</span></a></li>
            </ul>
         </li>
         <li class='has-sub '><a href='#'><span>Product 2</span></a>
            <ul>
               <li><a href='#'><span>Sub Product</span></a></li>
               <li><a href='#'><span>Sub Product</span></a></li>
            </ul>
         </li>
      </ul>
   </li>

</ul>

// UPPRUNALEGA LOGINIÐ MEÐ PHP KÓÐA

<a href="login.php"><div id="signin"> <h5> <?php 
    if(isset($_SESSION{"username"})) {
        echo "Hi &nbsp;" . $_SESSION{"username"} . "!";
    } else {
        echo "Skráðu þig inn!";
    };

    ?> </h5> </div></a>

//4 COLUMN DROPDOWN MENU ITEM

<li><a href="#" class="drop">4 Columns</a><!-- Begin 4 columns Item -->
    
        <div class="dropdown_4columns"><!-- Begin 4 columns container -->
        
            <div class="col_4">
                <h2>This is a heading title</h2>
            </div>
            
            <div class="col_1">
            
                <h3>Some Links</h3>
                <ul>
                    <li><a href="#">ThemeForest</a></li>
                    <li><a href="#">GraphicRiver</a></li>
                    <li><a href="#">ActiveDen</a></li>
                    <li><a href="#">VideoHive</a></li>
                    <li><a href="#">3DOcean</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Useful Links</h3>
                <ul>
                    <li><a href="#">NetTuts</a></li>
                    <li><a href="#">VectorTuts</a></li>
                    <li><a href="#">PsdTuts</a></li>
                    <li><a href="#">PhotoTuts</a></li>
                    <li><a href="#">ActiveTuts</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Other Stuff</h3>
                <ul>
                    <li><a href="#">FreelanceSwitch</a></li>
                    <li><a href="#">Creattica</a></li>
                    <li><a href="#">WorkAwesome</a></li>
                    <li><a href="#">Mac Apps</a></li>
                    <li><a href="#">Web Apps</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Misc</h3>
                <ul>
                    <li><a href="#">Design</a></li>
                    <li><a href="#">Logo</a></li>
                    <li><a href="#">Flash</a></li>
                    <li><a href="#">Illustration</a></li>
                    <li><a href="#">More...</a></li>
                </ul>   
                 
            </div>
            
        </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->

//GAMLA LOGIN PHP PARTURINN

<?php
//No need to be in the LOGIN section
ob_start();
session_start();

?>

<?php
if(isset($_POST{"username"}) && isset($_POST{"password"})) {
    //FILTERING
    $user = preg_replace('#{^A-Za-z0-9}#i', '', $_POST{"username"});
    $password = preg_replace('#{^A-Za-z0-9}#i', '', $_POST{"password"});

    //CONNECT TO DATABASE

    include "../templates/storescripts/connect_to_mysql.php";
    $sql = mysql_query("SELECT id 
                        FROM users 
                        WHERE username='$user' AND password='$password' 
                        LIMIT 1");

    //MAKE SURE PERSON EXISTS IN DATABASE 

    $exist_count = mysql_num_rows($sql); 
    if ($exist_count==1) {
        while($row = mysql_fetch_array($sql)) {
            $id = $row{'id'};
            $name = $row['name'];
        }
        $_SESSION{"id"} = $id;
        $_SESSION{"username"} = $user;
        $_SESSION{"password"} = $password;
        $_SESSION{"name"} = $name;
        header("location:../index.php");
        exit();
    } else {
        echo 'That information is incorrect. <a href="login.php"> Click here to try again </a>';
        exit();
    }

}

?>

//Stack Overflow Question 

for($i = 1; $i <= $available; $i++) {
        echo "<option value=\"{$i}\">{$i}</option>";
    }