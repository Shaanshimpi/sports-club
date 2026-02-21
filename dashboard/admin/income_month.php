<?php
require '../../include/db_conn.php';
$month = pg_escape_string($con, $_GET['mm']);
$year = pg_escape_string($con, $_GET['yy']);

$query="SELECT DISTINCT u.userid,u.username,u.gender,u.mobile,
u.email,u.joining_date,a.state,a.city,
e.paid_date,e.expire,p.\"planName\",p.amount,p.validity FROM users u 
INNER JOIN address a ON u.userid=a.id 
INNER JOIN enrolls_to e ON u.userid=e.uid
INNER JOIN plan p ON p.pid=e.pid
WHERE e.paid_date LIKE '$year-$month%'";
  

$res=pg_query($con,$query);
echo "<tbody>";

$sno    = 1;
$totalamount=0;
if (pg_num_rows($res) != 0) {

	echo "<thead>
				<tr>
					<th>Sl.No</th>
					<th>Member ID</th>
					<th>Name</th>
					<th>Contact</th>
					<th>Gender</th>
					<th>State</th>
					<th>Paid_Date</th>
					<th>Expire_Date</th>
					<th>Plan_Name</th>
					<th>Amount</th>
					<th>Validity</th>
				</tr>
	</thead>";

    while ($row = pg_fetch_assoc($res)) {
      

                echo "<tr><td>".$sno."</td>";
                
                echo "<td>" . $row['userid'] . "</td>";

                echo "<td>" . $row['username'] . "</td>";

                echo "<td>" . $row['mobile'] . "</td>";


                echo "<td>" . $row['gender'] . "</td>";

                echo "<td>" . $row['state'] . "</td>";

                echo "<td>" . $row['paid_date'] . "</td>";

                echo "<td>" . $row['expire'] . "</td>";

                echo "<td>" . $row['planName'] . "</td>";

                echo "<td>" . $row['amount'] . "</td>";

                echo "<td>" . $row['validity'] . " Month</td>";
                
                $totalamount=$totalamount+$row['amount'];
                $sno++;
            
        
    }

 	$monthName = date("F", mktime(0, 0, 0, $month, 10));

    echo "<tr><td colspan=11 align='center'><h3>Total Income on ".$monthName." is ₹".$totalamount."</h3></td></tr>";

}
else{
		$monthName = date("F", mktime(0, 0, 0, $month, 10));
		echo "<h2>No Data found On ".$monthName." ".$year."</h2";
}
echo "</tbody>";


?>
