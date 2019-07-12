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

        if($token == $result['token']){
            if($result['role'] == 'admin'){
                $users = $this->users->getUsers();
                $this->setData([$users]);
            }else{

            }
        }else{
            $this->setData(['user' => 'unauthorized']);
        }

    }

}