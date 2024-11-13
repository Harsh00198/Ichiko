<?php
include 'connect.php';

// Function to sanitize inputs
function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

// Function to handle database queries
function execute_query($conn, $query, $types, ...$params) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt;
}

// Sign-Up Process
if (isset($_POST['signUp'])) {
    $firstName = sanitize_input($_POST['fName']);
    $lastName = sanitize_input($_POST['lName']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Check if email exists
    $checkEmail = "SELECT * FROM users WHERE email=? limit 1";
    $stmt = execute_query($conn, $checkEmail, "s", $email);
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertQuery = "INSERT INTO users(firstName, lastName, email, password) VALUES (?, ?, ?, ?)";
        $stmt = execute_query($conn, $insertQuery, "ssss", $firstName, $lastName, $email, $hashedPassword);

        if ($stmt->affected_rows > 0) {
            header("Location: index.php");
            exit();
        } else {
            error_log("Database error: " . $conn->error);
            echo "An error occurred. Please try again later.";
        }
    }
}

// Sign-In Process
if (isset($_POST['signIn'])) {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password']; // Keep the plain password

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    $query = "SELECT * FROM users WHERE email=?";
    $stmt = execute_query($conn, $query, "s", $email);
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Log the stored hashed password for debugging
        error_log("Stored hashed password: " . $row['password']);

        // Password Verification
        if (password_verify($password, $row['password'])) { // Use plain password here
            session_start();
            session_regenerate_id(true);
            $_SESSION['email'] = $row['email'];
            $_SESSION['users_id'] = $row['id'];
            header("Location: index.php");
            exit();
        } else {
            header("Location: index.html");
        }
    } else {
        echo "Incorrect email or password";
    }
}
?>