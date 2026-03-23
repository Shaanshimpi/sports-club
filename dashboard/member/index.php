<?php
require '../../include/db_conn.php';
require '../../include/member_auth.php';
member_page_protect();
$uid = pg_escape_string($con, $_SESSION['member_userid']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SPORTS CLUB | Member Dashboard</title>
    <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
    <script type="text/javascript" src="../../js/Script.js"></script>
    <link rel="stylesheet" href="../../css/dashMain.css">
    <link rel="stylesheet" type="text/css" href="../../css/entypo.css">
</head>
<body class="page-body page-fade" onload="collapseSidebar()">
<div class="page-container sidebar-collapsed" id="navbarcollapse">
    <div class="sidebar-menu">
        <header class="logo-env">
            <div class="logo"><a href="index.php"><img src="../../logo1.png" alt="" width="192" height="80" /></a></div>
            <div class="sidebar-collapse" onclick="collapseSidebar()"><a href="#" class="sidebar-collapse-icon with-animation"><i class="entypo-menu"></i></a></div>
        </header>
        <?php include('nav.php'); ?>
    </div>

    <div class="main-content">
        <div class="row">
            <div class="col-md-6 col-sm-8 clearfix"></div>
            <div class="col-md-6 col-sm-4 clearfix hidden-xs">
                <ul class="list-inline links-list pull-right">
                    <li>Welcome <?php echo isset($_SESSION['member_name']) ? $_SESSION['member_name'] : $_SESSION['member_userid']; ?></li>
                    <li><a href="logout.php">Log Out <i class="entypo-logout right"></i></a></li>
                </ul>
            </div>
        </div>

        <h2>Member Dashboard</h2>
        <hr>

        <?php
        $q1 = pg_query($con, "SELECT COUNT(*) as total FROM enrolls_to WHERE uid='$uid'");
        $totalPayments = ($q1 && pg_num_rows($q1) == 1) ? pg_fetch_assoc($q1)['total'] : 0;

        $q2 = pg_query($con, "SELECT p.\"planName\", e.expire FROM enrolls_to e INNER JOIN plan p ON e.pid=p.pid WHERE e.uid='$uid' AND e.renewal='yes' ORDER BY e.et_id DESC LIMIT 1");
        $activePlan = '-';
        $expiry = '-';
        if ($q2 && pg_num_rows($q2) == 1) {
            $r2 = pg_fetch_assoc($q2);
            $activePlan = $r2['planName'];
            $expiry = $r2['expire'];
        }

        $q3 = pg_query($con, "SELECT weight, height, fat FROM health_status WHERE uid='$uid' ORDER BY hid DESC LIMIT 1");
        $healthText = 'No health data yet';
        if ($q3 && pg_num_rows($q3) == 1) {
            $r3 = pg_fetch_assoc($q3);
            $healthText = 'Wt: ' . $r3['weight'] . ', Ht: ' . $r3['height'] . ', Fat: ' . $r3['fat'];
        }
        ?>

        <div class="col-sm-4">
            <div class="tile-stats tile-green"><div class="icon"><i class="entypo-star"></i></div><div class="num"><h3>Active Plan</h3><?php echo $activePlan; ?></div></div>
        </div>
        <div class="col-sm-4">
            <div class="tile-stats tile-blue"><div class="icon"><i class="entypo-calendar"></i></div><div class="num"><h3>Membership Expiry</h3><?php echo $expiry; ?></div></div>
        </div>
        <div class="col-sm-4">
            <div class="tile-stats tile-red"><div class="icon"><i class="entypo-credit-card"></i></div><div class="num"><h3>Total Payments</h3><?php echo $totalPayments; ?></div></div>
        </div>

        <div class="col-sm-12" style="margin-top:20px;">
            <div class="tile-stats tile-aqua"><div class="icon"><i class="entypo-heart"></i></div><div class="num"><h3>Latest Health Status</h3><?php echo $healthText; ?></div></div>
        </div>

        <?php include('footer.php'); ?>
    </div>
</div>
</body>
</html>

