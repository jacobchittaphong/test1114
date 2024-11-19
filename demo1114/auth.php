<?php
// auth.php - Authentication functions

// Function to handle user registration
function register_user($pdo, $username, $password) {
    try {
        // First, check if the username already exists in the database
        $sql = "SELECT id FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $existing_user = $stmt->fetch();

        // If the username already exists, return false
        if ($existing_user) {
            return false; // Username already taken
        }

        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement for inserting a new user
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $pdo->prepare($sql);

        // Execute the query and return whether it was successful
        return $stmt->execute([
            'username' => $username,
            'password' => $hashed_password
        ]);
    } catch (PDOException $e) {
        // Log the error message for debugging purposes
        error_log("Database error: " . $e->getMessage());
        return false; // Return false in case of an error
    }
}

// Function to handle user login
function login_user($pdo, $username, $password) {
    // Prepare SQL query to get user by username
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Start session and store user info
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false; // Return false if login fails
}

// Check if a user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Log the user out by destroying the session
function logout_user() {
    $_SESSION = [];
    session_destroy();
}
?>
