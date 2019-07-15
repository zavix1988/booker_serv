<?php


namespace app\controllers;
use core\base\Controller;
use core\CommonHelper;
use app\models\Rooms;

class RoomController extends Controller
{
    private $rooms;

    public function __construct($route)
    {
        parent::__construct($route);
        $this->rooms = new Rooms();
    }

    public function getAllRooms(){
        $result = $this->rooms->allRooms();
        $this->setData($result);
    }
}
