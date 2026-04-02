<?php
require '../../include/db_conn.php';
require '../../include/member_auth.php';
member_page_protect();
$uid = pg_escape_string($con, $_SESSION['member_userid']);
$query = "SELECT st.tid, st.tname, st.day1, st.day2, st.day3, st.day4, st.day5, st.day6,
                 p.\"planName\"
          FROM sports_timetable st
          INNER JOIN plan p ON p.pid = st.pid
          WHERE st.pid IN (
              SELECT DISTINCT pid FROM enrolls_to WHERE uid = '$uid'
          )
          ORDER BY p.\"planName\", st.tid";
$result = pg_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SPORTS CLUB | My Routines</title>
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
        <h3>My Sports Routines</h3>
        <p style="color:#888; font-size:13px;">Schedules for every plan you have enrolled in (past or current).</p>
        <hr>
        <table class="table table-bordered datatable" border="1">
            <thead>
                <tr>
                    <th>Sl.No</th>
                    <th>Plan</th>
                    <th>Routine Name</th>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sno = 1;
            if ($result && pg_num_rows($result) > 0) {
                while ($row = pg_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $sno . "</td>";
                    echo "<td>" . htmlspecialchars($row['planName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tname']) . "</td>";
                    echo "<td>" . htmlspecialchars((string)$row['day1']) . "</td>";
                    echo "<td>" . htmlspecialchars((string)$row['day2']) . "</td>";
                    echo "<td>" . htmlspecialchars((string)$row['day3']) . "</td>";
                    echo "<td>" . htmlspecialchars((string)$row['day4']) . "</td>";
                    echo "<td>" . htmlspecialchars((string)$row['day5']) . "</td>";
                    echo "<td>" . htmlspecialchars((string)$row['day6']) . "</td>";
                    echo "</tr>";
                    $sno++;
                }
            } else {
                echo "<tr><td colspan=\"9\">No routines found for your enrolled plans, or you have no enrollments yet.</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <?php include('footer.php'); ?>
    </div>
</div>
</body>
</html>
