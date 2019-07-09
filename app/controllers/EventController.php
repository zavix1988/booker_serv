<?php


namespace app\controllers;
use core\base\Controller;
use core\CommonHelper;
use app\models\Events;

class ClassName extends Controller
{
    private $events;

    public function __construct($route)
    {
        parent::__construct($route);
        $this->events = new Events();
    }
}
