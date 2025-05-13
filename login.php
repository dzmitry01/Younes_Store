<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers for JSON response
header('Content-Type: application/json');

try {
    // Database connection
    $link = mysqli_connect('localhost', 'root', '', 'Base_Client');
    
    if (!$link) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }

    // Get and sanitize input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$email || !$password) {
        throw new Exception("Email and password are required");
    }

    // First, let's check the table structure
    $result = mysqli_query($link, "DESCRIBE CLIENT");
    if (!$result) {
        throw new Exception("Error checking table structure: " . mysqli_error($link));
    }

    // Prepare statement
    $stmt = $link->prepare("SELECT * FROM CLIENT WHERE Mail_Clt = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $link->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Check if password is hashed (assuming Mot_Clt is the password field)
        if (strlen($user['Mot_Clt']) === 60 && strpos($user['Mot_Clt'], '$2y$') === 0) {
            // Password is hashed, use password_verify
            if (password_verify($password, $user['Mot_Clt'])) {
                $_SESSION['user'] = $user;
                echo json_encode([
                    "success" => true,
                    "message" => "Welcome " . $user['No_Clt'] . " " . $user['Pno_Clt'] . "!"
                ]);
            } else {
                throw new Exception("Invalid password");
            }
        } else {
            // Password is not hashed, compare directly
            if ($password === $user['Mot_Clt']) {
                $_SESSION['user'] = $user;
                echo json_encode([
                    "success" => true,
                    "message" => "Welcome " . $user['No_Clt'] . " " . $user['Pno_Clt'] . "!"
                ]);
            } else {
                throw new Exception("Invalid password");
            }
        }
    } else {
        throw new Exception("Email not found");
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
} finally {
    if (isset($link)) {
        mysqli_close($link);
    }
}
?>