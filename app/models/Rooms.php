<?php

namespace app\models;
use core\base\Model;

class Rooms extends Model
{
    protected $table = 'rooms';


    //Create


    //Read
    
    public function allRooms(){
        $sql = "SELECT id, name FROM {$this->table}";
        return $this->pdo->query($sql);
    }

    //Update
    
    
    //Delete


}