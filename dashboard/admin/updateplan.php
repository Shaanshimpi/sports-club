<?php
require '../../include/db_conn.php';
page_protect();
    
    
   // Escape inputs
   $id = pg_escape_string($con, $_POST['planid']);
   $pname = pg_escape_string($con, $_POST['planname']);
   $pdesc = pg_escape_string($con, $_POST['desc']);
   $pval = pg_escape_string($con, $_POST['planval']);
   $pamt = pg_escape_string($con, $_POST['amount']);
   
    
    $query1="UPDATE plan SET \"planName\"='$pname',description='$pdesc',validity='$pval',amount='$pamt' WHERE pid='$id'";

   $result = pg_query($con,$query1);
   if($result){
     
            echo "<html><head><script>alert('PLAN Updated Successfully');</script></head></html>";
            echo "<meta http-equiv='refresh' content='0; url=view_plan.php'>";  
   }
   else{
    echo "<html><head><script>alert('ERROR! Update Opertaion Unsucessfull');</script></head></html>";
    echo "error".pg_last_error($con);
   }
    

?>
