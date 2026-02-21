<?php 
require_once __DIR__ . '/check_license.php';

// PostgreSQL connection parameters
$host     = "localhost";
$port     = "5432";
$dbname   = "sports_club_db";
$username = "postgres";
$password = "1234";

// Connect to PostgreSQL database
$link = pg_connect("host=$host port=$port dbname=$dbname user=$username password=$password");

if (!$link) {
    die("Can't Connect to PostgreSQL: " . pg_last_error());
}
?>