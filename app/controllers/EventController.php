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
        $room = $params[0];
        $result = $this->events->getEvents($room);
        foreach($result as $event){
            $user_id = $event['user_id'];
            $room_id = $event['room_id'];
            $day = substr($event['start_time'], 8, 2);
            $start_time = substr($event['start_time'], 11, 5);
            $end_time = substr($event['end_time'], 11, 5);

        }
        $this->setData([$end_time]);
    }
}
