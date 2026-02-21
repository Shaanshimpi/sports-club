<?php
require '../../include/db_conn.php';
page_protect();
    
    
   // Escape inputs
   $id = pg_escape_string($con, $_POST['tid']);
   $day1 = pg_escape_string($con, $_POST['day1']);
   $day2 = pg_escape_string($con, $_POST['day2']);
   $day3 = pg_escape_string($con, $_POST['day3']);
   $day4 = pg_escape_string($con, $_POST['day4']);
   $day5 = pg_escape_string($con, $_POST['day5']);
   $day6 = pg_escape_string($con, $_POST['day6']);
   
    
    $query1="UPDATE sports_timetable SET day1='$day1',day2='$day2',day3='$day3',day4='$day4',day5='$day5',day6='$day6' WHERE tid=$id";

   $result = pg_query($con,$query1);
   if($result){
     
            echo "<html><head><script>alert('Routine Updated Successfully');</script></head></html>";
            echo "<meta http-equiv='refresh' content='0; url=viewroutine.php'>";  
   }
   else{
    echo "<html><head><script>alert('ERROR! Update Opertaion Unsucessfull');</script></head></html>";
    echo "error".pg_last_error($con);
   }
    

?>
