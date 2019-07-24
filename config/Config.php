<?php

/**
 * 
 */
class Config
{
	
	private static $directory;
    private static $config = [];

    public static function setDirectory($path) 
    {
        self::$directory = $path;
    }

    public static function get($section)
    {
        $section = strtolower($section);

        self::$config[$section] = require self::$directory . '/' . $section . '.php';

        return self::$config[$section];
    }
}