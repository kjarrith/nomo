<?php
function pagination($path){
    $count = mysql_num_rows($count_sql);

    // Replacing everything from the GET variable except numbers
    if(isset($_GET['page'])){
      $page = preg_replace("#[^0-9]#","", $_GET['page']);
    } else {
      $page = 1;
    }
    //NUMBER OF ITEMS DISPLAYED
    $perPage = 24;
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
        $pagination .= '<a href="'.$path.'"'.$prev.'"> < </a>';
      }else {
        $pagination .= '<';
      }
      if($pages > 1) {
        for($i = 1; $i <= $pages; $i++) {
            $pagination .= '<a href="'.$path.'"'.$i.'"> '.$i.' </a>';
        }
      }
      if($page != $pages){
        $next = $page + 1;
        $pagination .= '<a href="'.$path.'"'.$next.'"> > </a>';
      } else {
        $pagination .= '>';
      }


    }

}