<?php
/**
 * Created by PhpStorm.
 * User: zavix
 * Date: 05.07.19
 * Time: 20:16
 */

namespace app\controllers;
use core\base\Controller;
use core\CommonHelper;
use app\models\Users;

class UserController extends Controller
{
    private $users;

    public function __construct($route)
    {
        parent::__construct($route);
        $this->users = new Users();
    }

    public function postSignUp($params)
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

    public function putLogIn($params)
    {
        $login = CommonHelper::cleanPostString($params['login']);
        $password = CommonHelper::cleanPostString($params['password']);
        $result = $this->users->findUser($login);
        if(!empty($result)){
            $password_hash = $result[0]['password_hash'];
            if (password_verify($password, $password_hash)){
                $token = md5($result[0]['login'].microtime());
                if($this->users->logIn($token, $result[0]['id'])){
                    $this->setData(['token' => $token]);
                }else{
                    $this->setData(['token' => false]);
                }
            }else{
                $this->setData(['token' => false]);
            }
        }else{
            $this->setData(['token' => false]);
        }
    }

    public function putLogOut($params)
    {
        $token = cleanPostString($params['token']);

    }
}