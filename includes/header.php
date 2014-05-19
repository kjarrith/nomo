
<div id="header"> 
	<form name="search" action="/static_pages/leitarnidurstodur.php" method="GET"> 
        <input name="search" placeholder="Leitaðu..." type="text"  class="search" />
        <input type="submit" style="position: absolute; left: -9999px"/>
    </form>
	<img src="images/logos/grey_50px_4.png" id="logo" />
         
            <div class="info-wrapper">
                <a href="karfa"><span class="info-span"> <img src="images/cart.png"> </span></a>
                <a href="#"><span class="info-main">
            <div id='cssmenu'>
                    <ul>
                            <?php 
                        if(isset($_COOKIE{"uid"})) {
                            $pieces = explode(" ", $name);
                            echo " <li class='has-sub '><a href='user'>
                        <span>
                        <h5>".
                        $pieces[0]
                        ;
                        } else {
                            echo " <li class='has-sub '><a href='accounts-landing'>
                        <span>
                        <h5 style='color:#C48780;'>SKRÁÐU ÞIG INN";
                        };
                            ?>
                        </h5>
                        </span>
                    </a>
                        
                          <ul>

                        <?php 
                            if(isset($_COOKIE{"uid"})) {
                                echo "
                                <li class='has-sub '><a href='user'><span>Stillingar</span></a></li>
                                <li class='has-sub '><a href='/fataskapurinn'><span>Fataskápurinn</span></a> </li>
                                <li class='bottom-sub '><a href='/templates/storescripts/logout.php'><span>Skrá út</span></a></li>
                                ";
                            } else {
                                echo "";
                            };
                        ?>
                          </ul>
                       </li>
                    </ul>

            </div>

        </span></a>
             </div>
             <a href="karfa">
                <div class="cart-display"> 
                    <h3><?php 
                            if(isset($_SESSION{"cartTotal"})) {
                                echo $_SESSION{"cartTotal"};
                                } else {
                                echo "0 kr.";
                                }
                        ?>
                    </h3> 
                </div>
            </a>
</div>