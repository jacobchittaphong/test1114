<?php
// config.php - Database configuration

// Database connection settings
$host = 'localhost'; 
$dbname = 'golf_inventory'; // Make sure this matches your database name
$user = 'jcac1'; 
$pass = 'jacob'; // Avoid using real passwords in code (use environment variables in production)
$charset = 'utf8mb4';

// Data Source Name (DSN) for connecting to MySQL
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// PDO options for error handling and data fetching
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions for error handling
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch data as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false, // Disable emulated prepares for better security
];

try {
    // Establish the database connection
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Log error details for debugging (but avoid exposing this info publicly)
    error_log("Database connection failed: " . $e->getMessage());
    // Show a generic message to the user without revealing sensitive information
    die("Database connection failed. Please try again later.");
}

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute the SQL query to create the table
try {
    $pdo->exec($sql);
} catch (PDOException $e) {
    // Log error details if table creation fails
    error_log("Table creation failed: " . $e->getMessage());
    die("An error occurred while setting up the database. Please try again later.");
}
