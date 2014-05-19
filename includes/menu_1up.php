<div id="menu-wrapper">
    <div id="dropmenu">

            <ul class="menu">

                <li class="menu_right"><a href="/karlar" class="drop">Menn</a><!-- Begin 3 columns Item -->
                
                    <div class="dropdown_3columns align_right"><!-- Begin 3 columns container -->
                        
                        <div class="col_3">
                            <h2> <strong>Hann</strong></h2>
                        </div>
                        
                        <div class="col_1">
                            <ul class="greybox-wrap">
                                <a href="/karlar"><li style="border-bottom:1px solid #ccc;"><span style="color:#00A194">Allt það nýjasta</span></li></a>
                                 <?php
                                echo $category_list_menn;
                            ?>
                            </ul>   
                
                        </div>
                                                               
                        <div class="col_1_reccomend">
                            <ul class="greybox">
                                <h5>Sjáðu líka:</h5>
                            <hr>
                            <?php
                                $sql = mysql_query("SELECT * FROM products WHERE category = 'menn' AND dcount > 0"); //SELECT * ÞÝÐIR SELECT ALL
                                    $discountCount = mysql_num_rows($sql);
                                    if($discountCount>0){
                                       echo '<a href="karlar_utsala"><li class="special2">Útsala!</li></a>';
                                    }
                            ?>
                                <a href="/karlar_top"><li class="special">Vinsælast</li></a>
                                <!--<a href="../static_pages/karla_vid_maelum_med.php"><li class="special">Nomo mælir með</li></a>-->

                            </ul>   
                
                        </div>
                
                    
                    </div><!-- End 3 columns container -->
                    
                </li><!-- End 3 columns Item -->

                 <li class="menu_right" ><a style="color:#C48780;" href="/verslanir" class="drop">Búðirnar</a><!-- Begin 3 columns Item -->
                
                    <div class="dropdown_3columns align_right"><!-- Begin 3 columns container -->
                        
                        <div class="col_3">
                            <h2> <strong>Nomo Fjölskyldan</strong></h2>
                        </div>
                        
                        <div class="col_1">
                            <ul class="greybox-wrap-store">
                                 <?php
                                echo $store_list;
                            ?>
                            </ul> 
                        </div>
                                                               
                
                    
                    </div><!-- End 3 columns container -->
                    
                </li><!-- End 3 columns Item -->

                 <li class="menu_right"><a href="/konur" class="drop-right">Konur</a><!-- Begin 3 columns Item -->
                
                    <div class="dropdown_3columns align_right"><!-- Begin 3 columns container -->
                        
                        <div class="col_3">
                            <h2><strong>Hún</strong></h2>
                        </div>
                        
                        <div class="col_1">
                            <ul class="greybox-wrap">
                            <a href="/konur"><li style="border-bottom:1px solid #ccc;"><span style="color:#00A194">Allt það nýjasta</span></li></a>
                                 <?php
                                echo $category_list_konur;
                            ?>
                            </ul>   
                
                        </div>
                                                               
                        <div class="col_1_reccomend">
                            <ul class="greybox">
                                <h5>Sjáðu líka:</h5>
                            <hr>
                            <?php
                                $sql = mysql_query("SELECT * FROM products WHERE category = 'menn' AND dcount > 0"); //SELECT * ÞÝÐIR SELECT ALL
                                    $discountCount = mysql_num_rows($sql);
                                    if($discountCount>0){
                                       echo '<a href="/konur_utsala"><li class="special2">Útsala!</li></a>';
                                    }
                            ?>
                                <a href="/konur_top"><li class="special">Vinsælast</li></a>
                                <!--<a href="../static_pages/karla_vid_maelum_med.php"><li class="special">Nomo mælir með</li></a>-->

                            </ul>   
                
                        </div>
                    
                    </div><!-- End 3 columns container -->
                    
                </li><!-- End 3 columns Item -->

            </ul>

    </div>
</div>