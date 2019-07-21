<?php

namespace app\models;
use core\base\Model;

class Events extends Model
{
    protected $table = 'events';


    //Create

    public function createEvent($user, $room, $note, $start, $end, $created, $recurring=false, $duration=false)
    {
        $sql = "SELECT id FROM ".DB_PREFIX."users WHERE login = ?";
        $result = $this->pdo->query($sql, [$user]);


        if(!empty($result)){
            $userId = $result[0]['id'];
        }else{
            return false;
        }

        $sql = "INSERT INTO {$this->table} (user_id, room_id, create_time, start_time, end_time, note) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        $result = $this->pdo->execute($sql, [$userId, $room, $created, $start, $end, $note]);

        if($recurring){
            $parentId = $this->pdo->lastInsertId();

            if($recurring == "weekly" and $result)
            {
                for($i = 1; $i <= $duration; $i++)
                {
                    $repeatStartTime = strtotime("$start + $i week");
                    $repeatEndTime = strtotime("$end + $i week");
                    $repeatStartDate = date("Y-m-d G:i:s", $repeatStartTime);
                    $repeatEndDate = date("Y-m-d G:i:s", $repeatEndTime);
                    if($this->checkTimeEvent($repeatStartTime, $repeatEndTime, $room)) {
                        $sql = "INSERT INTO {$this->table} (user_id, room_id, create_time, start_time, end_time, note) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                        if($this->pdo->execute($sql, [$userId, $room, $created, $repeatStartDate, $repeatEndDate, $note])){
                            $eventId = $this->pdo->lastInsertId();
                            $sql = "INSERT INTO ".DB_PREFIX."parentroom_room (parent_room_id, room_id) VALUES (?, ?)";
                            $this->pdo->execute($sql, [$parentId, $eventId]);
                        }
                    } else {
                        return false;
                    }
                }
            }

            if($recurring == "bi-weekly" and $result)
            {
                for($i = 1; $i <= $duration; $i++)
                {
                    $repeatStartTime = strtotime("$start + " . ($i * 2) . " week");
                    $repeatEndTime = strtotime("$end + " . ($i * 2) . " week");
                    $repeatStartDate = date("Y-m-d G:i:s", $repeatStartTime);
                    $repeatEndDate = date("Y-m-d G:i:s", $repeatEndTime);
                    if($this->checkTimeEvent($repeatStartTime, $repeatEndTime, $room)){
                        $sql = "INSERT INTO {$this->table} (user_id, room_id, create_time, start_time, end_time, note) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                        if($this->pdo->execute($sql, [$userId, $room, $created, $repeatStartDate, $repeatEndDate, $note])){
                            $eventId = $this->pdo->lastInsertId();
                            $sql = "INSERT INTO ".DB_PREFIX."parentroom_room (parent_room_id, room_id) VALUES (?, ?)";
                            $this->pdo->execute($sql, [$parentId, $eventId]);
                        }
                    } else {
                        return false;
                    }
                }
            }

            if($recurring == "monthly" and $result)
            {
                $repeatStartTime = strtotime("$start + 1 month");
                while(!$this->checkHolidays($repeatStartTime))
                {
                    $repeatStartTime = strtotime(" + 1 day", $repeatStartTime);
                }
                $repeatStartDate = date("Y-m-d G:i:s", $repeatStartTime);

                $repeatEndTime = strtotime("$end + 1 month");

                while(!$this->checkHolidays($repeatEndTime))
                {
                    $repeatEndTime = strtotime(" + 1 day", $repeatEndTime);
                }
                $repeatEndDate = date("Y-m-d G:i:s", $repeatEndTime);

                if($this->checkTimeEvent($repeatStartTime, $repeatEndTime, $room))
                {
                    $sql = "INSERT INTO {$this->table} (user_id, room_id, create_time, start_time, end_time, note) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                    if($this->pdo->execute($sql, [$userId, $room, $created, $repeatStartDate, $repeatEndDate, $note])){
                        $eventId = $this->pdo->lastInsertId();
                        $sql = "INSERT INTO ".DB_PREFIX."parentroom_room (parent_room_id, room_id) VALUES (?, ?)";
                        $this->pdo->execute($sql, [$parentId, $eventId]);
                    }
                } else {
                    return false;
                }
            }
        }

    }
    
    //Read

    public function getEvents($room, $month, $year)
    {
        $sql = "SELECT id, user_id, room_id, note, UNIX_TIMESTAMP(start_time) as startEvent, UNIX_TIMESTAMP(end_time) as endEvent, UNIX_TIMESTAMP(create_time) as createdEvent
                        FROM booker_events
                        WHERE room_id = ? AND MONTH(start_time) = ? AND YEAR(start_time) = ? ORDER BY UNIX_TIMESTAMP(end_time) ASC ";
        return $this->pdo->query($sql, [$room, $month, $year]);
    }

    public function getOneEvent($id)
    {

    }

    //Update
    //Delete

/*    public function deleteEventById($eventId, $allEvents=false)
    {
        if ($allEvents)
        {
            $result = $this->sql->delete("b_bookings", "id='$eventId'")
                ->l_or("booking_id='$eventId'")
                ->doQuery();
        } else {
            $result = $this->sql->delete("b_bookings", "id='$eventId'")
                ->doQuery();
        }
        if ($result)
        {
            return ["status" => "success"];
        }
        return ["status" => "error"];
    }*/


    public function checkTimeEvent($timestampStart, $timestampEnd, $room, $id = false)
    {
        $day = date('Y-m-d', $timestampStart);
        if($id == false)
        {
            $sql = "SELECT id, UNIX_TIMESTAMP(start_time) as start, UNIX_TIMESTAMP(end_time) as end FROM {$this->table} 
                        WHERE DATE(start_time) = ? AND room_id = ?";
            $result = $this->pdo->query($sql, [$day, $room]);
        } else {
            $sql = "SELECT id, UNIX_TIMESTAMP(start_time) as start, UNIX_TIMESTAMP(end_time) as end FROM {$this->table} 
                        WHERE DATE(start_time) = ? AND room_id = ? AND id != ?";
            $result = $this->pdo->query($sql, [$day, $room, $id]);
        }
        if (count($result) > 0 and is_array($result))
        {
            foreach($result as $date)
            {
                if(!((int)$date['start'] < (int)$timestampStart &&
                    (int)$date['end'] <= (int)$timestampStart ||
                    (int)$date['start'] >= (int)$timestampEnd &&
                    (int)$date['end'] > (int)$timestampEnd))
                {
                    return false;
                }
            }
        }
        return true;
    }

}