<?php
require '../../include/db_conn.php';
require '../../include/member_auth.php';
member_page_protect();
$uid = pg_escape_string($con, $_SESSION['member_userid']);
$query = "SELECT calorie,height,weight,fat,remarks FROM health_status WHERE uid='$uid' ORDER BY hid DESC";
$result = pg_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SPORTS CLUB | My Health</title>
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
        <h3>My Health Status</h3>
        <hr>
        <table class="table table-bordered datatable" border="1">
            <thead><tr><th>Sl.No</th><th>Calorie</th><th>Height</th><th>Weight</th><th>Fat</th><th>Remarks</th></tr></thead>
            <tbody>
            <?php
            $sno = 1;
            if ($result && pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $sno . "</td>";
                    echo "<td>" . $row['calorie'] . "</td>";
                    echo "<td>" . $row['height'] . "</td>";
                    echo "<td>" . $row['weight'] . "</td>";
                    echo "<td>" . $row['fat'] . "</td>";
                    echo "<td>" . $row['remarks'] . "</td>";
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

