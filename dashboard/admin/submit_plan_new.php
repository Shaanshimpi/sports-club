<?php
require '../../include/db_conn.php';
page_protect();

	// Escape inputs
	$planid = pg_escape_string($con, $_POST['planid']);
    $name = pg_escape_string($con, $_POST['planname']);
    $desc = pg_escape_string($con, $_POST['desc']);
    $planval = pg_escape_string($con, $_POST['planval']);
    $amount = pg_escape_string($con, $_POST['amount']);
    
   //Inserting data into plan table
    $query="INSERT INTO plan(pid,\"planName\",description,validity,amount,active) VALUES('$planid','$name','$desc','$planval','$amount','yes')";
   
   

	 $result = pg_query($con,$query);
	 if($result){
        
        echo "<head><script>alert('PLAN Added ');</script></head></html>";
        echo "<meta http-equiv='refresh' content='0; url=new_plan.php'>";
       
      }

    else{
        echo "<head><script>alert('NOT SUCCESSFUL, Check Again');</script></head></html>";
        echo "error".pg_last_error($con);
      }

?>
