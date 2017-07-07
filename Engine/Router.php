<?php


namespace App\Engine;

class Router
{
    public static function run (array $params)
    {
        $namespace = 'App\Controller\\';
        $defaultController = $namespace . 'SiteController';
        $controllerPath = ROOT_PATH . 'Controller/';
        $controller = ucfirst($params['controller']).'Controller';

        if (is_file($controllerPath . $controller . '.php'))
        {
            $controller = $namespace  . $controller;
            $controllerObj = new $controller;

            if ((new \ReflectionClass($controllerObj))->hasMethod($params['action'].'Action') && (new \ReflectionMethod($controllerObj, $params['action'].'Action'))->isPublic())
                call_user_func(array($controllerObj , $params['action'].'Action'));
            else
                call_user_func(array($defaultController, 'notFoundAction'));
        }
        else
        {
            call_user_func(array(new $defaultController, 'notFoundAction'));
        }
    }
}
