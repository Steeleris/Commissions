<?php

namespace Commissions\Config;

/**
 * Class which loads configurations within parameters given in parameters.php

 */
class Config
{
    /**
     * All of the items from the config file that is loaded
     *
     * @var array
     */
    public static $items = array();

    /**
     * Loads the config file specified and sets $items to the array
     */
    public static function load()
    {
        static::$items = include( __DIR__ . '/parameters.php');
    }

    /**
     * Getting parameter value by recursion
     *
     * @param $path
     * @param $val
     * @return mixed
     */
    private static function pathToTheValue($path, $val)
    {
        if (!is_array($val)) {
            return $val;
        }

        $column = array_shift($path);

        if (is_array($val[$column])) {
            return self::pathToTheValue($path, $val[$column]);
        }

        return $val[$column];
    }

    /**
     * Load parameters
     *
     * @param null $key
     * @return array
     */
    public static function get($key = null)
    {
        if (!$key) {
            return static::$items;
        }

        self::load();

        $path = explode('.', $key);
        $column = array_shift($path);
        $val = static::$items[$column];

        return self::pathToTheValue($path, $val);
    }
}
