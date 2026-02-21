<?php
/**
 * Phase 4: Trigger and Function Verification Script
 * Tests PostgreSQL triggers and functions
 */

// PostgreSQL connection parameters
$host = "localhost";
$port = "5432";
$dbname = "sports_club_db";
$user = "postgres";
$password = "1234";

$errors = [];
$success = [];

try {
    // Connect to PostgreSQL
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
    
    if (!$conn) {
        die("❌ Connection failed: " . pg_last_error());
    }
    
    $success[] = "✅ Successfully connected to PostgreSQL database: $dbname";
    
    // Test 1: Verify triggers exist
    $query = "SELECT trigger_name FROM information_schema.triggers WHERE trigger_schema = 'public' AND event_object_table = 'users' ORDER BY trigger_name";
    $result = pg_query($conn, $query);
    
    if ($result) {
        $triggers = [];
        while ($row = pg_fetch_assoc($result)) {
            $triggers[] = $row['trigger_name'];
        }
        
        $expected_triggers = ['deletelog', 'insertlog', 'updatelog'];
        $found_triggers = array_intersect($expected_triggers, $triggers);
        
        if (count($found_triggers) == 3) {
            $success[] = "✅ All 3 triggers found: " . implode(', ', $found_triggers);
        } else {
            $missing = array_diff($expected_triggers, $found_triggers);
            $errors[] = "❌ Missing triggers: " . implode(', ', $missing);
        }
    } else {
        $errors[] = "❌ Error checking triggers: " . pg_last_error($conn);
    }
    
    // Test 2: Test INSERT trigger
    $test_userid = 'TEST' . substr(time(), -16); // 20 chars max
    $test_email = 'test' . substr(time(), -10) . '@test.com';
    
    // Get initial log count
    $log_count_before = pg_query($conn, "SELECT COUNT(*) as count FROM log_users");
    $row_before = pg_fetch_assoc($log_count_before);
    $count_before = (int)$row_before['count'];
    
    // Insert test user
    $insert_query = "INSERT INTO users (userid, username, gender, mobile, email, dob, joining_date) VALUES ('$test_userid', 'Test User', 'Male', '1234567890', '$test_email', '2000-01-01', '2024-01-01')";
    $insert_result = pg_query($conn, $insert_query);
    
    if ($insert_result) {
        // Check if log entry was created
        $log_query = "SELECT * FROM log_users WHERE users_userid = '$test_userid' AND action = 'inserted'";
        $log_result = pg_query($conn, $log_query);
        
        if ($log_result && pg_num_rows($log_result) > 0) {
            $success[] = "✅ INSERT trigger working: log entry created for test user";
        } else {
            $errors[] = "⚠️ INSERT trigger may not be working: no log entry found";
        }
        
        // Test 3: Test UPDATE trigger
        $update_query = "UPDATE users SET username = 'Updated Test User' WHERE userid = '$test_userid'";
        $update_result = pg_query($conn, $update_query);
        
        if ($update_result) {
            $update_log_query = "SELECT * FROM log_users WHERE users_userid = '$test_userid' AND action = 'updated'";
            $update_log_result = pg_query($conn, $update_log_query);
            
            if ($update_log_result && pg_num_rows($update_log_result) > 0) {
                $success[] = "✅ UPDATE trigger working: log entry created for update";
            } else {
                $errors[] = "⚠️ UPDATE trigger may not be working: no log entry found";
            }
        }
        
        // Test 4: Test DELETE trigger
        $delete_query = "DELETE FROM users WHERE userid = '$test_userid'";
        $delete_result = pg_query($conn, $delete_query);
        
        if ($delete_result) {
            $delete_log_query = "SELECT * FROM log_users WHERE users_userid = '$test_userid' AND action = 'deleted'";
            $delete_log_result = pg_query($conn, $delete_log_query);
            
            if ($delete_log_result && pg_num_rows($delete_log_result) > 0) {
                $success[] = "✅ DELETE trigger working: log entry created for delete";
            } else {
                $errors[] = "⚠️ DELETE trigger may not be working: no log entry found";
            }
        }
        
        // Clean up test log entries
        pg_query($conn, "DELETE FROM log_users WHERE users_userid = '$test_userid'");
    } else {
        $errors[] = "⚠️ Could not test triggers: " . pg_last_error($conn);
    }
    
    // Test 5: Verify countGender function exists
    $query = "SELECT routine_name FROM information_schema.routines WHERE routine_schema = 'public' AND routine_name = 'countgender'";
    $result = pg_query($conn, $query);
    
    if ($result) {
        $row = pg_fetch_assoc($result);
        if ($row) {
            $success[] = "✅ Function 'countGender' exists";
            
            // Test the function
            $test_result = pg_query($conn, "SELECT * FROM countGender()");
            if ($test_result) {
                $success[] = "✅ Function 'countGender' executes successfully";
                $rows = [];
                while ($row = pg_fetch_assoc($test_result)) {
                    $rows[] = $row;
                }
                if (!empty($rows)) {
                    $success[] = "   Function result: " . json_encode($rows);
                }
            } else {
                $errors[] = "⚠️ Function 'countGender' exists but execution failed: " . pg_last_error($conn);
            }
        } else {
            $errors[] = "❌ Function 'countGender' not found";
        }
    } else {
        $errors[] = "❌ Error checking function: " . pg_last_error($conn);
    }
    
    pg_close($conn);
    
} catch (Exception $e) {
    $errors[] = "❌ Exception: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Phase 4: Triggers & Functions Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .success {
            color: #4CAF50;
            margin: 5px 0;
            padding: 5px;
        }
        .error {
            color: #f44336;
            margin: 5px 0;
            padding: 5px;
        }
        .summary {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .status {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        .status.pass {
            color: #4CAF50;
        }
        .status.fail {
            color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Phase 4: Advanced Features Verification</h1>
        
        <?php if (empty($errors)): ?>
            <div class="status pass">✅ ALL CHECKS PASSED</div>
        <?php else: ?>
            <div class="status fail">⚠️ SOME ISSUES FOUND</div>
        <?php endif; ?>
        
        <h2>Verification Results:</h2>
        
        <?php foreach ($success as $msg): ?>
            <div class="success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endforeach; ?>
        
        <?php foreach ($errors as $msg): ?>
            <div class="error"><?php echo htmlspecialchars($msg); ?></div>
        <?php endforeach; ?>
        
        <div class="summary">
            <h2>Summary</h2>
            <p><strong>Success Checks:</strong> <?php echo count($success); ?></p>
            <p><strong>Errors/Warnings:</strong> <?php echo count($errors); ?></p>
            
            <?php if (empty($errors)): ?>
                <p style="color: #4CAF50; font-weight: bold;">✅ Phase 4 is complete! Triggers and functions are working correctly.</p>
            <?php else: ?>
                <p style="color: #f44336; font-weight: bold;">⚠️ Please review the errors above.</p>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p><a href="index.php">← Back to Login</a> | <a href="verify_import.php">← Phase 1 Verification</a></p>
        </div>
    </div>
</body>
</html>


