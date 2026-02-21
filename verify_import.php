<?php
/**
 * Phase 1 Verification Script
 * Verifies that PostgreSQL schema import was successful
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
    
    // Check if all tables exist
    $expected_tables = ['admin', 'users', 'log_users', 'health_status', 'plan', 'enrolls_to', 'sports_timetable', 'address'];
    $query = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE' ORDER BY table_name";
    $result = pg_query($conn, $query);
    
    if (!$result) {
        $errors[] = "❌ Error querying tables: " . pg_last_error($conn);
    } else {
        $found_tables = [];
        while ($row = pg_fetch_assoc($result)) {
            $found_tables[] = $row['table_name'];
        }
        
        $missing_tables = array_diff($expected_tables, $found_tables);
        $extra_tables = array_diff($found_tables, $expected_tables);
        
        if (empty($missing_tables)) {
            $success[] = "✅ All 8 expected tables found: " . implode(', ', $found_tables);
        } else {
            $errors[] = "❌ Missing tables: " . implode(', ', $missing_tables);
        }
        
        if (!empty($extra_tables)) {
            $success[] = "ℹ️ Additional tables found: " . implode(', ', $extra_tables);
        }
    }
    
    // Verify data counts
    $data_checks = [
        'admin' => 2,
        'users' => 2,
        'plan' => 3,
        'health_status' => 2,
        'enrolls_to' => 1,
        'address' => 1
    ];
    
    foreach ($data_checks as $table => $expected_count) {
        $query = "SELECT COUNT(*) as count FROM $table";
        $result = pg_query($conn, $query);
        
        if ($result) {
            $row = pg_fetch_assoc($result);
            $actual_count = (int)$row['count'];
            
            if ($actual_count == $expected_count) {
                $success[] = "✅ Table '$table': $actual_count records (expected: $expected_count)";
            } else {
                $errors[] = "⚠️ Table '$table': $actual_count records (expected: $expected_count)";
            }
        } else {
            $errors[] = "❌ Error counting records in '$table': " . pg_last_error($conn);
        }
    }
    
    // Check if triggers exist
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
    
    // Check if function exists
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
    
    // Check foreign key constraints
    $query = "SELECT COUNT(*) as count FROM information_schema.table_constraints WHERE constraint_schema = 'public' AND constraint_type = 'FOREIGN KEY'";
    $result = pg_query($conn, $query);
    
    if ($result) {
        $row = pg_fetch_assoc($result);
        $fk_count = (int)$row['count'];
        if ($fk_count >= 4) {
            $success[] = "✅ Foreign key constraints found: $fk_count";
        } else {
            $errors[] = "⚠️ Expected at least 4 foreign keys, found: $fk_count";
        }
    }
    
    // Test trigger functionality (insert test)
    // Generate a test userid that fits in VARCHAR(20) - use timestamp but limit to 20 chars
    $timestamp = time();
    $test_userid = 'TEST' . substr($timestamp, -16); // 'TEST' (4) + last 16 digits of timestamp = 20 chars max
    $test_email = 'test' . substr($timestamp, -10) . '@test.com'; // Unique email
    $insert_query = "INSERT INTO users (userid, username, gender, mobile, email, dob, joining_date) VALUES ('$test_userid', 'Test User', 'Male', '1234567890', '$test_email', '2000-01-01', '2024-01-01')";
    $insert_result = pg_query($conn, $insert_query);
    
    if ($insert_result) {
        // Check if trigger created log entry
        $log_query = "SELECT * FROM log_users WHERE users_userid = '$test_userid' AND action = 'inserted'";
        $log_result = pg_query($conn, $log_query);
        
        if ($log_result && pg_num_rows($log_result) > 0) {
            $success[] = "✅ Insert trigger working: log entry created for test user";
        } else {
            $errors[] = "⚠️ Insert trigger may not be working: no log entry found";
        }
        
        // Clean up test data
        pg_query($conn, "DELETE FROM users WHERE userid = '$test_userid'");
        pg_query($conn, "DELETE FROM log_users WHERE users_userid = '$test_userid'");
    } else {
        $errors[] = "⚠️ Could not test trigger: " . pg_last_error($conn);
    }
    
    pg_close($conn);
    
} catch (Exception $e) {
    $errors[] = "❌ Exception: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Phase 1 Import Verification</title>
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
        .summary h2 {
            margin-top: 0;
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
        <h1>Phase 1: PostgreSQL Import Verification</h1>
        
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
                <p style="color: #4CAF50; font-weight: bold;">✅ Phase 1 is complete! You can proceed to Phase 2.</p>
            <?php else: ?>
                <p style="color: #f44336; font-weight: bold;">⚠️ Please review the errors above before proceeding.</p>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p><a href="index.php">← Back to Login</a></p>
        </div>
    </div>
</body>
</html>

