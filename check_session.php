<?php
session_start();
header('Content-Type: application/json');
 
echo json_encode([
    "isLoggedIn" => isset($_SESSION['user']),
    "user" => isset($_SESSION['user']) ? $_SESSION['user'] : null
]);
?> 