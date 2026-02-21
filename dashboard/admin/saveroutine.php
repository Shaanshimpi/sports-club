<?php
	require '../../include/db_conn.php';
	page_protect();
	
		
		$rname=$_POST["rname"];
		$day1=$_POST["day1"];
		$day2=$_POST["day2"];
		$day3=$_POST["day3"];
	    $day4=$_POST["day4"];
		$day5=$_POST["day5"];
		$day6=$_POST["day6"];
		$pid=$_POST["pidd"];
		
		
		// Escape inputs
		$rname = pg_escape_string($con, $rname);
		$day1 = pg_escape_string($con, $day1);
		$day2 = pg_escape_string($con, $day2);
		$day3 = pg_escape_string($con, $day3);
		$day4 = pg_escape_string($con, $day4);
		$day5 = pg_escape_string($con, $day5);
		$day6 = pg_escape_string($con, $day6);
		$pid = pg_escape_string($con, $pid);
		
		// Validate that the plan ID exists
		$check_plan = "SELECT pid FROM plan WHERE pid='$pid' AND active='yes'";
		$plan_check = pg_query($con, $check_plan);
		
		if (!$plan_check || pg_num_rows($plan_check) == 0) {
			echo "<head><script>alert('Error: Invalid Plan ID selected. Please select a valid plan.');</script></head></html>";
			echo "<meta http-equiv='refresh' content='0; url=addroutine.php'>";
			exit;
		}
		
		$sql="INSERT INTO sports_timetable(tname,day1,day2,day3,day4,day5,day6,pid) VALUES('$rname','$day1','$day2','$day3','$day4','$day5','$day6','$pid')";
	
		$result=pg_query($con,$sql);
		if($result){	
		
			echo "<head><script>alert('Routine Added Successfully');</script></head></html>";
			echo "<meta http-equiv='refresh' content='0; url=addroutine.php'>";
		}else{
			$error = pg_last_error($con);
			// Provide user-friendly error messages
			if (strpos($error, 'foreign key constraint') !== false) {
				echo "<head><script>alert('Error: Invalid Plan ID. Please select a valid plan from the dropdown.');</script></head></html>";
			} else {
				echo "<head><script>alert('Routine Added Failed: " . addslashes($error) . "');</script></head></html>";
			}
			echo "<meta http-equiv='refresh' content='2; url=addroutine.php'>";
		}
	
	
?>