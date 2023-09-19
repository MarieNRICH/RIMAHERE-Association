<?php
require __DIR__.'/../vendor/autoload.php';
session_start();

const AVAIABLE_ROUTES = [
    'home'=>[
        'action' => 'render',
        'controller' => 'MainController'
    ],
    'about'=>[
        'action' => 'render',
        'controller' => 'MainController'
    ],
    'activities'=>[
        'action' => 'renderActivity',
        'controller' => 'ActivityController'
    ],
    'profile'=>[
        'action' => 'renderUser',
        'controller' => 'UserController'
    ],
    'contact'=>[
        'action' => 'renderContact',
        'controller' => 'ContactController'
    ],
    'login'=>[
        'action' => 'renderUser',
        'controller' => 'UserController'
    ],
    'logout'=>[
        'action' => 'renderUser',
        'controller' => 'UserController'
    ],
    'register'=>[
        'action' => 'renderUser',
        'controller' => 'UserController'
    ],
    'admin'=>[
        'action' => 'renderAdmin',
        'controller' => 'AdminController'
    ],
    '403'=>[
        'action' => 'render',
        'controller' => 'ErrorController'
    ],
    '404'=>[
        'action' => 'render',
        'controller' => 'ErrorController'
    ],
    'adminMessage'=>[
        'action' => 'renderAdmin',
        'controller' => 'AdminController'
    ],
    'add'=>[
        'action' => 'renderAdmin',
        'controller' => 'AdminController'
    ],
    'update'=>[
        'action' => 'renderAdmin',
        'controller' => 'AdminController'
    ],
];


$page = 'home';
$controller;
$itemId=null; 

if(isset($_GET['page']) && !empty($_GET['page'])){        
    $page = $_GET['page'];
    if(!empty($_GET['subpage'])){
        $itemId = $_GET['subpage'];
    }
}else{
    $page = 'home';
}

// si la page dde fait partie de notre tableau de routes, on la stock ds la var controller
// sinon on redirige vers le ctl ErrorController:
if(array_key_exists($page,AVAIABLE_ROUTES)){
    
    // On stock dans la variable, le controller de la page demandée
    $controller = AVAIABLE_ROUTES[$page]['controller'];  
    // on stock ds la variable, la méthode (l'action) de la page demandée
    $controllerAction = AVAIABLE_ROUTES[$page]['action'];  //le dispatcheur
}else{
    $controller = 'ErrorController';
}

$nameSpace = "App\Controllers\\";
$ControllerClassName = $nameSpace.$controller;

// Instanciation de la classe en utilisant le nom complet (namespace + nom de la classe)
$pageController = new $ControllerClassName(); // l'ordre des appels sont importantes, le setter puis le render!
$pageController->setView($page);          
$pageController->setId($itemId);
$pageController->$controllerAction();





?>
