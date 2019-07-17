<?php

namespace app\models;
use core\base\Model;

class Events extends Model
{
    protected $table = 'events';


    //Create
    //Read

    public function getEvents($room, $date)
    {
        $sql = "SELECT * FROM {$this->table} WHERE room_id = ? AND start_time > ? AND start_time < ?";
        return $this->pdo->query($sql, [$room, $date, $date+100]);
    }

    //Update
    //Delete
}