<?php
// Simple database connection test
require_once 'application/config/database.php';

// Test database connection
try {
    $mysqli = new mysqli(
        $db['default']['hostname'],
        $db['default']['username'],
        $db['default']['password'],
        $db['default']['database']
    );
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    
    echo "Database connection successful!\n";
    echo "Database: " . $db['default']['database'] . "\n";
    
    // Check users table structure
    $result = $mysqli->query("DESCRIBE users");
    if ($result) {
        echo "\nUsers table structure:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        echo "Error describing users table: " . $mysqli->error . "\n";
    }
    
    // Check if there are any users
    $result = $mysqli->query("SELECT COUNT(*) as user_count FROM users");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "\nTotal users: " . $row['user_count'] . "\n";
    }
    
    // Check for any users with auth_level 9 (admin)
    $result = $mysqli->query("SELECT user_id, username, email, auth_level, banned FROM users WHERE auth_level = 9 LIMIT 5");
    if ($result) {
        echo "\nAdmin users:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- ID: " . $row['user_id'] . ", Username: " . $row['username'] . ", Email: " . $row['email'] . ", Banned: " . $row['banned'] . "\n";
        }
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
