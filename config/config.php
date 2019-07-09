<?php
/**
 * Created by PhpStorm.
 * User: zavix
 * Date: 26.06.19
 * Time: 15:38
 */

//DB config
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_USER', 'user12');
define('DB_PASSWORD', 'user12');
define('DB_NAME', 'user12');
define('DB_DEFAULT_CHARSET', 'utf8');
define('DB_PREFIX', 'bookук_');

//DataConverter config
define('TO_JSON', '.json');
define('TO_TEXT', '.txt');
define('TO_HTML', '.html');
define('TO_XML', '.xml');

//Path config

define('ROOT', dirname(__DIR__));
define('CORE', dirname(__DIR__) . '/core/');
define('APP', dirname(__DIR__) . '/app');
define('LIBS', dirname(__DIR__) . '/libs/');
