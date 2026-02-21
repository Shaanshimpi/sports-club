<?php
require '../../include/db_conn.php';
$month = pg_escape_string($con, $_GET['mm']);
$year = pg_escape_string($con, $_GET['yy']);
$flag = pg_escape_string($con, $_GET['flag']);

$query="";

if($flag==0)
	$query="SELECT * FROM users u INNER JOIN address a ON u.userid=a.id WHERE u.joining_date LIKE '$year-$month%'";
else if($flag==1)
	$query="SELECT * FROM users u INNER JOIN address a ON u.userid=a.id WHERE u.joining_date LIKE '$year%'";
  

$res=pg_query($con,$query);
echo "<tbody border=1>";

$sno    = 1;

if (pg_num_rows($res) != 0) {

	echo "<thead>
				<tr>
					<th>Sl.No</th>
					<th>Member ID</th>
					<th>Name</th>
					<th>Contact</th>
					<th>Gender</th>
					<th>State</th>
					<th>City</th>
					<th>DOB</th>
					<th>Joining_Date</th>
				</tr>
	</thead>";

    while ($row = pg_fetch_assoc($res)) {
      

                echo "<tr><td>".$sno."</td>";
                
                echo "<td>" . $row['userid'] . "</td>";

                echo "<td width='25%'>" . $row['username'] . "</td>";

                echo "<td>" . $row['mobile'] . "</td>";


                echo "<td>" . $row['gender'] . "</td>";

                echo "<td>" . $row['state'] . "</td>";

                echo "<td>" . $row['city'] . "</td>";

                echo "<td>" . $row['dob'] . "</td>";

                echo "<td>" . $row['joining_date'] ."</td></tr>";
                
                $sno++;
            
        
    }

}
else{
	if($flag==0){
		$monthName = date("F", mktime(0, 0, 0, $month, 10));
		echo "<h2>No Data found On ".$monthName." ".$year."</h2>";
	}
	else if($flag==1)
		echo "<h2>No Data found On ".$year."</h2";
}
echo "</tbody>";


?>
