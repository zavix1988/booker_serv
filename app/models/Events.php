<?php

namespace app\models;
use core\base\Model;

class Events extends Model
{
    protected $table = 'events';


    //Create

    public function createEvent($userId, $roomId, $note, $dateStart, $dateEnd, $dateCreated)
    {
        
    }
    
    //Read

    public function getEvents($room, $month, $year)
    {
        $sql = "SELECT id, user_id, room_id, note, UNIX_TIMESTAMP(start_time) as startEvent, UNIX_TIMESTAMP(end_time) as endEvent, UNIX_TIMESTAMP(create_time) as createdEvent
                        FROM booker_events
                        WHERE boardroom_id = ? AND MONTH(start_time) = ? AND YEAR(start_time) = ?";
        return $this->pdo->query($sql, [$room, $month, $year]);
    }

    //Update
    //Delete
}