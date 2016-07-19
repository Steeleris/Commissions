<?php

namespace Commissions\Config;

/**
 * Class which loads configurations within parameters given in a parameters file
 */
class Config
{
    /**
     * All of the items from the config file that is loaded
     *
     * @var array
     */
    public static $items = array();
    private static $pathToTheFile = null;

    /**
     * Setting path to the file of the parameters
     *
     * @param $path
     */
    public static function setParamsFile($path)
    {
        static::$pathToTheFile = $path;
    }

    /**
     * Loads the config file specified and sets $items to the array
     */
    public static function load()
    {
        static::$items = include(static::$pathToTheFile);
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
            return static::pathToTheValue($path, $val[$column]);
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

        static::load();

        $path = explode('.', $key);
        $column = array_shift($path);
        $val = static::$items[$column];

        return static::pathToTheValue($path, $val);
    }
}
