<?php
// No session_start() here, config.php handles it.
include('assets/includes/config.php');

// Protection: If OTP is not pending, or essential details are missing, redirect.
if (!isset($_SESSION['otp_pending_verification']) || !$_SESSION['otp_pending_verification'] || !isset($_SESSION['otp']) || !isset($_SESSION['otp_email_recipient'])) {
    if(isset($_SESSION['username']) && (!isset($_SESSION['otp_verified']) || !$_SESSION['otp_verified'])) {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();
    }
    header("location: loginPage.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['otp_code'])) {
    $userOTP = trim($_POST['otp_code']);

    if ($userOTP == $_SESSION['otp']) {
        $_SESSION['otp_verified'] = true;
        unset($_SESSION['otp']);
        unset($_SESSION['otp_pending_verification']);
        // $_SESSION['otp_email_recipient'] remains for potential audit until next login/reg

        if (isset($_SESSION['pending_user_details'])) {
            $_SESSION['username'] = $_SESSION['pending_user_details']['username'];
            $_SESSION['email'] = $_SESSION['pending_user_details']['email'];
            $_SESSION['DOB'] = $_SESSION['pending_user_details']['DOB'];
            // $_SESSION['avatar'] = $_SESSION['pending_user_details']['avatar'] ?? 'default-avatar.png';
            unset($_SESSION['pending_user_details']);
        } elseif (isset($_SESSION['pending_registration_details'])) {
            $reg_data = $_SESSION['pending_registration_details'];
            
            $sql_insert_user = "INSERT INTO r_users (username, DOB, pass, email) VALUES (?, ?, ?, ?)";
            $stmt_user = $conn->prepare($sql_insert_user);
            $stmt_user->bind_param("ssss", $reg_data['username'], $reg_data['DOB'], $reg_data['password_md5'], $reg_data['email']);
            
            if ($stmt_user->execute()) {
                $allowed_courses_for_cert_init = ["CID1", "CID2", "CID3", "CID4", "CID5", "CID6"];
                $cert_cols = []; $cert_placeholders_qmarks = []; $cert_types = "s"; $cert_values = [$reg_data['email']];
                foreach($allowed_courses_for_cert_init as $c_id_init){
                    $cert_cols[] = "`$c_id_init`"; $cert_cols[] = "`Exam$c_id_init`";
                    $cert_placeholders_qmarks[] = "?"; $cert_placeholders_qmarks[] = "?";
                    $cert_types .= "ss"; 
                    $cert_values[] = 'false'; $cert_values[] = ''; // Default values
                }
                $sql_insert_cert = "INSERT INTO `r_certificates`(`username`, ".implode(", ", $cert_cols).") VALUES (?, ".implode(", ", $cert_placeholders_qmarks).")";
                $stmt_cert = $conn->prepare($sql_insert_cert);
                $stmt_cert->bind_param($cert_types, ...$cert_values);
                $stmt_cert->execute();
                $stmt_cert->close();

                $_SESSION['username'] = $reg_data['username'];
                $_SESSION['email'] = $reg_data['email'];
                $_SESSION['DOB'] = $reg_data['DOB'];
            } else {
                $_SESSION['otp_error'] = "Registration failed after OTP verification. Please try registering again. Error: " . $stmt_user->error;
                unset($_SESSION['pending_registration_details']);
                $stmt_user->close();
                header("location: registrationPage.php"); 
                exit();
            }
            $stmt_user->close();
            unset($_SESSION['pending_registration_details']);
        }

        header("location: index.php"); 
        exit();
    } else {
        $_SESSION['otp_error'] = "Invalid OTP entered. Please try again.";
        // Page will reload and show the error.
    }
}

getBaseHTML('Coursly - OTP Verification');
// The rest of the OTP page HTML (from previous response) follows...
?>
<style>
    .form-control-dark {
        background-color: var(--dark2-color) !important; color: var(--text-color) !important;
        border: 1px solid var(--gray-color) !important; text-align: center; 
        font-size: 1.5rem; letter-spacing: 0.5em;
    }
    .form-control-dark::placeholder { color: var(--text-muted) !important; opacity: 0.7 !important; font-size: 1rem; letter-spacing: normal; }
    .form-control-dark:focus { border-color: var(--accent-color) !important; box-shadow: 0 0 0 0.25rem rgba(0, 198, 255, 0.25) !important; }
    .error-message-custom { background-color: rgba(220, 53, 69, 0.1) !important; border: 1px solid rgba(220, 53, 69, 0.3) !important; color: #f8d7da !important; padding: 0.75rem 1.25rem; border-radius: 0.375rem; margin-bottom: 1rem; font-size: 0.9rem; }
    .info-message-custom { background-color: rgba(0, 198, 255, 0.1) !important; border: 1px solid rgba(0, 198, 255, 0.3) !important; color: var(--accent-color) !important; padding: 0.75rem 1.25rem; border-radius: 0.375rem; margin-bottom: 1rem; font-size: 0.9rem; }
</style>
<main>
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div style="background-color: var(--gray-color); padding: 2.5rem; border-radius: 10px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);">
                        <div class="text-center mb-4">
                            <img src="assets/img/mylogob.png" alt="Coursly Logo" height="50" class="mb-3">
                            <h2 style="color: var(--light-color); font-family: 'Montserrat', sans-serif; font-weight: 700;">Verify Your Identity</h2>
                            <p style="color: var(--text-muted);">An OTP has been sent to: <strong style="color: var(--accent-color);"><?php echo isset($_SESSION['otp_email_recipient']) ? htmlspecialchars($_SESSION['otp_email_recipient']) : 'your email'; ?></strong></p>
                        </div>
                        
                        <?php if (isset($_SESSION["otp_error"])): ?>
                            <div class="error-message-custom text-center"> <?= htmlspecialchars($_SESSION["otp_error"]) ?> </div>
                            <?php unset($_SESSION["otp_error"]); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION["otp_send_error"])): ?>
                            <div class="error-message-custom text-center"> <?= htmlspecialchars($_SESSION["otp_send_error"]) ?> </div>
                            <?php unset($_SESSION["otp_send_error"]); ?>
                        <?php endif; ?>
                  
                        <form action="otpVerificationPage.php" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="otp_code" class="form-label visually-hidden">Enter OTP:</label>
                                <input type="text" id="otp_code" name="otp_code" pattern="\d{6}" maxlength="6" class="form-control form-control-dark" required placeholder="------" title="Enter the 6-digit OTP" autocomplete="one-time-code">
                                <div class="invalid-feedback text-center">Please enter the 6-digit OTP.</div>
                            </div>
                            
                            <button type="submit" class="btn btn-gradient-primary w-100 py-2 mt-3">
                                <i class="fas fa-check-circle me-2"></i> Verify OTP
                            </button>
                            
                            <div class="text-center mt-4">
                                <p style="color: var(--text-muted); font-size: 0.9rem;">Didn't receive the OTP? 
                                    <a href="resendOtpPage.php">Resend OTP</a>
                                </p>
                                <a href="logoutPage.php" class="btn btn-sm btn-outline-light mt-2">
                                    <i class="fas fa-times-circle me-1"></i> Cancel & Logout
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
(function () { 'use strict'; const forms = document.querySelectorAll('.needs-validation'); Array.from(forms).forEach(form => { form.addEventListener('submit', event => { if (!form.checkValidity()) { event.preventDefault(); event.stopPropagation(); } form.classList.add('was-validated'); }, false); }); })();
document.getElementById('otp_code')?.focus();
</script>
<?php
includeFooter();
?>