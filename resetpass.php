<?php
include_once 'db.php';
session_start();
 


 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = $_SESSION['reset_email'] ?? null;
 
   
    if (empty($password) || empty($confirm_password)) {
        die("Error: Both password fields are required.");
    }
 
    if ($password !== $confirm_password) {
        die("Error: Passwords do not match.");
    }
 
    if (empty($email)) {
        die("Error: Reset email session is missing.");
    }
 
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
 

    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $email);
 
    if ($stmt->execute()) {
        unset($_SESSION['reset_email']);
        echo "Password reset successful! <a href='../view/login.html'>Login here</a>";
    } else {
        echo "Error: Could not reset password.";
    }
 
    $stmt->close();
} else {
    echo "Invalid request.";
}
 
$conn->close();
?>