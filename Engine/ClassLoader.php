<?php


namespace App\Engine;


class ClassLoader
{

    protected static $_instance = null;

    public function init()
    {
        // Register the loader method
        spl_autoload_register(array(__CLASS__, '_loadClasses'));
    }

    public static function getInstance()
    {
        return (null === static::$_instance) ? static::$_instance = new static : static::$_instance;
    }

    private function _loadClasses($class)
    {
        // Remove namespace and backslash
        $class = str_replace(array(__NAMESPACE__, 'App', '\\'), '/', $class);

        if (is_file(__DIR__ . '/' . $class . '.php'))
            require_once __DIR__ . '/' . $class . '.php';

        if (is_file(ROOT_PATH . $class . '.php'))
            require_once ROOT_PATH . $class . '.php';
    }
}