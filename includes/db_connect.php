<?php
function db_connect() {
  // Define static connection variable, avoids connecting more than once 
  static $connection;

  // Check is database connection exists, if not connected
  if(!isset($connection)) {
    // Load configuration as an array from .ini file
    $config = parse_ini_file('../private/config.ini'); 
    $connection = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
  }

  // If connection was not successful, handle the error
  if($connection === false) {
    // Handle error - notify administrator, log to a file, show an error screen, etc.
    return mysqli_connect_error(); 
  }
  return $connection;
}
?> 