<?php
// $a = $_SERVER['HTTP_REFERER'];

// if (strpos($a, '/e-has/') !== false) {
    
// } else {
//     header("Location: ./");
// }

?>
<?php
// include 'index.php';
include './include/db_conn.php';
$key          = rtrim($_POST['login_key']);
$pass         = rtrim($_POST['pwfield']);
$user_id_auth = rtrim($_POST['login_id']);
$passconfirm= rtrim($_POST['confirmfield']);
if($pass==$passconfirm){
if (isset($user_id_auth) && isset($pass) && isset($key)) {
    $user_id_auth = pg_escape_string($con, $user_id_auth);
    $key = pg_escape_string($con, $key);
    $pass = pg_escape_string($con, $pass);
    $sql    = "SELECT * FROM admin WHERE username='$user_id_auth' and securekey='$key'";
    $result = pg_query($con, $sql);
    $count  = pg_num_rows($result);
    if ($count == 1) {
        pg_query($con, "UPDATE admin SET pass_key='$pass' WHERE username='$user_id_auth'");
        echo "<html><head><script>alert('Password Updated ,Login Again ');</script></head></html>";
        echo "<meta http-equiv='refresh' content='0; url=login.php'>";
    } else {
        echo "<html><head><script>alert('Change Unsuccessful');</script></head></html>";
        echo "<meta http-equiv='refresh' content='0; url=login.php'>";
    }
} else {
    echo "<html><head><script>alert('Change Unsuccessful');</script></head></html>";
    echo "<meta http-equiv='refresh' content='0; url=login.php'>";
}
}
else{
    echo "<html><head><script>alert('Confirm Password Mismatch');</script></head></html>";
    echo "<meta http-equiv='refresh' content='0; url=forgot_password.php'>";
}
?>
<center>
<img src="loading.gif">
</center>
