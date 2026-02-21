<?php
require '../../include/db_conn.php';
page_protect();

 $memID=$_POST['m_id'];
 $uname=$_POST['u_name'];
 $stname=$_POST['street_name'];
 $city=$_POST['city'];
 $zipcode=$_POST['zipcode'];
 $state=$_POST['state'];
 $gender=$_POST['gender'];
 $dob=$_POST['dob'];
 $phn=$_POST['mobile'];
 $email=$_POST['email'];
 $jdate=$_POST['jdate'];
 $plan=$_POST['plan'];

// Validate field lengths before escaping
$errors = [];

if (strlen($_POST['m_id']) > 20) {
    $errors[] = "Member ID must be 20 characters or less";
}
if (strlen($_POST['email']) > 100) {
    $errors[] = "Email must be 100 characters or less (current: " . strlen($_POST['email']) . " characters)";
}
if (strlen($_POST['mobile']) > 20) {
    $errors[] = "Mobile number must be 20 characters or less";
}
if (strlen($_POST['u_name']) > 40) {
    $errors[] = "Username must be 40 characters or less";
}

if (!empty($errors)) {
    echo "<head><script>alert('Validation Error:\\n" . implode("\\n", $errors) . "');</script></head></html>";
    echo "<meta http-equiv='refresh' content='0; url=new_entry.php'>";
    exit;
}

// Escape all user inputs
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

// Truncate fields to match database constraints
$memID = substr($memID, 0, 20);
$email = substr($email, 0, 100); // Updated to match new VARCHAR(100) limit
$phn = substr($phn, 0, 20);
$uname = substr($uname, 0, 40);

//inserting into users table
$query="INSERT INTO users(username,gender,mobile,email,dob,joining_date,userid) VALUES('$uname','$gender','$phn','$email','$dob','$jdate','$memID')";
    $result = pg_query($con,$query);
    if($result){
      //Retrieve information of plan selected by user
      $query1="SELECT * FROM plan WHERE pid='$plan'";
      $result1=pg_query($con,$query1);

        if($result1){
          $value=pg_fetch_row($result1);
          date_default_timezone_set("Asia/Calcutta"); 
          $d=strtotime("+".$value[3]." Months");
          $cdate=date("Y-m-d"); //current date
          $expiredate=date("Y-m-d",$d); //adding validity retrieve from plan to current date
          //inserting into enrolls_to table of corresponding userid
          $query2="INSERT INTO enrolls_to(pid,uid,paid_date,expire,renewal) VALUES('$plan','$memID','$cdate','$expiredate','yes')";
          $result2 = pg_query($con,$query2);
          if($result2){

            $query4="INSERT INTO health_status(uid) VALUES('$memID')";
            $result4 = pg_query($con,$query4);
            if($result4){

              $query5="INSERT INTO address(id,\"streetName\",state,city,zipcode) VALUES('$memID','$stname','$state','$city','$zipcode')";
              $result5 = pg_query($con,$query5);
              if($result5){
               echo "<head><script>alert('Member Added ');</script></head></html>";
               echo "<meta http-equiv='refresh' content='0; url=new_entry.php'>";
              }
              else{
                  echo "<head><script>alert('Member Added Failed');</script></head></html>";
                 echo "error: ".pg_last_error($con);
                 //Deleting record of users if inserting to enrolls_to table failed to execute
                 $query3 = "DELETE FROM users WHERE userid='$memID'";
                 pg_query($con,$query3);
              }
            }
             
            else{
               echo "<head><script>alert('Member Added Failed');</script></head></html>";
              echo "error: ".pg_last_error($con);
               //Deleting record of users if inserting to enrolls_to table failed to execute
                $query3 = "DELETE FROM users WHERE userid='$memID'";
                pg_query($con,$query3);
            }
            
          }
          else{
            echo "<head><script>alert('Member Added Failed');</script></head></html>";
            echo "error: ".pg_last_error($con);
            //Deleting record of users if inserting to enrolls_to table failed to execute
             $query3 = "DELETE FROM users WHERE userid='$memID'";
             pg_query($con,$query3);
          }

         
        }
        else
        {
          echo "<head><script>alert('Member Added Failed');</script></head></html>";
          echo "error: ".pg_last_error($con);
           //Deleting record of users if retrieving inf of plan failed
          $query3 = "DELETE FROM users WHERE userid='$memID'";
          pg_query($con,$query3);
        }

    }
    else{
        $error = pg_last_error($con);
        // Provide user-friendly error messages
        if (strpos($error, 'value too long') !== false) {
            echo "<head><script>alert('Error: One or more fields are too long.\\n\\nPlease ensure:\\n- Member ID: max 20 characters\\n- Email: max 100 characters\\n- Mobile: max 20 characters\\n- Username: max 40 characters');</script></head></html>";
        } else if (strpos($error, 'duplicate key') !== false || strpos($error, 'unique constraint') !== false) {
            echo "<head><script>alert('Error: Member ID or Email already exists. Please use a different Member ID or Email.');</script></head></html>";
        } else {
            echo "<head><script>alert('Member Added Failed: " . addslashes($error) . "');</script></head></html>";
        }
        echo "<meta http-equiv='refresh' content='2; url=new_entry.php'>";
      }
?>
