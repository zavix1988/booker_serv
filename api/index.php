<?php
/**
 * Created by PhpStorm.
 * User: zavix
 * Date: 05.07.19
 * Time: 19:13
 */

//var_dump(password_hash('admin', PASSWORD_DEFAULT));die;

use core\Rest;

$query = rtrim(substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'api')+4),'/');

require '../config/config.php';

spl_autoload_register(function($class){
    $file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

Rest::dispatch($query);