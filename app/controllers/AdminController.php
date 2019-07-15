<?php
/**
 * Created by PhpStorm.
 * User: zavix
 * Date: 05.07.19
 * Time: 20:15
 */

namespace app\controllers;
use core\base\Controller;
use core\CommonHelper;
use app\models\Users;


class AdminController extends Controller
{
    private $users;

    public function __construct($route)
    {
        parent::__construct($route);
        $this->users = new Users();
    }

    public function getAllUsers($params){
        $login = CommonHelper::clean($params[0]);
        $token = CommonHelper::clean($params[1]);

        if($this->chechAdmin($login, $token)){
            $users = $this->users->getUsers();
            if(!empty($users)){
                $this->setData($users);
            }else{
                $this->setData(['users' => 'empty list']);
            }
        }else{
            $this->setData(['user' => 'unauthorized']);

        }
    }

    public function getUser($params)
    {
        $login = $params[0];
        $user = $params[1];
        $token = $params[2];


        if($this->chechAdmin($user, $token)){
            $result = $this->users->getUser($login);
            $result = $result[0];
            $this->setData(['first_name' => $result['first_name'], 'last_name' => $result['last_name'], 'email'=>$result['email']]);
        }else{
            $this->setData(['user' => 'unauthorized']);

        }
    }

    public function postRegister($params)
    {
        $login = CommonHelper::cleanPostString($params['login']);
        $password = CommonHelper::cleanPostString($params['password']);
        $retry = CommonHelper::cleanPostString($params['retry']);
        $email = CommonHelper::cleanPostString($params['email']);
        $first_name = CommonHelper::cleanPostString($params['first_name']);
        $last_name = CommonHelper::cleanPostString($params['last_name']);

        if (CommonHelper::check_length($password, 8, 20) && ($password == $retry) && filter_var($email, FILTER_VALIDATE_EMAIL) && CommonHelper::check_length($login, 5, 25) )
        {
            if($this->users->isUnique($login))
            {
                $password = password_hash($password, PASSWORD_DEFAULT);
                if ($this->users->create($login, $password, $first_name, $last_name, $email)){
                    $this->setData(['result'=>true]);
                }
            }else{
                $this->setData(['result'=>false, 'unique'=>false]);
            }
        }else{
            $this->setData(['result'=>false, 'validation'=>false]);
        }
    }

    public function putUpdateUser($params)
    {

        $user = CommonHelper::cleanPostString($params['user']); 
        $token = CommonHelper::cleanPostString($params['token']);

        $first_name = CommonHelper::cleanPostString($params['first_name']);
        $last_name = CommonHelper::cleanPostString($params['last_name']);
        $login = CommonHelper::cleanPostString($params['login']);
        $email = CommonHelper::cleanPostString($params['email']);
        
        $password = CommonHelper::cleanPostString($params['password']);
        $retry = CommonHelper::cleanPostString($params['retry']);

        if($this->chechAdmin($user, $token)){
            $result = $this->users->getUser($login);
            $result = $result[0];
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                if($password == ''){
                    $this->users->updateUser($result['id'], $first_name, $last_name, $email);
                    $this->setData(['result' => true]);
                }else{
                    if (CommonHelper::check_length($password, 8, 20) && ($password == $retry)) {
                        $this->users->changePassword($password, $result['id']);
                        $this->setData(['result' => true, 'password' => 'changed']);
                    }else{
                        $this->setData(['result' => false, 'password' => 'unchanged']);
                    }
                }
            }else{
                $this->setData(['result' => false, 'validation' => false]);
            }
        }else{
            $this->setData(['result'=>false, 'user'=>'unauthorized']);
        }

    }

    public function deleteUser($login)
    {
        $login = CommonHelper::cleanPostString($login);
        $result = $this->users->delete($login);
        if($result){
            $this->setData($result);
        }else{
            $this->setData(['result' => false]);
        }


    }

    private function chechAdmin($login, $token)
    {
        $result = $this->users->checkLoginedUser($login);
        if($token == $result['token']){
            if($result['role'] == 'admin'){
               return true;
            }
        }else{
           return false;
        }
    }

}