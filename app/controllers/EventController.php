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

    public function getRoomEvent($params)
    {
        $eventId = CommonHelper::cleanPostString($params[0]);
        $result = $this->events->getEventById($eventId);
        if($result){
            $this->setData($result[0]);
        }
    }

    public function postRoomEvent($params)
    {
        $login =  CommonHelper::cleanPostString($params['login']);
        $token =  CommonHelper::cleanPostString($params['token']);

        $userData = $this->users->checkLoginedUser($login);
        if($userData['token'] != $token){
            $result['errors'] = ["login" => "unauthorized"];
        }

        $roomId =  CommonHelper::cleanPostString($params['room']);
        $user =  CommonHelper::cleanPostString($params['user']);
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

        if(!$this->events->checkHolidays($dateStart)){
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
            if($this->events->createEvent($user, $roomId, $note, $start, $end, $created)){
                $this->setData(['result' => 'success']);
            }
        }else{
            if($this->events->createEvent($user, $roomId, $note, $start, $end, $created, $recurring, $duration)){
                $this->setData(['result' => 'success']);
            }
        }
    }
    public function putRoomEvent($params)
    {
        $login =  CommonHelper::cleanPostString($params['login']);
        $token =  CommonHelper::cleanPostString($params['token']);

        $userData = $this->users->checkLoginedUser($login);
        if($userData['token'] != $token){
            $result['errors'] = ["login" => "unauthorized"];
        }

        $eventId =  CommonHelper::cleanPostString($params['event']);
        $roomId =  CommonHelper::cleanPostString($params['room']);
        $userId =  CommonHelper::cleanPostString($params['userId']);
        $note =  CommonHelper::cleanPostString($params['description']);
        $dateStart =  (int)CommonHelper::cleanPostString($params['dateStart']);
        $dateEnd =  (int)CommonHelper::cleanPostString($params['dateEnd']);


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

        if(!$this->events->checkTimeEvent($dateStart, $dateEnd, $roomId))
        {
            return ["status" => "err_time"];
        }


        $start = date("Y-m-d G:i:s", $dateStart);
        $end = date("Y-m-d G:i:s", $dateEnd);

        if($this->events->updateEvent($eventId, $roomId, $userId, $start, $end, $note)){
            $this->setData(['result' => 'success']);
        }
    }

    public function deleteRoomEvent($params)
    {
        $eventId = CommonHelper::cleanPostString($params[0]);
        $user = CommonHelper::cleanPostString($params[1]);
        $token = CommonHelper::cleanPostString($params[2]);
        $delRecurrences = CommonHelper::cleanPostString($params[3]);

        $userData = $this->users->checkLoginedUser($user);
        if($userData['token'] != $token){
            $result['errors'] = ["login" => "unauthorized"];
            $this->setData($result);
        }else{
            $result = $this->events->getEventById($eventId);
            if($result[0] && (count($result[0]) > 0)){
                if($result[0]['login'] == $user || $user == 'admin'){
                    if ($delRecurrences == 'all'){
                        $this->events->deleteEvent($eventId, true);
                        $this->setData(['result'=>'success']);
                    }else{
                        $this->events->deleteEvent($eventId);
                        $this->setData(['result'=>'success']);
                    }
                }else{
                    $this->setData(['result'=> false, 'error' => 'permission_denied']);
                }
            }
        }
    }

}
