<?php
/**
 * Created by PhpStorm.
 * User: zavix
 * Date: 05.07.19
 * Time: 19:12
 */

namespace core\base;


abstract class Controller
{


    protected $route = [];

    protected $data = [];



    public function __construct($route)
    {
        $this->route = $route;
    }

    public function getView()
    {
        $vObj = new View($this->route);
        $vObj->handle($this->data);
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}