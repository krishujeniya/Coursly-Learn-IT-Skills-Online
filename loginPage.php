<?php
include('assets/includes/config.php');

// Redirect logged-in users IF OTP IS ALREADY VERIFIED
if (isset($_SESSION['username']) && isset($_SESSION['otp_verified']) && $_SESSION['otp_verified']) {
    header("Location: index.php");
    exit();
} elseif (isset($_SESSION['username']) && isset($_SESSION['otp_pending_verification']) && $_SESSION['otp_pending_verification']) {
    // If logged in but OTP is pending, send to OTP page
    header("Location: otpVerificationPage.php");
    exit();
}


getBaseHTML('Coursly - Login to Your Account');

?>

<style>
    /* Additional styles to make inputs and placeholders fit the dark theme using CSS variables */
    .form-control-dark {
        background-color: var(--dark2-color) !important;
        color: var(--text-color) !important;
        border: 1px solid var(--gray-color) !important;
    }
    .form-control-dark::placeholder {
        color: var(--text-muted) !important;
        opacity: 0.7 !important;
    }
    .form-control-dark:focus {
        border-color: var(--accent-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(var(--accent-color-rgb), 0.25) !important; /* Assuming --accent-color-rgb is defined or use a fixed one */
        /* If --accent-color-rgb is not available, you might need to hardcode the RGB or use a simpler shadow */
        /* For example: box-shadow: 0 0 5px var(--accent-color) !important; */
    }
    .input-group-text-dark {
        background-color: var(--dark-color) !important;
        border: 1px solid var(--gray-color) !important;
    }
    .input-group-text-dark i {
        color: var(--accent-color) !important;
    }
    /* Define --accent-color-rgb if not present, for focus ring. This is a helper. */
    :root {
        /* Example: if --accent-color is #00c6ff */
        --accent-color-rgb: 0, 198, 255; 
    }
</style>

<main>
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div style="background-color: var(--gray-color); padding: 2.5rem; border-radius: 10px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);">
                        <div class="text-center mb-4">
                            <img src="assets/img/mylogob.png" alt="Coursly Logo" height="50" class="mb-3">
                            <h2 style="color: var(--light-color); font-family: 'Montserrat', sans-serif; font-weight: 700;">Welcome Back!</h2>
                            <p style="color: var(--text-muted);">Sign in to access your courses.</p>
                            
                            <?php if (isset($_SESSION['login_error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert" style="background-color: rgba(220, 53, 69, 0.1); border-color: rgba(220, 53, 69, 0.3); color: #f8d7da;">
                                    <?= htmlspecialchars($_SESSION['login_error']) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION['login_error']); ?>
                            <?php endif; ?>
                        </div>
                        
                        <form action="authenticationPage.php" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="login-email" class="form-label" style="color: var(--text-color);">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-dark">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control form-control-dark" 
                                           id="login-email" name="login_email" 
                                           placeholder="your@email.com" required>
                                    <div class="invalid-feedback">
                                        Please provide a valid email address.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="login-password" class="form-label" style="color: var(--text-color);">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-dark">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control form-control-dark" 
                                           id="login-password" name="login_password" 
                                           placeholder="Enter your password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-color: var(--gray-color); background-color: var(--dark2-color);">
                                        <i class="fas fa-eye" style="color: var(--text-muted);"></i>
                                    </button>
                                    <div class="invalid-feedback">
                                        Please enter your password.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" style="background-color: var(--dark2-color); border-color: var(--gray-color);">
                                    <label class="form-check-label" for="remember-me" style="color: var(--text-muted);">Remember me</label>
                                </div>
                                <a href="forgot-password.php">Forgot password?</a>
                            </div>
                            
                            <button type="submit" class="btn btn-gradient-primary w-100 py-2">
                                <i class="fas fa-sign-in-alt me-2"></i> Sign In
                            </button>
                            
                            <div class="text-center mt-4">
                                <p style="color: var(--text-muted);">Don't have an account? 
                                    <a href="registrationPage.php">Create one</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// Form validation
(function () {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})();
    
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('login-password');
    const icon = this.querySelector('i');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
        icon.style.color = 'var(--accent-color)';
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
        icon.style.color = 'var(--text-muted)';
    }
});
</script>

<?php
includeFooter();
?>