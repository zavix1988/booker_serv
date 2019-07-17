<?php

namespace app\models;
use core\base\Model;

class Events extends Model
{
    protected $table = 'events';


    //Create

    public function createEvent($user, $room, $note, $dateStart, $dateEnd, $dateCreated)
    {
        $start = date("Y-m-d G:i:s", $dateStart);
        $end = date("Y-m-d G:i:s", $dateEnd);
        $created = date("Y-m-d G:i:s", $dateCreated);

        $sql = "INSERT INTO {$this->table} (user_id, room_id, create_time, start_time, end_time, note, recurrent_event_id) 
                        VALUES ?, ?, ?, ?, ?, ?, ?";
        
        return $this->pdo->execute($sql, [$user, $room, $created, $start, $end, $note, 0]);        
    }
    
    //Read

    public function getEvents($room, $month, $year)
    {
        $sql = "SELECT id, user_id, room_id, note, UNIX_TIMESTAMP(start_time) as startEvent, UNIX_TIMESTAMP(end_time) as endEvent, UNIX_TIMESTAMP(create_time) as createdEvent
                        FROM booker_events
                        WHERE room_id = ? AND MONTH(start_time) = ? AND YEAR(start_time) = ?";
        return $this->pdo->query($sql, [$room, $month, $year]);
    }

    //Update
    //Delete
}