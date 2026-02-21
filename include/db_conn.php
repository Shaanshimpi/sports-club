<?php
require_once __DIR__ . '/../check_license.php';

// PostgreSQL connection parameters
$host     = "localhost";
$port     = "5432";
$dbname   = "sports_club_db";
$username = "postgres";
$password = "1234";

// Connect to PostgreSQL database
$con = pg_connect("host=$host port=$port dbname=$dbname user=$username password=$password");

// Check connection
if (!$con) {
    die("Failed to connect to PostgreSQL: " . pg_last_error());
}
?>
<?php
function page_protect()
{
    session_start();
    
    global $db;
    
    /* Secure against Session Hijacking by checking user agent */
    if (isset($_SESSION['HTTP_USER_AGENT'])) {
        if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) {
            session_destroy();
            echo "<meta http-equiv='refresh' content='0; url=../login/'>";
            exit();
        }
    }
    
    // before we allow sessions, we need to check authentication key - ckey and ctime stored in database
    
    /* If session not set, check for cookies set by Remember me */
    if (!isset($_SESSION['user_data']) && !isset($_SESSION['logged']) && !isset($_SESSION['auth_level'])) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0; url=../login/'>";
        exit();
    } else {
        
    }
    
}
?>