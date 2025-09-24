<?php
// Check offers in database
$host = 'localhost';
$dbname = 'auxidb';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Recent Offers</h2>";
    $stmt = $pdo->query("SELECT * FROM offers ORDER BY created_at DESC LIMIT 5");
    $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($offers)) {
        echo "<p>No offers found in database.</p>";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Job ID</th><th>Cleaner ID</th><th>Offer Type</th><th>Amount</th><th>Status</th><th>Created</th></tr>";
        foreach ($offers as $offer) {
            echo "<tr>";
            echo "<td>" . $offer['id'] . "</td>";
            echo "<td>" . $offer['job_id'] . "</td>";
            echo "<td>" . $offer['cleaner_id'] . "</td>";
            echo "<td>" . $offer['offer_type'] . "</td>";
            echo "<td>" . $offer['amount'] . "</td>";
            echo "<td>" . $offer['status'] . "</td>";
            echo "<td>" . $offer['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

