<?php

class Route {
    static function start() {
        $controller_name = 'Main';
        $action_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);
/*
        if ($routes[1]!='wp-admin'){
        if (!empty($routes[1])) {
            $controller_name = $routes[1];
        }
        
        if (!empty($routes[2])) {
            $action_name = $routes[2];
        }
        }
*/
        //print_r($controller_name);
       // print_r($action_name);
        $controller_class_name = 'Controller_' . $controller_name;
        $action_name = 'action_' . $action_name;
        
        $controller_file = strtolower($controller_name) . '.php';
        $controller_path = WP_HOA_ROOT . '/application/controller/' . $controller_file;
        if (file_exists($controller_path)) {
            include_once $controller_path;
        } else {
            Route::ErrorPage404();
        }
        
        $controller = new $controller_class_name;
        $action = $action_name;
        
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            Route::ErrorPage404();
        }
    }
    
    static function ErrorPage404() {

        /*
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');
        header('Location: ' . $host . '404');
        */
        die("Page not found");
    }
}

