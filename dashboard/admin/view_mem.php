<?php
require '../../include/db_conn.php';
page_protect();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <title>SPORTS CLUB  | Member View</title>
    <link rel="stylesheet" href="../../css/style.css"  id="style-resource-5">
    <script type="text/javascript" src="../../js/Script.js"></script>
    <link rel="stylesheet" href="../../css/dashMain.css">
    <link rel="stylesheet" type="text/css" href="../../css/entypo.css">
	<link href="a1style.css" rel="stylesheet" type="text/css">
	
	<style>
 	#button1
	{
	width:126px;
	}

	.page-container .sidebar-menu #main-menu li#hassubopen > a {
	background-color: #2b303a;
	color: #ffffff;
	}

	</style>

</head>
    <body class="page-body  page-fade" onload="collapseSidebar()">

    	<div class="page-container sidebar-collapsed" id="navbarcollapse">	
	
		<div class="sidebar-menu">
	
			<header class="logo-env">
			
			<!-- logo -->
			<div class="logo">
				<a href="main.php">
					<img src="logo1.png" alt="" width="192" height="80" />
				</a>
			</div>
			
					<!-- logo collapse icon -->
					<div class="sidebar-collapse" onclick="collapseSidebar()">
				<a href="#" class="sidebar-collapse-icon with-animation"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
					<i class="entypo-menu"></i>
				</a>
			</div>
							
			
		
			</header>
    		<?php include('nav.php'); ?>
    	</div>


    		<div class="main-content">
		
				<div class="row">
					
					<!-- Profile Info and Notifications -->
					<div class="col-md-6 col-sm-8 clearfix">	
							
					</div>
					
					
					<!-- Raw Links -->
					<div class="col-md-6 col-sm-4 clearfix hidden-xs">
						
						<ul class="list-inline links-list pull-right">

							<li>Welcome <?php echo $_SESSION['full_name']; ?> 
							</li>								
						
							<li>
								<a href="logout.php">
									Log Out <i class="entypo-logout right"></i>
								</a>
							</li>
						</ul>
						
					</div>
					
				</div>

		<h3>Edit Member</h3>

		<hr />
		
		<table class="table table-bordered datatable" id="table-1" border=1>
			<thead>
				<tr><h2>
					<th>Sl.No</th>
					<th>Membership Expiry</th>
					<th>Member ID</th>
					<th>Name</th>
					<th>Contact</th>
					<th>E-Mail</th>
					<th>Gender</th>
					<th>Joining Date</th>
					<th>Action</th></h2>
				</tr>
			</thead>
				<tbody>

						<?php
							$query  = "SELECT * FROM users ORDER BY joining_date";
							//echo $query;
							$result = pg_query($con, $query);
							$sno    = 1;

							if (pg_num_rows($result) != 0) {
							    while ($row = pg_fetch_assoc($result)) {
							        $uid   = $row['userid'];
							        $uid_esc = pg_escape_string($con, $uid);
							        $query1  = "SELECT * FROM enrolls_to WHERE uid='$uid_esc' AND renewal='yes' ORDER BY et_id DESC LIMIT 1";
							        $result1 = pg_query($con, $query1);
							        $expire = (pg_num_rows($result1) == 1) ? pg_fetch_assoc($result1)['expire'] : '—';
							        
							        echo "<tr><td>".$sno."</td>";
							        echo "<td>" . htmlspecialchars($expire) . "</td>";
							        echo "<td>" . htmlspecialchars($row['userid']) . "</td>";
							        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
							        echo "<td>" . htmlspecialchars($row['mobile']) . "</td>";
							        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
							        echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
							        echo "<td>" . htmlspecialchars($row['joining_date']) . "</td>";
							        $sno++;
							        echo "<td><form action='read_member.php' method='post'><input type='hidden' name='name' value='" . htmlspecialchars($uid, ENT_QUOTES, 'UTF-8') . "'/><input type='submit' class='a1-btn a1-blue' id='button1' value='View History ' class='btn btn-info'/></form><form action='edit_member.php' method='post'><input type='hidden'  name='name' value='" . htmlspecialchars($uid, ENT_QUOTES, 'UTF-8') . "'/><input type='submit' class='a1-btn a1-green' id='button1' value='Edit' class='btn btn-warning'/></form><form action='del_member.php' method='post' onsubmit='return ConfirmDelete()'><input type='hidden' name='name' value='" . htmlspecialchars($uid, ENT_QUOTES, 'UTF-8') . "'/><input type='submit' value='Delete' width='20px' id='button1' class='a1-btn a1-orange'/></form></td></tr>";
							    }
							}
						?>									
					</tbody>
				</table>

<script>
	
	function ConfirmDelete(name){
	
    var r = confirm("Are you sure! You want to Delete this User?");
    if (r == true) {
       return true;
    } else {
        return false;
    }
}

</script>

			<?php include('footer.php'); ?>
    	</div>
    </body>
</html>





