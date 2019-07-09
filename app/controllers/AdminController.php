<?php
/**
 * Created by PhpStorm.
 * User: zavix
 * Date: 05.07.19
 * Time: 20:15
 */

namespace app\controllers;
use core\base\Controller;


class AdminController extends Controller
{
    public function __construct($route)
    {
        parent::__construct($route);
    }

    public function getLogin($params){
        $this->setData(['sdfsd'=> 'dfghdf']);
    }

}