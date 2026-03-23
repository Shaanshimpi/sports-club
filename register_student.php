<?php
require_once __DIR__ . '/check_license.php';
include __DIR__ . '/include/db_conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: index.php');
	exit;
}

$errors = [];
if (strlen($_POST['m_id']) > 20) {
	$errors[] = "Member ID must be 20 characters or less";
}
if (strlen($_POST['email']) > 100) {
	$errors[] = "Email must be 100 characters or less";
}
if (strlen($_POST['mobile']) > 20) {
	$errors[] = "Mobile number must be 20 characters or less";
}
if (strlen($_POST['u_name']) > 40) {
	$errors[] = "Username must be 40 characters or less";
}
if (strlen($_POST['member_pass']) < 4) {
	$errors[] = "Password must be at least 4 characters";
}
if (strlen($_POST['member_pass']) > 50) {
	$errors[] = "Password must be 50 characters or less";
}

if (!empty($errors)) {
	echo "<head><script>alert('Validation Error:\n" . implode("\n", array_map('addslashes', $errors)) . "');</script></head></html>";
	echo "<meta http-equiv='refresh' content='0; url=index.php'>";
	exit;
}

$memID = pg_escape_string($con, $_POST['m_id']);
$uname = pg_escape_string($con, $_POST['u_name']);
$stname = pg_escape_string($con, $_POST['street_name']);
$city = pg_escape_string($con, $_POST['city']);
$zipcode = pg_escape_string($con, $_POST['zipcode']);
$state = pg_escape_string($con, $_POST['state']);
$gender = pg_escape_string($con, $_POST['gender']);
$dob = pg_escape_string($con, $_POST['dob']);
$phn = pg_escape_string($con, $_POST['mobile']);
$email = pg_escape_string($con, $_POST['email']);
$jdate = pg_escape_string($con, $_POST['jdate']);
$plan = pg_escape_string($con, $_POST['plan']);
$memberPass = pg_escape_string($con, $_POST['member_pass']);

$memID = substr($memID, 0, 20);
$email = substr($email, 0, 100);
$phn = substr($phn, 0, 20);
$uname = substr($uname, 0, 40);
$stname = substr($stname, 0, 40);
$state = substr($state, 0, 15);
$city = substr($city, 0, 15);
$zipcode = substr($zipcode, 0, 20);
$memberPass = substr($memberPass, 0, 50);

$query = "INSERT INTO users(username,gender,mobile,email,dob,joining_date,userid) VALUES('$uname','$gender','$phn','$email','$dob','$jdate','$memID')";
$result = pg_query($con, $query);

if (!$result) {
	$error = pg_last_error($con);
	if (strpos($error, 'value too long') !== false) {
		echo "<head><script>alert('Error: One or more fields are too long.');</script></head></html>";
	} else if (strpos($error, 'duplicate key') !== false || strpos($error, 'unique constraint') !== false) {
		echo "<head><script>alert('Error: Member ID or Email already exists.');</script></head></html>";
	} else {
		echo "<head><script>alert('Registration Failed: " . addslashes($error) . "');</script></head></html>";
	}
	echo "<meta http-equiv='refresh' content='2; url=index.php'>";
	exit;
}

pg_query($con, "CREATE TABLE IF NOT EXISTS member_login(userid varchar(20) PRIMARY KEY, pass_key varchar(50) NOT NULL)");
$queryLogin = "INSERT INTO member_login(userid,pass_key) VALUES('$memID','$memberPass')";
$resultLogin = pg_query($con, $queryLogin);
if (!$resultLogin) {
	pg_query($con, "DELETE FROM users WHERE userid='$memID'");
	echo "<head><script>alert('Member Login Setup Failed');</script></head></html>";
	echo "error: " . pg_last_error($con);
	echo "<meta http-equiv='refresh' content='2; url=index.php'>";
	exit;
}

$query1 = "SELECT * FROM plan WHERE pid='$plan'";
$result1 = pg_query($con, $query1);

if (!$result1 || pg_num_rows($result1) == 0) {
	pg_query($con, "DELETE FROM users WHERE userid='$memID'");
	pg_query($con, "DELETE FROM member_login WHERE userid='$memID'");
	echo "<head><script>alert('Member Added Failed: Invalid plan.');</script></head></html>";
	echo "<meta http-equiv='refresh' content='2; url=index.php'>";
	exit;
}

$value = pg_fetch_row($result1);
date_default_timezone_set("Asia/Calcutta");
$d = strtotime("+" . $value[3] . " Months");
$cdate = date("Y-m-d");
$expiredate = date("Y-m-d", $d);

$query2 = "INSERT INTO enrolls_to(pid,uid,paid_date,expire,renewal) VALUES('$plan','$memID','$cdate','$expiredate','yes')";
$result2 = pg_query($con, $query2);

if (!$result2) {
	pg_query($con, "DELETE FROM users WHERE userid='$memID'");
	pg_query($con, "DELETE FROM member_login WHERE userid='$memID'");
	echo "<head><script>alert('Member Added Failed');</script></head></html>";
	echo "error: " . pg_last_error($con);
	echo "<meta http-equiv='refresh' content='2; url=index.php'>";
	exit;
}

$query4 = "INSERT INTO health_status(uid) VALUES('$memID')";
$result4 = pg_query($con, $query4);
if (!$result4) {
	pg_query($con, "DELETE FROM enrolls_to WHERE uid='$memID'");
	pg_query($con, "DELETE FROM users WHERE userid='$memID'");
	pg_query($con, "DELETE FROM member_login WHERE userid='$memID'");
	echo "<head><script>alert('Member Added Failed');</script></head></html>";
	echo "<meta http-equiv='refresh' content='2; url=index.php'>";
	exit;
}

$query5 = "INSERT INTO address(id,\"streetName\",state,city,zipcode) VALUES('$memID','$stname','$state','$city','$zipcode')";
$result5 = pg_query($con, $query5);
if (!$result5) {
	pg_query($con, "DELETE FROM health_status WHERE uid='$memID'");
	pg_query($con, "DELETE FROM enrolls_to WHERE uid='$memID'");
	pg_query($con, "DELETE FROM users WHERE userid='$memID'");
	pg_query($con, "DELETE FROM member_login WHERE userid='$memID'");
	echo "<head><script>alert('Member Added Failed');</script></head></html>";
	echo "error: " . pg_last_error($con);
	echo "<meta http-equiv='refresh' content='2; url=index.php'>";
	exit;
}

header('Location: index.php?registered=1');
exit;
?>
