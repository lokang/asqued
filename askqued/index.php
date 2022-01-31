<?php
session_start();
if($_SERVER['SERVER_NAME'] == 'lokang') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
if(!empty($_GET)){
    foreach($_GET as $key=>$value){
        $_GET[$key] = htmlspecialchars($value);
    }
}
if(empty($_GET['url'])){
    header('Location: /home/index');
    exit();
}
if($_GET['url'] == '/'){
    $_GET['url'] = 'home/index';
}
$url = explode('/', $_GET['url']);
spl_autoload_register(function ($class) { // include physical files of the class.
    if(file_exists(__DIR__.'/controller/' . $class . '.php')){
        include __DIR__.'/controller/' . $class . '.php';
    }elseif(file_exists(__DIR__.'/model/' . $class . '.php')){
        include __DIR__.'/model/' . $class . '.php';
    }else{
        header('Location: /home/error/');
    }
});
$controller = ucwords($url[0]).'Controller';
$_controller = new $controller(); // initiates class(HomeController) using spl_autoload_register function above.
unset($url[0]);
$method = $url[1];
unset($url[1]);
if(!method_exists($_controller, $method)){
    header('Location: /home/error/');
}

call_user_func_array([$_controller, $method], array_values($url));
