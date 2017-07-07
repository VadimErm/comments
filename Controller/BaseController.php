<?php


namespace App\Controller;


use App\Engine\Security;
use App\Engine\View;

class BaseController
{


    protected $_security;
    protected $_view;

    public function __construct()
    {
        $this->_security = new Security();
        $this->_view = new View();

    }

    public function redirect($url)
    {
        header("Location:".ROOT_URL."$url");
    }
}