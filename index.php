<?php
require_once __DIR__ . '/check_license.php';
include __DIR__ . '/include/db_conn.php';

session_start();
if(isset($_SESSION["user_data"]))
{
	header("location:./dashboard/admin/");
}
if(isset($_SESSION["member_userid"]))
{
	header("location:./dashboard/member/");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Sports Club | Login</title>
	<link rel="stylesheet" href="./css/style.css"/>
	<link rel="stylesheet" type="text/css" href="./css/entypo.css">
</head>
<style>
h2 {
  color:white;
background-image: url("blaze_back_new.png");
width: 1366px;
align-items: center;
}
#studentRegisterForm label {
  display: block;
  color: #ddd;
  font-size: 12px;
  margin-bottom: 4px;
}
#studentRegisterForm select.form-control,
#studentRegisterForm select.form-control option {
  background: #2b303a !important;
  color: #fff !important;
}
</style>
<body>
    <center><h2 style="color:#7CFC00;font-size:30px"><BR><BR><BR>WELCOME TO <BR>SPORTS CLUB Management System <BR><BR><BR></h2></center>
<body class="page-body login-page login-form-fall">
    
    	<div id="container">
			<div class="login-container">
	
	<div class="login-header login-caret">
		
		<div class="login-content">
			
			<a href="#" class="logo">
				<img src="logo1.png" alt="" />
			</a>
			
			<p class="description">Dear user, log in to access your area!</p>
			
			<!-- progress bar indicator -->
			<div class="login-progressbar-indicator">
				<h3>43%</h3>
				<span>logging in...</span>
			</div>
		</div>
		
	</div>
	
	<div class="login-progressbar">
		<div></div>
	</div>
	
	<div class="login-form">
		
		<div class="login-content">
			
			<form action="secure_login.php" method='post' id="bb">				
				<div class="form-group">					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-user"></i>
						</div>
							<input type="text" placeholder="Admin username / Member ID / Member email" class="form-control" name="user_id_auth" id="textfield" data-rule-minlength="4" data-rule-required="true">
					</div>
				</div>				
								
				<div class="form-group">					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						<input type="password" name="pass_key" id="pwfield" class="form-control" data-rule-required="true" data-rule-minlength="6" placeholder="Password">
					</div>				
				</div>
				
				<div class="form-group">
					<button type="submit" name="btnLogin" class="btn btn-primary">
						Login In
						<i class="entypo-login"></i>
					</button>
				</div>
			</form>

				<div class="form-group">
					<button type="button" class="btn btn-primary" id="btnRegisterStudent">
						Register as a member
					</button>
				</div>

				<div id="studentRegisterForm" style="display:none;">
					<form action="register_student.php" method="post">
						<input type="hidden" name="m_id" value="<?php echo time(); ?>">
						<div class="form-group">
							<label for="u_name">Name</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-user"></i></div>
								<input type="text" name="u_name" id="u_name" class="form-control" placeholder="Name" required maxlength="40">
							</div>
						</div>
						<div class="form-group">
							<label for="street_name">Street name</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-key"></i></div>
								<input type="text" name="street_name" id="street_name" class="form-control" placeholder="Street name" required maxlength="40">
							</div>
						</div>
						<div class="form-group">
							<label for="city">City</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-key"></i></div>
								<input type="text" name="city" id="city" class="form-control" placeholder="City" required>
							</div>
						</div>
						<div class="form-group">
							<label for="zipcode">Zipcode</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-key"></i></div>
								<input type="number" name="zipcode" id="zipcode" class="form-control" placeholder="Zipcode" required maxlength="6">
							</div>
						</div>
						<div class="form-group">
							<label for="state">State</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-key"></i></div>
								<input type="text" name="state" id="state" class="form-control" placeholder="State" required>
							</div>
						</div>
						<div class="form-group">
							<label for="gender">Gender</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-user"></i></div>
								<select name="gender" id="gender" class="form-control" required>
									<option value="">-- Select --</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="dob">Date of birth</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-calendar"></i></div>
								<input type="date" name="dob" id="dob" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label for="mobile">Phone</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-key"></i></div>
								<input type="text" name="mobile" id="mobile" class="form-control" placeholder="Phone" required maxlength="20">
							</div>
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-mail"></i></div>
								<input type="email" name="email" id="email" class="form-control" placeholder="Email" required maxlength="100">
							</div>
						</div>
						<div class="form-group">
							<label for="member_pass">Password</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-key"></i></div>
								<input type="password" name="member_pass" id="member_pass" class="form-control" placeholder="Password" required maxlength="50">
							</div>
						</div>
						<div class="form-group">
							<label for="jdate">Joining date</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-calendar"></i></div>
								<input type="date" name="jdate" id="jdate" class="form-control" placeholder="Joining date" required>
							</div>
						</div>
						<div class="form-group">
							<label for="plan">Plan</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="entypo-key"></i></div>
								<select name="plan" id="plan" class="form-control" required>
									<option value="">-- Select plan --</option>
									<?php
									$plan_query = "SELECT * FROM plan WHERE active='yes'";
									$plan_result = pg_query($con, $plan_query);
									if ($plan_result && pg_num_rows($plan_result) != 0) {
										while ($plan_row = pg_fetch_row($plan_result)) {
											echo "<option value=\"" . htmlspecialchars($plan_row[0]) . "\">" . htmlspecialchars($plan_row[1]) . "</option>";
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<button type="submit" name="btnRegister" class="btn btn-primary">Submit</button>
							<button type="button" class="btn btn-primary" id="btnCancelRegister">Cancel</button>
						</div>
					</form>
				</div>
				<?php if (isset($_GET['registered']) && $_GET['registered'] === '1'): ?>
				<p style="color:#7CFC00;">You have been enrolled. Use the same login form above with your Member ID or Email + Password.</p>
				<?php elseif (isset($_GET['registered']) && $_GET['registered'] === '0'): ?>
				<p style="color:#ff6b6b;">Registration failed. Please try again.</p>
				<?php endif; ?>
		
				<div class="login-bottom-links">
					<a href="forgot_password.php" class="link">Forgot your password?</a>
				</div>			
		</div>
		
	</div>
	
</div>

		</div>

<script>
(function(){
	var btn = document.getElementById('btnRegisterStudent');
	var form = document.getElementById('studentRegisterForm');
	var cancel = document.getElementById('btnCancelRegister');
	if (btn) btn.onclick = function(){ form.style.display = form.style.display === 'none' ? 'block' : 'none'; };
	if (cancel) cancel.onclick = function(){ form.style.display = 'none'; };
})();
</script>
</body>
</html>
