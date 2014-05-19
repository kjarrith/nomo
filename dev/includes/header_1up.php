
<div id="header"> 

	<form name="search" action="../static_pages/leitarnidurstodur.php" method="GET"> 
        <input name="search" placeholder="Leitaðu..." type="text"  class="search" />
        <input type="submit" style="position: absolute; left: -9999px"/>
    </form>
	<a href="../index.php"> <img src="../images/nomologo.png" id="logo" style="width:140px; padding:0;margin:0;"/> </a>
         
            <div class="info-wrapper">
                <a href="../templates/cart.php"><span class="info-span"> <img src="../images/cart.png"> </span></a>
                <a href="#"><span class="info-main">
            <div id='cssmenu'>
                    <ul>

                       <li class='has-sub '><a href="../templates/login.php">
                        <span>
                        <h5> 
                            <?php 
                        if(isset($_SESSION{"username"})) {
                            echo $name ;
                        } else {
                            echo "Skráðu þig inn!";
                        };
                            ?>
                        </h5>
                        </span>
                    </a>
                        
                          <ul>
                                 <li class='has-sub '><a href='../templates/user.php'><span>Stillingar</span></a>
                                   
                                 </li>
                                 <li class='bottom-sub '><a href='../templates/storescripts/logout.php'><span>Skrá út</span></a>

                                 </li>
                          </ul>
                       </li>
                    </ul>

            </div>

        </span></a>
             </div>
             <a href="../templates/cart.php">
             <div class="cart-display"> <h3><?php 
                                            if(isset($_SESSION{"cartTotal"})) {
                                                echo $_SESSION{"cartTotal"};
                                                } else {
                                                   echo "0 kr.";
                                                }
                                                ?></h3> </div> </a>
</div>