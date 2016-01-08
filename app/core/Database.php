<?php

/**
 * Handling database connection
 *
 */
class Database
{
    private static $connection = NULL;
    /*private static $connectionWc = NULL;*/

    /**
     * private constructor
     */
    private function __construct()
    {
    }

    /**
     * Establishing database connection
     * @return database connection handler
     */
    public static function getInstance()
    {
        $dbData = Config::load('database');

        if (self::$connection === NULL) {
            try {
                self::$connection = new PDO("mysql:host=" . $dbData['DB_HOST'] . ";dbname=" . $dbData['DB_NAME'] . ";charset=utf8", $dbData['DB_USERNAME'], $dbData['DB_PASSWORD']);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (PDOException $e) {
                echo("Failed to connect to MySQL: " . $e->getMessage());
            }
        }

        return self::$connection;
    }

    /**
     * Establishing database connection
     * @return database connection handler
     */
   /* public static function getWCInstance()
    {
        $dbData = Config::load('database');

        if (self::$connectionWc === NULL) {
            try {
                self::$connectionWc = new PDO("mysql:host=" . $dbData['DB_HOST'] . ";dbname=bandcloud__api_webcrawler;charset=utf8", "bc_api_crawler", "mTC~W<@cr[Ya';b5gz.9sYERRNp3FGC6");
                self::$connectionWc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connectionWc->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (PDOException $e) {
                echo("Failed to connect to MySQL: " . $e->getMessage());
            }
        }

        return self::$connectionWc;
    }*/
}