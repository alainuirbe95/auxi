<?php
// Test if we can access the cleaner controller
echo "<h2>Testing Cleaner Route</h2>";

// Test the URL
$url = 'http://localhost/easyclean/cleaner/make_offer/9';
echo "<p>Testing URL: <a href='$url' target='_blank'>$url</a></p>";

// Test with cURL and follow redirects
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
$redirect_count = curl_getinfo($ch, CURLINFO_REDIRECT_COUNT);

curl_close($ch);

echo "<h3>Response:</h3>";
echo "<p>HTTP Code: $http_code</p>";
echo "<p>Final URL: $final_url</p>";
echo "<p>Redirect Count: $redirect_count</p>";
echo "<pre>$response</pre>";
?>

