<?php
/**
 * Created by PhpStorm.
 * User: zavix
 * Date: 05.07.19
 * Time: 19:13
 */

namespace core\base;


class View
{
    private $format;

    public function __construct($route)
    {
        $this->format = $this->getFormat($route['params']);

    }


    public function handle($data)
    {
        if (!is_array($data)){
            http_response_code(404);
            echo "PAGE NOT FOUND";
        }
        else if($data['user'] == 'unauthorized')
        {
            http_response_code(401);
        }
        else
        {
            http_response_code(200);
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: *");

            switch ($this->format)
            {
                case TO_TEXT :
                    header('Content-type: text/plain');
                    echo $this->toText($data);
                    break;
                case TO_XML :
                    header('Content-type: application/xml');
                    echo $this->toXML($data);
                    break;
                case TO_HTML :
                    header('Content-type: text/html');
                    echo $this->toHTML($data);
                    break;
                default:
                    header('Content-Type: application/json');
                    echo $this->toJson($data);
            }
        }
    }

    private function toText($data)
    {
        $string = '';

        foreach($data as $key => $node){
            if(is_array($node)){
                foreach($node as $key => $value){
                    $string .= "{$key}: {$value}\n";
                }
            }else if(is_string($node)){
                $string .= "{$key}: {$node}\n";
            }
        }
        return $string;
    }

    private static function toJson($data)
    {
        return json_encode($data);
    }

    private function toXML($data)
    {
        $xml = new \SimpleXMLElement('<data/>');

        if (is_array($data))
        {
            foreach ($data as $data_key => $item)
            {
                if (is_array($item))
                {
                    $car = $xml->addChild('node');
                    foreach ($item as $key => $val)
                    {
                        if (is_array($val)){
                            self::toXML($val);
                        }
                        $car->addChild($key, $val);
                    }
                }
                if(is_string($item))
                {
                    $xml->addChild($data_key, $item);
                }
            }
            $result = $xml->asXML();
            return $result;
        }

    }

    private function toHTML($data)
    {
        if(is_array($data)){
            $string = "<div class='data'>\n";
            foreach($data as $key => $node){
                if (is_array($node)){
                    $string .= "<div class='node'>\n";
                    foreach($node as $key => $value){
                        $string .= "<div class='{$key}'>$value</div>\n";
                    }
                    $string .= "</div>\n";
                }else if(is_string($node)){
                    $string .= "<div class='{$key}'>$node</div>\n";
                }

            }
            $string .= "</div>\n";
            return $string;
        }
        return false;

    }
    private function getFormat($params)
    {
        if(strpos($params, TO_TEXT) !== false){
            return TO_TEXT;
        }
        if(strpos($params, TO_HTML) !== false){
            return TO_HTML;
        }
        if(strpos($params, TO_XML) !== false){
            return TO_XML;
        }
        return TO_JSON;
    }
}