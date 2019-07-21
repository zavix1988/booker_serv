<?php


namespace app\controllers;
use core\base\Controller;
use core\CommonHelper;
use app\models\Events;
use app\models\Users;

class EventController extends Controller
{
    private $events;

    public function __construct($route)
    {
        parent::__construct($route);
        $this->events = new Events();
        $this->users = new Users(); 
    }

    public function getRoomEvents($params)
    {
        $room = CommonHelper::cleanPostString($params[0]);
        $month = CommonHelper::cleanPostString($params[1])+1;
        $year = CommonHelper::cleanPostString($params[2]);

        $result = $this->events->getEvents($room, $month, $year);
        if ($result){
            $this->setData($result);
        }else{
            $this->setData(['result' => false]);
        }
    }

    public function postRoomEvent($params)
    {
        $user =  CommonHelper::cleanPostString($params['user']);
        $token =  CommonHelper::cleanPostString($params['token']);

        $userData = $this->users->checkLoginedUser($user);
        if($userData['token'] != $token){
            $result['errors'] = ["user" => "unauthorized"];
        }

        $roomId =  CommonHelper::cleanPostString($params['room']);
        $login =  CommonHelper::cleanPostString($params['login']);
        $note =  CommonHelper::cleanPostString($params['description']);
        $dateStart =  (int)CommonHelper::cleanPostString($params['dateStart']);
        $dateEnd =  (int)CommonHelper::cleanPostString($params['dateEnd']);

        $recurring =  CommonHelper::cleanPostString($params['recurring']);
        $duration =  (int)CommonHelper::cleanPostString($params['duration']);

        if (($dateStart >= $dateEnd) || ($dateStart < time()))
        {
            $result['errors'] = ["dates" => "error"];
        }

        $timeStart = date("G", $dateStart);
        $timeEnd = date("G", $dateEnd);

        if (($timeStart < 8 || $timeStart > 20) ||
            ($timeEnd < 8 || $timeEnd > 20))
        {
            $result['errors'] = ["hours" => "error"];
        }

        if(!$this->checkHolidays($dateStart)){
            $result['errors'] = ["day" => "holiday"];
        }

        if(!$this->events->checkTimeEvent($dateStart, $dateEnd, $roomId))
        {
            return ["status" => "err_time"];
        }


        $start = date("Y-m-d G:i:s", $dateStart);
        $end = date("Y-m-d G:i:s", $dateEnd);
        $created = date("Y-m-d G:i:s", time());

        if(!$recurring){
            if($this->events->createEvent($login, $roomId, $note, $start, $end, $created)){
                $this->setData(['result' => 'success']);
            }
        }else{
            if($this->events->createEvent($login, $roomId, $note, $start, $end, $created, $recurring, $duration)){
                $this->setData(['result' => 'success']);
            }
        }
    }

    private function checkHolidays($timestamp)
    {
        $day = date('N', $timestamp);
        return !((int)$day >= 6);
    }
}
