<?php
// No session_start() here, config.php handles it.
include('assets/includes/config.php');

if (!isset($_SESSION['otp_pending_verification']) || !$_SESSION['otp_pending_verification'] || !isset($_SESSION['otp_email_recipient'])) {
    header("Location: loginPage.php");
    exit();
}

// The SendOTP function is now in config.php and should be available.
// It handles the redirection to otpVerificationPage.php itself.
SendOTP($_SESSION['otp_email_recipient'], $conn); // Pass $conn if SendOTP function expects it

// Fallback if SendOTP somehow doesn't redirect (should not happen if SendOTP has exit())
exit(); 
?>