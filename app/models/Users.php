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



    //Create
    
    public function create($login, $password, $first_name, $last_name, $email)
    {
        $sql = "INSERT INTO {$this->table} (login, password_hash, first_name, last_name, email, role, token, is_active) VALUES (?, ?, ?, ?, ?, 'user', '', 1)";
        return $this->pdo->execute($sql, [$login, $password, $first_name, $last_name, $email]);
    }


    //Read


    public function getUsers()
    {
        $sql = "SELECT first_name, last_name, email, login FROM {$this->table} WHERE role != 'admin' AND is_active != 0";
        return $this->pdo->query($sql);
    }

    public function getUser($login)
    {
        $sql = "SELECT id, login, first_name, last_name, email, password_hash, role FROM {$this->table} WHERE login = ?";
        return $this->pdo->query($sql, [$login]);
    }
    
    public function checkLoginedUser($login)
    {
        $sql = "SELECT token, role FROM {$this->table} WHERE login = ?";
        $result = $this->pdo->query($sql, [$login]);
        if($result && !empty($result)){
            return $result[0];
        }else{
            return false;
        }
    }

    public function isUnique($login){
        $sql = "SELECT login FROM {$this->table} WHERE login = ?";
        $result = $this->pdo->query($sql, [$login]);
        if(count($result) > 0){
            return false;
        }
        return true;
    }

    //Update

    public function changeToken($token, $id)
    {
        $sql = "UPDATE {$this->table} SET token = ? WHERE id = ?";
        return $this->pdo->execute($sql, [$token, $id]);
    }

    public function changePassword($password, $id)
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE {$this->table} SET password_hash = ? WHERE id = ?";
        return $this->pdo->execute($sql, [$password_hash, $id]);
    }

    public function updateUser($id, $first_name, $last_name, $email)
    {
        $sql = "UPDATE {$this->table} SET first_name = ?, last_name = ?, email = ? WHERE id = ?" ;
        return $this->pdo->execute($sql, [$first_name, $last_name, $email, $id]);
    }

    //Delete

    public function delete($login)
    {
        $sql = "UPDATE {$this->table} SET is_active = 0 WHERE login = ?";
        $result = $this->pdo->execute($sql, [$login]);
        $rowsCount = $this->pdo->getRowsCount();
        return compact('result', 'rowsCount');
    }
}