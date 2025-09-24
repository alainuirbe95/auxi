<?php
// Debug offer submission
echo "<h2>Debug Offer Submission</h2>";
echo "<h3>POST Data:</h3>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<h3>GET Data:</h3>";
echo "<pre>";
print_r($_GET);
echo "</pre>";

echo "<h3>Request Method:</h3>";
echo $_SERVER['REQUEST_METHOD'];

echo "<h3>Request URI:</h3>";
echo $_SERVER['REQUEST_URI'];
?>

