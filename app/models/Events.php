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
        $sql = "SELECT be.id, user_id, bu.login, bu.first_name, bu.last_name, room_id, note, UNIX_TIMESTAMP(start_time) as startEvent, UNIX_TIMESTAMP(end_time) as endEvent, UNIX_TIMESTAMP(create_time) as createdEvent
                        FROM {$this->table} be
                        INNER JOIN booker_users bu ON bu.id = user_id
                        WHERE room_id = ? AND MONTH(start_time) = ? AND YEAR(start_time) = ? ORDER BY UNIX_TIMESTAMP(end_time) ASC ";
        return $this->pdo->query($sql, [$room, $month, $year]);
    }

    public function getEventById($eventId){
        $sql = "SELECT be.id, user_id, bu.login, bu.first_name, bu.last_name, room_id, note, UNIX_TIMESTAMP(start_time) as startEvent, UNIX_TIMESTAMP(end_time) as endEvent, UNIX_TIMESTAMP(create_time) as createdEvent 
                  FROM {$this->table} be
                  INNER JOIN booker_users bu ON bu.id = user_id 
                  WHERE be.id = ? LIMIT 1";
        return $this->pdo->query($sql, [$eventId]);
    }

    //Update

    public function updateEvent($eventId, $roomId, $userId, $start, $end, $note)
    {

        $sql = "UPDATE {$this->table} SET user_id = ?, room_id = ?, start_time = ?, end_time = ?, note = ? WHERE id = ?";
        return $this->pdo->execute($sql, [$userId, $roomId, $start, $end, $note, $eventId]);

    }

    //Delete

    public function deleteEvent($eventId)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        if($this->pdo->execute($sql, [$eventId])){
            $sql = "DELETE FROM booker_parentroom_room WHERE room_id = ?";
            return $this->pdo->execute($sql, [$eventId]);
        }
        return false;
    }




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

    public function checkHolidays($timestamp)
    {
        $day = date('N', $timestamp);
        return !((int)$day >= 6);
    }
}