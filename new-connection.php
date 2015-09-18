<?php
/*--------------------BEGINNING OF THE CONNECTION PROCESS------------------*/
//define constants for db_host, db_user, db_pass, and db_database
//adjust the values below to match your database settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root'); //set DB_PASS as 'root' if you're using mac
define('DB_DATABASE', 'the_wall'); //make sure to set your database
//connect to database host
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
/*-------------------------END OF CONNECTION PROCESS!---------------------*/
//Make sure connection is good or die
if(mysqli_connect_errno())
{
 die("Failed to connect to MySQL: (" . mysqli_connect_errno() . ") " . mysqli_connect_error());
}
/*----BELOW ARE THE CUSTOM FUNCTIONS WE HAVE PRE-WRITTEN YOU TO USE IN QUERYING YOUR DATABASES!----*/

/**
 * Use when expecting multiple records.
 *
 * Returns an array of rows
 */
function fetch($query)
{
 global $connection;
 $result = mysqli_query($connection, $query);
 $rows = array();
 foreach($result as $row) {
  $rows[] = $row;
 } 
 return $rows;
}

/**
 * Use when doing an INSERT/DELETE/UPDATE query
 */ 
function run_mysql_query($query)
{
 global $connection;
 $result = $connection->query($query);
 //Check if query is an 'insert' query
 if(preg_match("/insert/i", $query))
 {
  return $connection->insert_id;
 }
 //return boolean (true/false) if query update or delete / 
 return $result;
}
