<?php
require '../../include/db_conn.php';
require '../../include/member_auth.php';
member_page_protect();
$uid = pg_escape_string($con, $_SESSION['member_userid']);
$query = "SELECT p.\"planName\", p.description, p.validity, p.amount, e.paid_date, e.expire, e.renewal FROM enrolls_to e INNER JOIN plan p ON e.pid=p.pid WHERE e.uid='$uid' ORDER BY e.et_id DESC";
$result = pg_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SPORTS CLUB | My Plan</title>
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
        <h3>My Plan Details</h3>
        <hr>
        <table class="table table-bordered datatable" border="1">
            <thead><tr><th>Sl.No</th><th>Plan Name</th><th>Description</th><th>Validity</th><th>Amount</th><th>Paid Date</th><th>Expire Date</th><th>Status</th></tr></thead>
            <tbody>
            <?php
            $sno = 1;
            if ($result && pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $sno . "</td>";
                    echo "<td>" . $row['planName'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>" . $row['validity'] . " Month</td>";
                    echo "<td>" . $row['amount'] . "</td>";
                    echo "<td>" . $row['paid_date'] . "</td>";
                    echo "<td>" . $row['expire'] . "</td>";
                    echo "<td>" . $row['renewal'] . "</td>";
                    echo "</tr>";
                    $sno++;
                }
            }
            ?>
            </tbody>
        </table>
        <?php include('footer.php'); ?>
    </div>
</div>
</body>
</html>

