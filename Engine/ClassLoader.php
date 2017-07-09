<?php


namespace App\Engine;


class ClassLoader
{

    protected static $_instance = null;

    /**
     * Register the loader method
     */
    public function init()
    {

        spl_autoload_register(array(__CLASS__, '_loadClasses'));
    }

    /**
     * Return an instance of this class
     * @return null|static
     */
    public static function getInstance()
    {
        return (null === static::$_instance) ? static::$_instance = new static : static::$_instance;
    }

    /**
     * Loader method
     * @param $class
     */
    private function _loadClasses($class)
    {

        $class = str_replace(array(__NAMESPACE__, 'App', '\\'), '/', $class);

        if (is_file(__DIR__ . '/' . $class . '.php'))
            require_once __DIR__ . '/' . $class . '.php';

        if (is_file(ROOT_PATH . $class . '.php'))
            require_once ROOT_PATH . $class . '.php';
    }
}