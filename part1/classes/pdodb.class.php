<?php
/**
 * PDO (PHP Data Objects) Database Connection
 * Create a singleton SQLite database connection
 * 
 * @author Vlad Cocis
 * 
 */
class PDOdb
{
    private static $dbConnection = null;

    /**
     * Private to prevent normal class instantiation
     * @access private
     */
    private function __construct()
    {
    }

    /**
     * Return DB connection or create initial connection
     * @return object (PDO)
     * 
     */
    public static function getConnection($dbname)
    {
        if (!self::$dbConnection) {
            self::$dbConnection = new PDO("sqlite:" . $dbname);
            self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$dbConnection;
    }
}
