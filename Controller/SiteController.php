<?php

namespace App\Controller;

use App\Models\User;

class SiteController extends BaseController
{
    public function loginAction()
    {


        if(isset($_POST['login']) && isset($_POST['password'])){
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);
            $model = new User();
            $user  = $model->findBy(['login' => $login]);

            if($user && $user->login($password)){
                $this->redirect('index.php?c=comment&a=index');
            } else {
                $this->_view->render('login');
            }
        } else {
            $this->_view->render('login');
        }


    }

    public function logoutAction()
    {
        $user = $this->_security->auth();

        if($user->logout()){
            $this->redirect('index.php?c=site&a=login');
        } else {
            $this->_view->render('error', ['error' => "Something wrong!"]);
        }

    }

    public function notFoundAction()
    {
        http_response_code(404);
        $this->_view->render('404');
    }

}