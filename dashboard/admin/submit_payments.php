<?php
require '../../include/db_conn.php';
page_protect();

 $memID=$_POST['m_id'];
 $plan=$_POST['plan'];

// Escape inputs
$memID = pg_escape_string($con, $memID);
$plan = pg_escape_string($con, $plan);

//updating renewal from yes to no from enrolls_to table
$query="UPDATE enrolls_to SET renewal='no' WHERE uid='$memID'";
    $result = pg_query($con,$query);
    if($result){
      //inserting new payment data into enrolls_to table
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

               echo "<head><script>alert('Payment Successfully update ');</script></head></html>";
               echo "<meta http-equiv='refresh' content='0; url=payments.php'>";
            }
             
            else{
               echo "<head><script>alert('Payment update Failed');</script></head></html>";
              echo "error: ".pg_last_error($con);
            }
            
          }
          else{
            echo "<head><script>alert('Payment update Failed');</script></head></html>";
            echo "error: ".pg_last_error($con);
          }

         
        }
        else
        {
          echo "<head><script>alert('Payment update Failed');</script></head></html>";
          echo "error: ".pg_last_error($con);
        }

?>
