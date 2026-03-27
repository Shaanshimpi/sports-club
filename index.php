<?php
require_once __DIR__ . '/check_license.php';

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
	<title>Sports Club | Home</title>
	<link rel="stylesheet" href="./css/style.css"/>
	<link rel="stylesheet" type="text/css" href="./css/entypo.css">
</head>
<style>
body { margin:0; font-family: Arial, Helvetica, sans-serif; }
.hero {
	background: #1f242c url("blaze_back_new.png") no-repeat center top;
	background-size: cover;
	min-height: 70vh;
	color: white;
	display: flex;
	align-items: center;
	justify-content: center;
	text-align: center;
	padding: 40px 15px;
}
.hero-inner {
	background: rgba(0,0,0,0.55);
	padding: 28px 18px;
	border-radius: 8px;
	max-width: 820px;
}
.hero h1 { margin: 0 0 10px 0; font-size: 40px; color:#7CFC00; }
.hero p { margin: 0 0 18px 0; font-size: 16px; color:#e6e6e6; }
.hero .subtext { font-size: 14px; color:#d4d4d4; margin-top: 8px; }
.btn-row { margin-top: 18px; }
.btn-row a { margin: 6px; display:inline-block; }
.section {
	padding: 25px 10px;
	background: #2b303a;
	color: #fff;
}
.about {
	max-width: 920px;
	margin: 0 auto 18px auto;
	background: #1f242c;
	border:1px solid rgba(255,255,255,0.08);
	border-radius:10px;
	padding: 16px 16px;
	line-height: 1.55;
	font-size: 14px;
	color: #dcdcdc;
}
.about h2 {
	margin: 0 0 8px 0;
	font-size: 22px;
	color: #7CFC00;
}
.cards { max-width: 900px; margin: 0 auto; display:flex; flex-wrap:wrap; gap:12px; justify-content:center; }
.card {
	background:#1f242c;
	border:1px solid rgba(255,255,255,0.08);
	border-radius:10px;
	padding: 14px 14px;
	width: 260px;
}
.card h3 { margin: 0 0 8px 0; font-size: 18px; color:#7CFC00; }
.card p { margin: 0; font-size: 13px; color:#dcdcdc; }
.split {
	max-width: 920px;
	margin: 20px auto 0 auto;
	display:flex;
	flex-wrap:wrap;
	gap:12px;
}
.panel {
	flex: 1 1 300px;
	background:#1f242c;
	border:1px solid rgba(255,255,255,0.08);
	border-radius:10px;
	padding: 14px 14px;
}
.panel h3 {
	margin: 0 0 10px 0;
	font-size: 18px;
	color:#7CFC00;
}
.panel ul {
	margin: 0;
	padding-left: 18px;
	color:#dcdcdc;
	font-size: 13px;
	line-height: 1.6;
}
.cta {
	max-width: 920px;
	margin: 18px auto 0 auto;
	background:#1f242c;
	border:1px solid rgba(255,255,255,0.08);
	border-radius:10px;
	padding: 18px 16px;
	text-align:center;
}
.cta h3 {
	margin: 0 0 8px 0;
	color:#7CFC00;
	font-size: 20px;
}
.cta p {
	margin: 0 0 12px 0;
	color:#dcdcdc;
	font-size: 14px;
}
.footer {
	background:#111;
	color:#bbb;
	text-align:center;
	padding: 10px;
	font-size: 12px;
}
</style>
<body>
	<div class="hero">
		<div class="hero-inner">
			<img src="logo1.png" alt="" style="width:160px; height:auto;">
			<h1>SPORTS CLUB</h1>
			<p>Welcome to Sports Club Management System - one place to manage fitness, training, memberships, and player growth.</p>
			<p class="subtext">From football and cricket to badminton, swimming, yoga, and tennis, we support every learner from beginner level to advanced level with structured plans and progress tracking.</p>
			<div class="btn-row">
				<a class="btn btn-primary" href="login.php">Sign In</a>
				<a class="btn btn-primary" href="login.php#register">Register</a>
			</div>
		</div>
	</div>

	<div class="section">
		<div class="about">
			<h2>About Sports Club</h2>
			Our Sports Club is built to help students and members stay active, disciplined, and healthy through regular sports practice. The club provides coaching support, monthly plan subscriptions, payment tracking, and health updates such as height, weight, fat percentage, and calorie notes. Members can choose plans based on interest and continue renewal every month, while administrators manage plans, members, routines, and overall club performance from a central dashboard.
			<br><br>
			We focus on practical development: skill training sessions, fitness conditioning, and performance monitoring. This system makes day-to-day operations simple for the management team and gives members clear visibility of their own records, plan validity, and payment history.
		</div>
		<div class="cards">
			<div class="card">
				<h3>Admin Panel</h3>
				<p>Manage members, create plans, take payments, and view reports.</p>
			</div>
			<div class="card">
				<h3>Member Dashboard</h3>
				<p>View your plan, payments, and latest health status in one place.</p>
			</div>
			<div class="card">
				<h3>Simple & Fast</h3>
				<p>Beginner-friendly PHP project with clean flow for presentations.</p>
			</div>
			<div class="card">
				<h3>Health Tracking</h3>
				<p>Record and review calorie, height, weight, fat percentage, and remarks.</p>
			</div>
			<div class="card">
				<h3>Payment Records</h3>
				<p>Track monthly renewals, active memberships, and complete payment history.</p>
			</div>
			<div class="card">
				<h3>Plan Management</h3>
				<p>Create and maintain plan offerings with pricing and validity details.</p>
			</div>
		</div>

		<div class="split">
			<div class="panel">
				<h3>Sports We Support</h3>
				<ul>
					<li>Football training for beginners and intermediate players.</li>
					<li>Cricket practice with batting, bowling, and field drills.</li>
					<li>Badminton sessions for reflex, speed, and court movement.</li>
					<li>Swimming programs focused on endurance and technique.</li>
					<li>Yoga sessions for flexibility, breathing, and recovery.</li>
					<li>Tennis training for footwork and match practice.</li>
				</ul>
			</div>
			<div class="panel">
				<h3>Why This System Helps</h3>
				<ul>
					<li>Single login flow for both admin and member accounts.</li>
					<li>Centralized member profile with plan and payment records.</li>
					<li>Useful for coaching review and periodic performance discussions.</li>
					<li>Simple structure suitable for classroom projects and presentations.</li>
					<li>Auto-seeding support for quick setup in new systems.</li>
					<li>Clear dashboards to reduce manual record handling.</li>
				</ul>
			</div>
		</div>

		<div class="cta">
			<h3>Ready to Explore the Sports Club Platform?</h3>
			<p>Sign in as admin to manage the club, or register as a member to view your own plans, payments, and health progress.</p>
			<a class="btn btn-primary" href="login.php">Go to Sign In</a>
			<a class="btn btn-primary" href="login.php#register">Go to Register</a>
		</div>
	</div>

	<div class="footer"><strong>SPORTS CLUB</strong> | Home</div>
</body>
</html>
