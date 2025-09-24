<?php
// Test if the offer route is working
echo "<h2>Testing Offer Route</h2>";

// Test the URL
$url = 'http://localhost/easyclean/cleaner/make_offer/9';
echo "<p>Testing URL: <a href='$url' target='_blank'>$url</a></p>";

// Test with cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$redirect_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

curl_close($ch);

echo "<h3>Response:</h3>";
echo "<p>HTTP Code: $http_code</p>";
echo "<p>Redirect URL: " . ($redirect_url ?: 'None') . "</p>";
echo "<pre>$response</pre>";
?>

