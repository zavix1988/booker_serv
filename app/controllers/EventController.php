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
        $month = (strlen($month) == 2) ? (string)$month : '0'.$month;
        $year = CommonHelper::cleanPostString($params[2]);

        $date = $year.$month.'01';

        $result = $this->events->getEvents($room, $date);
        if(!empty($result)){
            foreach($result as $event){
                $newEvent['user_id'] = $event['user_id'];
                $newEvent['room_id'] = $event['room_id'];
                $newEvent['day'] = substr($event['start_time'], 8, 2);
                $newEvent['start_time'] = substr($event['start_time'], 11, 5);
                $newEvent['end_time'] = substr($event['end_time'], 11, 5);
                $newEvent['description'] = $event['description '];
                $events[] = $newEvent;
            }
            $this->setData($events);
        }else{
            $this->setData([]);
        }
    }
}
