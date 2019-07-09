<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 06.07.2019
 * Time: 10:44
 */

namespace app\models;
use core\base\Model;


class Users extends Model
{
    protected $table = 'users';

    public function create($login, $password, $first_name, $last_name, $email)
    {
        $sql = "INSERT INTO {$this->table} (login, password_hash, first_name, last_name, email, role, discount_id) VALUES (?, ?, ?, ?, ?, 'user', 1)";
        return $this->pdo->execute($sql, [$login, $password, $first_name, $last_name, $email]);
    }

    public function find($id, $field)
    {
        $sql ="SELECT WHERE ? = ?";

    }

    public function update()
    {
        
    }

    public function delete()
    {
        
    }

    public function isUnique($login){
        $sql = "SELECT login FROM {$this->table} WHERE login = ?";
        $result = $this->pdo->query($sql, [$login]);
        if(count($result) > 0){
            return false;
        }
        return true;
    }
}