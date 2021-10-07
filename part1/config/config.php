<?php
/**
 * Config file
 *
 * @author Vlad Cocis
 *
 */

/**
 * Provides a message to the user in case of exception
 * 
 * @param $e - the exception
 *
 */
function exceptionHandler($e)
{
    $msg = array("status" => "500", "message" => $e->getMessage(), "file" => $e->getFile(), "line" => $e->getLine());
    $usr_msg = array("status" => "500", "message" => "Sorry! Internal server error");
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST");
    echo json_encode($usr_msg);
}
set_exception_handler('exceptionHandler');


/**
 * Error handler. Throws exceptions in case of errors
 * 
 * @param $errno - error number
 * @param $errno - error string
 * @param $errno - error line in code
 *
 */
function errorHandler($errno, $errstr, $errline)
{
    if ($errno != 2 && $errno != 8) {
        throw new Exception("Fatal Error Detected: [$errno] $errstr line: $errline", 1);
    }
}
set_error_handler('errorHandler');

/**
 * Autoloader
 * 
 * @param $className - name of class to be loaded
 *
 */
function autoloadClasses($className)
{
    $filename = "classes\\" . strtolower($className) . ".class.php";
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $filename);
    if (is_readable($filename)) {
        include_once $filename;
    } else {
        throw new exception("File not found: " . $className . " (" . $filename . ")");
    }

}
spl_autoload_register("autoloadClasses");

$ini['main'] = parse_ini_file("config.ini", true);

define('BASEPATH', $ini['main']['paths']['basepath']);
define('CSSPATH', $ini['main']['paths']['css']);
define('JWTKEY', $ini['main']['keys']['jwtkey']);
