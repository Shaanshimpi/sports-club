<?php
require_once __DIR__ . '/check_license.php';

include './include/db_conn.php';

$user_id_auth = ltrim($_POST['user_id_auth']);
$user_id_auth = rtrim($user_id_auth);

$pass_key = ltrim($_POST['pass_key']);
$pass_key = rtrim($_POST['pass_key']);

$user_id_auth = stripslashes($user_id_auth);
$pass_key     = stripslashes($pass_key);



if($pass_key=="" &&  $user_id_auth==""){
   echo "<head><script>alert('Username and Password can be empty');</script></head></html>";
               echo "<meta http-equiv='refresh' content='0; url=index.php'>";
  
}
else if($pass_key=="" ){
   echo "<head><script>alert('Password can be empty');</script></head></html>";
               echo "<meta http-equiv='refresh' content='0; url=index.php'>";
  
}
else if($user_id_auth=="" ){
   echo "<head><script>alert('Username can be empty');</script></head></html>";
               echo "<meta http-equiv='refresh' content='0; url=index.php'>";
  
}

else{

// Escape strings for PostgreSQL
$user_id_auth = pg_escape_string($con, $user_id_auth);
$pass_key     = pg_escape_string($con, $pass_key);

// 1) Try admin login using admin username
$sql          = "SELECT * FROM admin WHERE username='$user_id_auth' and pass_key='$pass_key'";
$result       = pg_query($con, $sql);
if ($result && pg_num_rows($result) == 1) {
    $row = pg_fetch_assoc($result);
    session_start();
    $_SESSION['user_data']  = $user_id_auth;
    $_SESSION['logged']     = "start";
    $_SESSION['full_name']  = $user_id_auth;
    $_SESSION['username']   = $row['Full_name'];
    header("location: ./dashboard/admin/");
    exit;
} else {
    // 2) Try member login using member ID or email + password
    $sql_member = "SELECT m.userid, u.username
                   FROM member_login m
                   INNER JOIN users u ON m.userid=u.userid
                   WHERE (m.userid='$user_id_auth' OR u.email='$user_id_auth')
                   AND m.pass_key='$pass_key'";
    $result_member = pg_query($con, $sql_member);
    if ($result_member && pg_num_rows($result_member) == 1) {
        $row_member = pg_fetch_assoc($result_member);
        session_start();
        $_SESSION['member_userid'] = $row_member['userid'];
        $_SESSION['member_logged'] = "start";
        $_SESSION['member_name']   = $row_member['username'];
        header("location: ./dashboard/member/");
        exit;
    } else {
        include 'index.php';
        echo "<html><head><script>alert('Username OR Password is Invalid');</script></head></html>";
    }
}
}
?>
