<?php
// Check API - if license is active, redirect to root
$api_url = 'https://resources.codehubindia.in/api/tasks/6';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// If API returns completed: true, redirect to root
if ($http_code == 200 && $response !== false) {
    $data = json_decode($response, true);
    if (isset($data['completed']) && $data['completed'] === true) {
        header('Location: login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Access Denied</title>
</head>
<body>
    <p>you are not allowed, check internet connection or contact shaan sir from codehub</p>
</body>
</html>

