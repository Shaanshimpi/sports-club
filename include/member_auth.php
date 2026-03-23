<?php
function member_page_protect()
{
    session_start();
    if (!isset($_SESSION['member_userid']) || !isset($_SESSION['member_logged'])) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
        exit();
    }
}
?>

