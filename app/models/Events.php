<?php

namespace app\models;
use core\base\Model;

class Events extends Model
{
    protected $table = 'events';


    //Create
    //Read

    public function getEvents($room)
    {
        $sql = "SELECT * FROM {$this->table} WHERE room_id = ? AND start_time > '20190701' AND start_time < '20190801'";
        return $this->pdo->query($sql, [$room]);
    }

    //Update
    //Delete
}