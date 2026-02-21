<?php
require '../../include/db_conn.php';
$pid = pg_escape_string($con, $_GET['q']);
$query="SELECT * FROM plan WHERE pid='$pid'";
$res=pg_query($con,$query);
if($res){
	$row=pg_fetch_assoc($res);
	// echo "<tr><td>".$row['amount']."</td></tr>";
	echo "<tr>
		<td height='35'>AMOUNT:</td>
		<td height='35'>&nbsp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input id='boxx' type='text' value='₹".$row['amount']."' readonly></td></tr>
		<tr>
		<td height='35'>VALIDITY:</td>
		<td height='35'>&nbsp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type='text' id='boxx' value='".$row['validity']." Month' readonly></td>
		</tr>
	";
}

?>