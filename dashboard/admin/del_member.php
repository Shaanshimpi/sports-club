<?php
require '../../include/db_conn.php';
page_protect();

$msgid = pg_escape_string($con, $_POST['name']);
if (strlen($msgid) > 0) {
    $result = pg_query($con, "DELETE FROM users WHERE userid='$msgid'");
    if($result){
        echo "<html><head><script>alert('Member Deleted');</script></head></html>";
        echo "<meta http-equiv='refresh' content='0; url=view_mem.php'>";
    } else {
        echo "<html><head><script>alert('ERROR! Delete Opertaion Unsucessfull');</script></head></html>";
        echo "error".pg_last_error($con);
    }
} else {
    echo "<html><head><script>alert('ERROR! Delete Opertaion Unsucessfull');</script></head></html>";
   echo "error".pg_last_error($con);
}

?>