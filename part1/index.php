<?php
/**
 * Homepage, uses Router and View classes to display content to user
 *
 * @author Vlad Cocis
 *
 */
include 'config/config.php';
$recordset = new JSONRecordSet($ini['main']['database']['dbname']);
$page = new Router($recordset);
new View($page);
