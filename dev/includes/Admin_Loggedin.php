<?php
ob_start();
session_start();
if (isset($_SESSION{"manager"})) {
    header("location:index.php");
    exit();
}
?>

<?php
if(isset($_POST{"username"}) && isset($_POST{"password"})) {
    //FILTERING
    $manager = preg_replace('#{^A-Za-z0-9}#i', '', $_POST{"username"});
    $password = preg_replace('#{^A-Za-z0-9}#i', '', $_POST{"password"});

    //CONNECT TO DATABASE

    include "../templates/storescripts/connect_to_mysql.php";
    $sql = mysql_query("SELECT id 
                        FROM admin 
                        WHERE username='$manager' AND password='$password' 
                        LIMIT 1");

    //MAKE SURE PERSON EXISTS IN DATABASE 

    $exist_count = mysql_num_rows($sql); 
    if ($exist_count==1) {
        while($row = mysql_fetch_array($sql)) {
            $id = $row{'id'};
        }
        $_SESSION{"id"} = $id;
        $_SESSION{"manager"} = $manager;
        $_SESSION{"password"} = $password;
        header("location: index.php");
        exit();
    } else {
        echo 'That information is incorrect. <a href="index.php"> Click here to try again </a>';
        exit();
    }

}

?>