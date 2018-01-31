<?php

function autoload($class) {
    $class_name = strtolower($class);
    $class_path = __DIR__ . '/../' . str_replace('_', '/', $class_name) . '.php';
    if (file_exists($class_path)) {
        include_once $class_path;
    } else {
        $core_class_path = __DIR__ . '/' . $class_name . '.php';
        if (file_exists($core_class_path)) {
            include_once $core_class_path;
        }
    }
}

spl_autoload_register('autoload');


