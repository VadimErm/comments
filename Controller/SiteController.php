<?php

namespace App\Controller;

use App\Models\User;

class SiteController extends BaseController
{

    /**
     * Authenticate user. If user click remember me, access token saved in cookies else it saved in session
     */
    public function loginAction()
    {

        if(isset($_POST['login']) && isset($_POST['password'])){
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);
            $model = new User();
            $user  = $model->findBy(['login' => $login]);
            if(isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
                $remember_me = true;
            }
            if($user && $user[0]->login($password, $remember_me)){
                $this->redirect('index.php?c=comment&a=index');
            } else {
                $this->_view->render('login');
            }
        } else {
            $this->_view->render('login');
        }


    }

    /**
     * Logout user
     */
    public function logoutAction()
    {
        $user = $this->_security->auth();

        if($user[0]->logout()){
            $this->redirect('index.php?c=site&a=login');
        } else {
            $this->_view->render('error', ['error' => "Something wrong!"]);
        }

    }

    /**
     * If action doesn't exist, router call this method;
     */
    public function notFoundAction()
    {
        http_response_code(404);
        $this->_view->render('404');
    }

}