<?php
//Конфигурация базы данных//
define('HOST', 'localhost');
define('DB_NAME', 'softgroup');
define('USER_NAME', 'root');
define('PASSWORD', '12345');
//-------------------------//

define('ROOT_URL', 'http://' . $_SERVER['HTTP_HOST'] . str_replace('\\', '', dirname(htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES))));
define('ROOT_PATH', __DIR__ . '/');
define('COMMENTS_LIMIT', 5); // количество подгружаемых комментариев
