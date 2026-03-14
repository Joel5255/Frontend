<?php
// Test InfinityFree database connection
echo "Testing InfinityFree database connection...\n";

// InfinityFree MySQL connection
$host = "sql201.infinityfree.com";
$user = "if0_41338430";
$password = "uwlpRCnXtwZkc";
$database = "if0_41338430_financial_literacy";

echo "Connecting to: $host as $user\n";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error . "\n";
    echo "Check your InfinityFree MySQL credentials and remote access settings\n";
} else {
    echo "✓ Connection successful!\n";
    
    // Check if users table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'users'");
    if ($tableCheck->num_rows > 0) {
        echo "✓ Users table exists\n";
        
        // Count users
        $count = $conn->query("SELECT COUNT(*) as count FROM users");
        $row = $count->fetch_assoc();
        echo "Current users count: " . $row['count'] . "\n";
        
        // Show table structure
        echo "\nUsers table structure:\n";
        $structure = $conn->query("DESCRIBE users");
        while ($col = $structure->fetch_assoc()) {
            echo "- " . $col['Field'] . " (" . $col['Type'] . ")\n";
        }
    } else {
        echo "✗ Users table does not exist\n";
        echo "Available tables:\n";
        $tables = $conn->query("SHOW TABLES");
        while ($table = $tables->fetch_assoc()) {
            echo "- " . implode(", ", $table) . "\n";
        }
    }
}

$conn->close();
?>
