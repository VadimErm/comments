<?php

namespace App;

use App\Engine\ClassLoader;
use App\Engine\Router;

include 'config.php';


try
{
    require ROOT_PATH . 'Engine/ClassLoader.php';
    ClassLoader::getInstance()->init();
    $params = ['controller' => (!empty($_GET['c']) ? $_GET['c'] : 'SiteController'), 'action' => (!empty($_GET['a']) ? $_GET['a'] : 'index')];
    Router::run($params);
}
catch (\Exception $exception)
{
    echo $exception->getMessage();
}

?> 
