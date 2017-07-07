<?php

namespace App;

use App\Engine\ClassLoader;
use App\Engine\Router;

define('ROOT_URL', 'http://' . $_SERVER['HTTP_HOST'] . str_replace('\\', '', dirname(htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES))));
define('ROOT_PATH', __DIR__ . '/');


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
