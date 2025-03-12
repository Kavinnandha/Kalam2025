<?php
// Debug function to check authentication response
function debug_auth_request() {
    // PhonePe credential details
    $client_id = "M22DZIHTE7XA8"; // Replace with your client ID
    $client_secret = "bbdc4a8f-806b-4307-ad83-d0efefbe8725"; // Replace with your client secret
    $client_version = 1; // For UAT
    
    // OAuth token endpoint for UAT
    $oauth_url = "https://api.phonepe.com/apis/hermes/pg/v1/pay";
    
    // Prepare OAuth request
    $oauth_data = [
        "client_id" => $client_id,
        "client_version" => $client_version,
        "client_secret" => $client_secret,
        "grant_type" => "client_credentials"
    ];
    
    // Log the request details
    error_log("OAuth Request: " . json_encode($oauth_data));
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $oauth_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query($oauth_data),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/x-www-form-urlencoded"
        ],
        CURLOPT_VERBOSE => true
    ]);
    
    // Capture curl debug output
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($curl, CURLOPT_STDERR, $verbose);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    // Get verbose debug information
    rewind($verbose);
    $verboseLog = stream_get_contents($verbose);
    
    echo "<h2>Authentication Debug Information</h2>";
    echo "<h3>Request Details:</h3>";
    echo "<pre>";
    print_r([
        "client_id" => $client_id,
        "client_version" => $client_version,
        "client_secret" => "[REDACTED]",
        "grant_type" => "client_credentials"
    ]);
    echo "</pre>";
    
    echo "<h3>Response Code:</h3>";
    echo "<pre>$http_code</pre>";
    
    echo "<h3>Response Body:</h3>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    
    if ($err) {
        echo "<h3>cURL Error:</h3>";
        echo "<pre>$err</pre>";
    }
    
    echo "<h3>CURL Verbose Log:</h3>";
    echo "<pre>" . htmlspecialchars($verboseLog) . "</pre>";
    
    return json_decode($response, true);
}

// Run the debug function
$debug_result = debug_auth_request();
?>