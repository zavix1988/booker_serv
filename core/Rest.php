<?php

namespace core;


class Rest
{

    private static $service;
    private static $method;
    private static $route = [];



    public static function dispatch($url)
    {
        self::$method = $_SERVER['REQUEST_METHOD'];

        $url = self::removeQueryString($url);

        list(self::$route['controller'], self::$route['method'], self::$route['params']) = explode('/', $url, 3);
        $controller = 'app\controllers\\' . self::upperCamelCase(self::$route['controller'] . 'Controller');


        if (class_exists($controller)){
            self::$service = new $controller(self::$route);
            switch(self::$method)
            {
                case 'GET':
                    $result = self::callMethod('get'.ucfirst(self::$route['method']),  explode('/', self::$route['params']));
                    break;
                case 'DELETE':
                    $result = self::callMethod('delete'.ucfirst(self::$route['method']), explode('/', self::$route['params']));
                    break;
                case 'POST':
                    $params = $_POST;
                    $result = self::callMethod('post'.ucfirst(self::$route['method']), $params);
                    break;
                case 'PUT':
                    $params = [];
                    $putData = file_get_contents('php://input');
                    $inputArray = explode('&', $putData);
                    foreach($inputArray as $pair)
                    {
                        $item = explode('=', $pair);
                        if(count($item) == 2)
                        {
                            $params[urldecode($item[0])] = urldecode($item[1]);
                        }
                    }
                    $result = self::callMethod('put'.ucfirst(self::$route['method']), $params);
                    break;
                case 'OPTIONS':

                    break;
                default:
                    return false;
            }
            if($result !== 'noMethod'){
                self::$service->getView();
            }
        }else{
            http_response_code(404);
            echo "Контроллер ".self::$route['controller']." не найден";
        }
    }

    private static function callMethod($method, $param=false)
    {
        if ( method_exists(self::$service, $method) )
        {
            return call_user_func([self::$service, $method], $param);
        }
        else
        {
            http_response_code(404);
            echo "Метод $method не найден";
            return 'noMethod';
        }
    }


    /**
     * @param $name
     * @return mixed
     */
    protected static function upperCamelCase($name)
    {
        return $name = str_replace(" ", "", ucwords(str_replace("-", " ", $name)));
    }

    /**
     *
     * @param $name
     * @return string
     */
    protected static function lowerCamelCase($name)
    {
        return lcfirst(self::upperCamelCase($name));
    }

    /**
     * @param $url
     * @return string
     */
    protected static function removeQueryString($url)
    {
        if ($url) {
            $params = explode('&', $url, 2);
            if (false == strpos($params[0], '=')) {
                return rtrim($params[0], '/');
            } else {
                return '';
            }
        }
        return $url;
    }

}
