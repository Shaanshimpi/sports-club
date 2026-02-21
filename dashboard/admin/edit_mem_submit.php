<?php
require '../../include/db_conn.php';
page_protect();
    
    
   // Escape all inputs
   $uid = pg_escape_string($con, $_POST['uid']);
   $uname = pg_escape_string($con, $_POST['uname']);
   $gender = pg_escape_string($con, $_POST['gender']);
   $mobile = pg_escape_string($con, $_POST['phone']);
   $email = pg_escape_string($con, $_POST['email']);
   $dob = pg_escape_string($con, $_POST['dob']);
   $jdate = pg_escape_string($con, $_POST['jdate']);
   $stname = pg_escape_string($con, $_POST['stname']);
   $state = pg_escape_string($con, $_POST['state']);
   $city = pg_escape_string($con, $_POST['city']);
   $zipcode = pg_escape_string($con, $_POST['zipcode']);
   $calorie = pg_escape_string($con, $_POST['calorie']);
   $height = pg_escape_string($con, $_POST['height']);
   $weight = pg_escape_string($con, $_POST['weight']);
   $fat = pg_escape_string($con, $_POST['fat']);
   $remarks = pg_escape_string($con, $_POST['remarks']);
    
    $query1="UPDATE users SET username='$uname',gender='$gender',mobile='$mobile',email='$email',dob='$dob',joining_date='$jdate' WHERE userid='$uid'";

   $result1 = pg_query($con,$query1);
   if($result1){
     $query2="UPDATE address SET \"streetName\"='$stname',state='$state',city='$city',zipcode='$zipcode' WHERE id='$uid'";
     $result2 = pg_query($con,$query2);
     if($result2){
        $query3="UPDATE health_status SET calorie='$calorie',height='$height',weight='$weight',fat='$fat',remarks='$remarks' WHERE uid='$uid'";
        $result3 = pg_query($con,$query3);
        if($result3){
            echo "<html><head><script>alert('Member Update Successfully');</script></head></html>";
            echo "<meta http-equiv='refresh' content='0; url=view_mem.php'>";
        }else{
             echo "<html><head><script>alert('ERROR! Update Opertaion Unsucessfull');</script></head></html>";
             echo "error".pg_last_error($con);
        }
     }else{
        echo "<html><head><script>alert('ERROR! Update Opertaion Unsucessfull');</script></head></html>";
         echo "error".pg_last_error($con);
     }
   }else{
    echo "<html><head><script>alert('ERROR! Update Opertaion Unsucessfull');</script></head></html>";
    echo "error".pg_last_error($con);
   }
    

?>
