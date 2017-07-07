<?php


namespace App\Engine;


use App\Controller\BaseController;
use App\Models\User;

class Security
{
    protected $_user;
    protected $_baseController;

    public function __construct()
    {
        $this->_user = new User();


    }

    public function auth($access_token = null)
    {


        if(is_null($access_token)){
            if (isset($_COOKIE['user_id']) && isset($_COOKIE['access_token']))
            {

                $user =$this->_user->findBy(['access_token' => $_COOKIE['access_token']]);


                if(is_null($user))
                {
                    setcookie('user_id', '', time() - 60*24*30*12, '/');
                    setcookie('access_token', '', time() - 60*24*30*12, '/');
                    setcookie('errors', '2', time() + 60*24*30*12, '/');
                    return false;
                }
            } else {
                return false;
            }

            return $user;
        } else {
            $user =$this->_user->findBy(['access_token' => $access_token]);

            if(is_null($user))
            {
                return false;
            } else {
                return $user;
            }
        }


    }

}