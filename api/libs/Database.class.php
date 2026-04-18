<?php

class Database
{
    static $db;
    public static function getConnection()
    {
        /*
            file env.json
            {
                "database": "apis",
                "username": "root",
                "password": "",
                "server": "localhost"
            }
        */
        $config_json = file_get_contents(__DIR__ . '/../../../env.json');
        $config = json_decode($config_json, true);

        if (Database::$db != null) {
            return Database::$db;
        } else {
            Database::$db = mysqli_connect($config['server'], $config['username'], $config['password'], $config['database']);
            if (!Database::$db) {
                die("Connection failed: " . mysqli_connect_error());
            } else {
                return Database::$db;
            }
        }
    }
}
