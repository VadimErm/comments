<?php


namespace App\Engine;

class Db extends \PDO
{


    public function __construct()
    {
        $options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES UTF8';
        parent::__construct('mysql:host=' .HOST . ';dbname=' .DB_NAME . ';', USER_NAME, PASSWORD, $options);
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
