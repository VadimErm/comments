<?php


namespace App\Engine;

class Db extends \PDO
{
    CONST HOST = 'localhost';
    CONST DB_NAME = 'softgroup';
    CONST USER_NAME = 'root';
    CONST PASSWORD = '12345';

    public function __construct()
    {
        $options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES UTF8';
        parent::__construct('mysql:host=' . self::HOST . ';dbname=' . self::DB_NAME . ';', self::USER_NAME, self::PASSWORD, $options);
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
