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

        $result = $this->users->checkLoginedUser($login);
        //$this->setData([$result]);
        if($token == $result['token']){
            if($result['role'] == 'admin'){
                $users = $this->users->getUsers();
                if(!empty($users)){
                    $this->setData([$users]);
                }
            }else{

            }
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
                if ($this->users->create($login, $password,$first_name, $last_name, $email)){
                    $this->setData(['result'=>true]);
                }
            }else{
                $this->setData(['result'=>false, 'unique'=>false]);
            }
        }else{
            $this->setData(['result'=>false, 'validation'=>false]);
        }
    }

}