<?php
session_start('');
//CONNECT TO THE DATABASE
include_once 'templates/storescripts/connect_to_mysql.php';
$cssVersion = 33;
$faviconVersion = 4;

    define('APPDIR' ,dirname(__FILE__));
    $parts = explode('/', $_SERVER['REQUEST_URI']);

    //Get rid of empty item
    array_shift($parts);

    $system       = array_shift($parts);
    $flokkur      = array_shift($parts);
    $page         = array_shift($parts);
    $u1           = array_shift($parts);
    $u2           = array_shift($parts);

    switch ($system) {
      case 'velkomin':
        require_once(APPDIR . '/templates/login.php');
        break;
      case 'bloggid':
        require_once(APPDIR . '/blog/landing.php');
        break;
      case 'netgiro':
        require_once(APPDIR . '/static_pages/netgiro-pay.php');
        break;
      case 'opnun':
        require_once(APPDIR . '/templates/thewait.php');
        break;
      case 'accounts':
        require_once(APPDIR . '/templates/accounts.php');
        break;
      case 'accounts2':
        $_GET['u'] = $flokkur;
        require_once(APPDIR . '/templates/accounts2.php');
        break;
      case 'accounts-landing':
        require_once(APPDIR . '/templates/accounts-landing.php');
        break;
      case 'login':
        require_once(APPDIR . '/templates/accounts-login.php');
        break;
      case 'forgot':
        require_once(APPDIR . '/templates/accounts-forgot.php');
        break;
      case 'heimsending':
        require_once(APPDIR . '/info_pages/heimsending.php');
        break;
      case 'skilafrestur':
        require_once(APPDIR . '/info_pages/skilafrestur.php');
        break;
      case 'home':
        require_once(APPDIR . '/templates/home.php');
        break;
      case 'flokkur':
        $_GET['id'] = $flokkur;
        $_GET['page'] = $page;
        require_once(APPDIR . '/templates/category.php');
        break;
      case 'vara':
        $_GET['id'] = $flokkur;
        require_once(APPDIR . '/templates/product.php');
        break;
      case 'verslun':
        $_GET['id'] = $flokkur;
        $_GET['page'] = $page;
        require_once(APPDIR . '/templates/store.php');
        break;
      case 'verslanir':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/static_pages/allar_budir.php');
        break;
      case 'fataskapurinn':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/static_pages/wishlist.php');
        break;
      case 'karlar':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/static_pages/karla_main.php');
        break;
      case 'karlar_top':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/static_pages/karla_popular.php');
        break;
      case 'konur_top':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/static_pages/konur_popular.php');
        break;
      case 'konur':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/static_pages/kvenna_main.php');
        break;
      case 'karlar_utsala':
      $_GET['page'] = $flokkur;
        require_once(APPDIR . '/static_pages/karla_utsala.php');
        break;
      case 'konur_utsala':
      $_GET['page'] = $flokkur;
        require_once(APPDIR . '/static_pages/kvenna_utsala.php');
        break;
      case 'user':
        require_once(APPDIR . '/templates/user.php');
        break;
      case 'hafdu_samband':
        require_once(APPDIR . '/static_pages/footer_samband.php');
        break;
      case 'info':
        require_once(APPDIR . '/admin/numbers.php');
        break;
      case 'karfa':
        $_GET['cmd'] = $flokkur;
        require_once(APPDIR . '/templates/cart.php');
        break;
      case 'vorulisti':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/admin/inventory_list.php');
        break;
      case 'tester-1':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/templates/create_tester.php');
        break;
      case 'tester-2':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/templates/create_tester2.php');
        break;
      case 'takk':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/templates/success.php');
        break;
      case 'tilhamingju':
        require_once(APPDIR . '/static_pages/takk.php');
        break;
      case 'login':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/templates/login.php');
        break;
      case 'closerlook':
        $_GET['page'] = $flokkur;
        require_once(APPDIR . '/functions/closerlook.php');
        break;
      case 'leit':
        $_GET['search'] = $flokkur;
        require_once(APPDIR . '/static_pages/leitarnidurstodur.php');
        break;
      default:
        require_once(APPDIR . '/templates/home.php');
        break;
    }
    exit;

    /**
     *
     * 
     */
    ?>

