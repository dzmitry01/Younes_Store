<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set header to return JSON
header('Content-Type: application/json');

// Database connection details
$host = 'localhost';
$dbname = 'base_client';
$username = 'root';
$password = '';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if it's a POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $firstname = $_POST['Pno_Clt'] ?? '';
        $lastname = $_POST['No_Clt'] ?? '';
        $age = $_POST['Age_Clt'] ?? '';
        $wilaya = $_POST['Wi_Clt'] ?? '';
        $phone = $_POST['Tel_Clt'] ?? '';
        $email = $_POST['Mail_Clt'] ?? '';
        $address = $_POST['Adr_Clt'] ?? '';
        $password = $_POST['Mot_Clt'] ?? '';
        $confirmPassword = $_POST['confirmMot_Clt'] ?? '';
        $gender = $_POST['Sexe_Clt'] ?? '';

        // Validate required fields
        if (empty($firstname) || empty($lastname) || empty($age) || empty($wilaya) || 
            empty($phone) || empty($email) || empty($address) || empty($password) || 
            empty($confirmPassword) || empty($gender)) {
            echo json_encode([
                'success' => false,
                'message' => 'All fields are required'
            ]);
            exit;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid email format'
            ]);
            exit;
        }

        // Validate age
        if (!is_numeric($age) || $age < 18) {
            echo json_encode([
                'success' => false,
                'message' => 'Age must be at least 18'
            ]);
            exit;
        }

        // Validate password match
        if ($password !== $confirmPassword) {
            echo json_encode([
                'success' => false,
                'message' => 'Passwords do not match'
            ]);
            exit;
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM client WHERE Mail_Clt = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Email already registered'
            ]);
            exit;
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO client (Pno_Clt, No_Clt, Age_Clt, Wi_Clt, Tel_Clt, Mail_Clt, Adr_Clt, Mot_Clt, Sexe_Clt) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $firstname,
            $lastname,
            $age,
            $wilaya,
            $phone,
            $email,
            $address,
            $hashedPassword,
            $gender
        ]);

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful! Redirecting to login page...',
            'redirect' => 'login.html'
        ]);

    } else {
        // Not a POST request
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
    }

} catch(PDOException $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>