<?php
// Simple License Check - Add this at the top of every PHP page

$api_url = 'https://resources.codehubindia.in/api/tasks/6';

// Call API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Check if API call was successful
if ($http_code == 200 && $response !== false) {
    // Decode JSON response
    $data = json_decode($response, true);
    
    // Check if completed key exists and is true
    if (isset($data['completed']) && $data['completed'] === true) {
        // License is valid, continue normally
        return;
    }
}

// License invalid or API failed, redirect to contact page
if(false){              //change this to true
    return;
}else{
    header('Location: contact_me.php');
}
exit();

