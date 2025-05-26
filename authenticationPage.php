<?php
// No session_start() here, config.php handles it.
include('assets/includes/config.php');

// Login Form
if (isset($_POST['login_password']) && isset($_POST['login_email'])) {
    unset($_SESSION['otp_verified']); 
    unset($_SESSION['otp_pending_verification']);

    // Use prepared statements to prevent SQL injection
    $email = $_POST['login_email'];
    $password_attempt = $_POST['login_password']; // We'll verify MD5 against stored MD5

    $sql = "SELECT * FROM r_users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify hashed password
        if (md5($password_attempt) === $row['pass']) {
            $_SESSION['pending_user_details'] = [
                'username' => $row["username"],
                'email' => $row['email'],
                'DOB' => $row['DOB']
                // 'avatar' => $row['avatar'] ?? 'default-avatar.png' // If you add avatar
            ];
            SendOTP($row['email'], $conn); // Pass $conn if SendOTP needs it and it's not global
        } else {
            $_SESSION['login_error'] = "Invalid email or password.";
            header("location: loginPage.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("location: loginPage.php");
        exit();
    }
    $stmt->close();
}


// Register Form
if (isset($_POST['register_username']) && isset($_POST['register_DOB']) && isset($_POST['register_password']) && isset($_POST['register_email'])) {
    unset($_SESSION['otp_verified']);
    unset($_SESSION['otp_pending_verification']);

    $username = $_POST['register_username'];
    $DOB = $_POST['register_DOB']; // Validate this format server-side too
    $password_plain = $_POST['register_password'];
    $password_md5 = md5($password_plain); 
    $email = $_POST['register_email'];

    // Validate inputs (e.g., email format, password length, DOB format)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_error2'] = "Invalid email format.";
        header("location: registrationPage.php");
        exit();
    }
    if (strlen($password_plain) < 8) {
        $_SESSION['login_error2'] = "Password must be at least 8 characters.";
        header("location: registrationPage.php");
        exit();
    }
    // Add more validation as needed

    $checkSql = "SELECT email FROM r_users WHERE email = ?";
    $stmt_check = $conn->prepare($checkSql);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $checkResult = $stmt_check->get_result();
    
    if ($checkResult->num_rows > 0) {
        $_SESSION['login_error2'] = "This email address is already registered.";
        header("location: registrationPage.php");
        exit();
    }
    $stmt_check->close();

    $_SESSION['pending_registration_details'] = [
        'username' => $username,
        'DOB' => $DOB,
        'password_md5' => $password_md5,
        'email' => $email
    ];
    SendOTP($email, $conn); // Pass $conn if SendOTP needs it
}

// If neither login nor registration form was submitted, redirect or show error
if (!isset($_POST['login_email']) && !isset($_POST['register_email'])) {
    header("Location: loginPage.php"); // Or an error page
    exit();
}
?>