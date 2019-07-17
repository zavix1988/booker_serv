<?php


namespace app\controllers;
use core\base\Controller;
use core\CommonHelper;
use app\models\Events;

class EventController extends Controller
{
    private $events;

    public function __construct($route)
    {
        parent::__construct($route);
        $this->events = new Events();
    }

    public function getRoomEvents($params)
    {
        $room = CommonHelper::cleanPostString($params[0]);
        $month = CommonHelper::cleanPostString($params[1])+1;
        $year = CommonHelper::cleanPostString($params[2]);

        $result = $this->events->getEvents($room, $month, $year);

        $this->setData($result);
    }

    public function postRoomEvent($params)
    {

    }
}
