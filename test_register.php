<?php
// Test registration with error logging
error_log("Starting registration test");

// Database connection
$host = "sql201.infinityfree.com";
$user = "if0_41338430";
$password = "uwlpRCnXtwZkc";
$database = "if0_41338430_financial_literacy";

error_log("Connecting to database: $host, user: $user, database: $database");

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

error_log("Database connected successfully");

// Test data
$name = "Test User " . date('Y-m-d H:i:s');
$email = "test" . time() . "@example.com";
$password = "test123";

error_log("Creating user: $name, $email");

// Check if user already exists
$checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    error_log("User already exists: $email");
    die(json_encode(["error" => "User already exists"]));
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Create user
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    $userId = $conn->insert_id;
    error_log("User created successfully with ID: $userId");
    
    echo json_encode([
        "success" => true,
        "message" => "User created successfully",
        "user_id" => $userId,
        "name" => $name,
        "email" => $email
    ]);
} else {
    error_log("Error creating user: " . $stmt->error);
    echo json_encode([
        "success" => false,
        "error" => "Error creating user: " . $stmt->error,
        "sql_error" => $conn->error
    ]);
}

$stmt->close();
$checkStmt->close();
$conn->close();
?>
