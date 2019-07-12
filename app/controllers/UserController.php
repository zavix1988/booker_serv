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
                    $this->setData(['login' => $login,'token' => $token, 'role'=> $result[0]['role']]);
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