<?php
// Test form submission
echo "<h2>Test Form Submission</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h3>Server Info:</h3>";
    echo "<p>Request Method: " . $_SERVER['REQUEST_METHOD'] . "</p>";
    echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";
    echo "<p>Content Type: " . $_SERVER['CONTENT_TYPE'] . "</p>";
} else {
    echo "<h3>Test Form:</h3>";
    echo "<form method='POST' action='test_form_submission.php'>";
    echo "<input type='text' name='offer_type' value='counter' placeholder='Offer Type'><br><br>";
    echo "<input type='number' name='amount' value='150.00' placeholder='Amount'><br><br>";
    echo "<button type='submit'>Test Submit</button>";
    echo "</form>";
}
?>

