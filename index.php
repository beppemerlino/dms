<?php
define('DIR', "dms");
define('VERSION', "0.9.012-Alpha");

require_once('route.php');

$array_menu = array(

    // HOMEPAGE
    array('pagina' => '', 'gruppi' => array('SuperAdmins', 'Admins', 'SuperUsers', 'Users')),

    // UTENTI
    array('pagina' => 'gestisci_utenti', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'gestisci_teams', 'gruppi' => array('SuperAdmins', 'Admins')),

    // COMMESSE
    array('pagina' => 'gestisci_commesse', 'gruppi' => array('SuperAdmins', 'Admins', 'SuperUsers', 'Users')),
    array('pagina' => 'crea_commessa', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'rendicontazione', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'report', 'gruppi' => array('SuperAdmins', 'Admins')),

    // HARDWARE
    array('pagina' => 'gestione_computer', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'gestione_monitor', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'gestione_workstation', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'gestione_printer', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'gestione_nas', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'gestione_device', 'gruppi' => array('SuperAdmins', 'Admins')),

    /*array('pagina' => 'crea_pc', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'cancella_pc', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'crea_workstation', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'cancella_workstation', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'crea_printer', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'cancella_printer', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'crea_monitor', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'cancella_monitor', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'crea_nas', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'cancella_nas', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'crea_device', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'cancella_device', 'gruppi' => array('SuperAdmins', 'Admins')),
    array('pagina' => 'inventario', 'gruppi' => array('SuperAdmins', 'Admins')),*/

    // SOFTWARE
    array('pagina' => 'gestione_software', 'gruppi' => array('SuperAdmins', 'Admins')),

);

Route::addVar($array_menu);

Route::add('/'.DIR,function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "";
    require './views/router.php';
});

Route::add('/'.DIR.'/gestisci_utenti',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "gestisci_utenti";
    require './views/router.php';
});

Route::add('/'.DIR.'/gestisci_teams',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "gestisci_teams";
    require './views/router.php';
});

Route::add('/'.DIR.'/gestisci_commesse',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "gestisci_commesse";
    require './views/router.php';
});

Route::add('/'.DIR.'/crea_commessa',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "crea_commessa";
    require './views/router.php';
});

Route::add('/'.DIR.'/rendicontazione',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "rendicontazione";
    require './views/router.php';
});

Route::add('/'.DIR.'/report',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "report";
    require './views/router.php';
});



Route::add('/'.DIR.'/gestione_computer',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "gestione_computer";
    require './views/router.php';
});

Route::add('/'.DIR.'/gestione_workstation',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "gestione_workstation";
    require './views/router.php';
});

Route::add('/'.DIR.'/gestione_nas',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "gestione_nas";
    require './views/router.php';
});

Route::add('/'.DIR.'/gestione_device',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "gestione_device";
    require './views/router.php';
});

Route::add('/'.DIR.'/gestione_software',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "gestione_software";
    require './views/router.php';
});













/*******************************************/



Route::add('/'.DIR.'/crea_pc',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "crea_pc";
    require './views/router.php';
});

Route::add('/'.DIR.'/cancella_pc',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "cancella_pc";
    require './views/router.php';
});

Route::add('/'.DIR.'/crea_workstation',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "crea_workstation";
    require './views/router.php';
});

Route::add('/'.DIR.'/cancella_workstation',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "cancella_workstation";
    require './views/router.php';
});


Route::add('/'.DIR.'/crea_monitor',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "crea_monitor";
    require './views/router.php';
});

Route::add('/'.DIR.'/cancella_monitor',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "cancella_monitor";
    require './views/router.php';
});

Route::add('/'.DIR.'/crea_printer',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "crea_printer";
    require './views/router.php';
});

Route::add('/'.DIR.'/cancella_printer',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "cancella_printer";
    require './views/router.php';
});

Route::add('/'.DIR.'/crea_nas',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "crea_nas";
    require './views/router.php';
});

Route::add('/'.DIR.'/cancella_nas',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "cancella_nas";
    require './views/router.php';
});

Route::add('/'.DIR.'/crea_device',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "crea_device";
    require './views/router.php';
});

Route::add('/'.DIR.'/cancella_device',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "cancella_device";
    require './views/router.php';
});

Route::add('/'.DIR.'/inventario',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "inventario";
    require './views/router.php';
});

Route::add('/'.DIR.'/crea_software',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "crea_software";
    require './views/router.php';
});

Route::add('/'.DIR.'/cancella_software',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "cancella_software";
    require './views/router.php';
});

Route::add('/'.DIR.'/software',function(){
    $array_menupoint = Route::$array_menu;
    $menupoint = "software";
    require './views/router.php';
});

Route::add('/'.DIR.'/login',function(){
    require './login-page.php';
});

Route::add('/'.DIR.'/index.html',function(){
    require './login-page.php';
});

Route::add('/'.DIR.'/([a-zA-Z0-9]*)',function(){
    require './views/404.php';
});


Route::run('/');