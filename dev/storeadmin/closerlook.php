<?php

include '../templates/storescripts/connect_to_mysql.php';

$search = $_POST['search'];
echo '             <tr style="background-color: #333; color:#fff; font-weight: bold; ">
                    <td width="18%">Nafn <br/></td>
                    <td width="10%">Heimilisfang</td>
                    <td width="15%">E-Mail</td>
                    <td width="8%">Kyn</td>
                    <td width="8%">Afsláttur</td>
                    <td width="10%">Sími</td>
                    <td width="9%">Bætt við</td>
                    <td width="2%">Breyta</td>
                </tr>';
$sql3 = mysql_query("SELECT * FROM users WHERE name LIKE '%$search%' OR email  LIKE '%$search%' OR address  LIKE '%$search%' OR gender  LIKE '%$search%' ORDER BY id DESC ");
 while ($row=mysql_fetch_array($sql3)) {
            $id = $row{"id"};
            $name = $row{"name"};
            $address = $row{"address"};
            $email = $row{"email"};
            $curr_discount = $row["discount"];
            $gender = $row{"gender"};
            $phone = $row{"phone"};
            $date_added = strftime("%d %b %y", strtotime($row{"date_added"}));
            echo '<tr>
            <td>'.$name.'</td>
            <td>'.$address.'</td>
            <td>'. $email . '</td>
            <td>' . $gender . '</td>
            <td> <form action="" enctype="multipart/form-data" name="edit" method="post">
            <input name="discount" class="input align_left"  type="text" value="' . $curr_discount . '" size="3" > 
            <input name="button" class="button" type="submit" value="Breyta"/> 
            <input name="id_to_change" class="button" type="hidden" value="' . $id . '"/> 
            </form>
            </td>
            <td>' . $phone . '</td>
            <td>' . $date_added . '</td>
            <td> 
            <form action ="all_users.php" method="post"> 
                <input name="acceptbtn" type="submit" class="button" Value="X" style="font-size:12px;"/> 
                <input name="user_to_delete" type="hidden" value="' . $id . '"/>   
            </form> 
            <a class="button" href="product_edit.php?id='.$id.'">?</a> 
            </td>
            </tr>'
            ;
    } 
$_GET['worked'] = "worked";
?>