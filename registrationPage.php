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


getBaseHTML('Coursly - Create Your Account');
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
        box-shadow: 0 0 0 0.25rem rgba(var(--accent-color-rgb), 0.25) !important; 
    }
     /* Define --accent-color-rgb if not present, for focus ring. This is a helper. */
    :root {
        /* Example: if --accent-color is #00c6ff */
        --accent-color-rgb: 0, 198, 255; 
    }
    .error-message-custom {
        background-color: rgba(220, 53, 69, 0.1) !important; 
        border: 1px solid rgba(220, 53, 69, 0.3) !important; 
        color: #f8d7da !important; 
        padding: 0.75rem 1.25rem;
        border-radius: 0.375rem; 
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    .input-group-text-dark {
        background-color: var(--dark2-color) !important; /* Adjusted to dark2 for consistency with input */
        border: 1px solid var(--gray-color) !important;
        border-right: none; /* Remove right border if input has left border */
    }
    .form-control-dark.no-left-border { /* For inputs next to an icon group */
        border-left: none;
    }
    .input-group-text-dark i {
        color: var(--accent-color) !important;
        width: 1.25em; /* Ensure icons have consistent width */
        text-align: center;
    }
    #togglePasswordReg i { /* Style for the password toggle icon */
        color: var(--text-muted);
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
                            <h2 style="color: var(--light-color); font-family: 'Montserrat', sans-serif; font-weight: 700;">Create Your Account</h2>
                            <p style="color: var(--text-muted);">Join Coursly and start your learning journey!</p>
                        </div>
                        
                        <?php if (isset($_SESSION['login_error2'])): ?>
                            <div class="error-message-custom text-center">
                                <?= htmlspecialchars($_SESSION['login_error2']) ?>
                            </div>
                            <?php unset($_SESSION['login_error2']); ?>
                        <?php endif; ?>
                  
                        <form action="authenticationPage.php" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="register-username" class="form-label" style="color: var(--text-color);">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-dark">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" id="register-username" name="register_username" minlength="2" class="form-control form-control-dark no-left-border" required placeholder="e.g. johnsmith123">
                                    <div class="invalid-feedback">Please choose a username (minimum 2 characters).</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="register-DOB" class="form-label" style="color: var(--text-color);">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-dark">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <input type="text" id="register-DOB" name="register_DOB" pattern="\d{2}-\d{2}-\d{4}" class="form-control form-control-dark no-left-border" required placeholder="DD-MM-YYYY">
                                    <div class="invalid-feedback">Please enter your DOB in DD-MM-YYYY format.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="register-email" class="form-label" style="color: var(--text-color);">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-dark">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" id="register-email" name="register_email" class="form-control form-control-dark no-left-border" required placeholder="e.g. your@email.com">
                                    <div class="invalid-feedback">Please provide a valid email address.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="register-password" class="form-label" style="color: var(--text-color);">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-dark">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" id="register-password" name="register_password" minlength="8" class="form-control form-control-dark no-left-border" required placeholder="Minimum 8 characters">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordReg" style="border-color: var(--gray-color); background-color: var(--dark2-color); border-left: none;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">Password must be at least 8 characters long.</div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-gradient-primary w-100 py-2 mt-4">
                                <i class="fas fa-user-plus me-2"></i> Register
                            </button>
                            
                            <div class="text-center mt-4">
                                <p style="color: var(--text-muted);">Already have an account? 
                                    <a href="loginPage.php">Sign In</a>
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

// Optional: Simple DOB format hint/validation (auto-add hyphens)
const dobInput = document.getElementById('register-DOB');
if (dobInput) {
    dobInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); 
        let formattedValue = '';
        if (value.length > 0) {
            formattedValue += value.substring(0, 2);
        }
        if (value.length > 2) {
            formattedValue += '-' + value.substring(2, 4);
        }
        if (value.length > 4) {
            formattedValue += '-' + value.substring(4, 8);
        }
        e.target.value = formattedValue;
    });
}

// Toggle password visibility for registration
const togglePasswordReg = document.getElementById('togglePasswordReg');
const passwordReg = document.getElementById('register-password');

if (togglePasswordReg && passwordReg) {
    togglePasswordReg.addEventListener('click', function() {
        const icon = this.querySelector('i');
        const type = passwordReg.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordReg.setAttribute('type', type);
        
        if (type === 'text') {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            icon.style.color = 'var(--accent-color)';
        } else {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            icon.style.color = 'var(--text-muted)';
        }
    });
}
</script>

<?php
includeFooter();
?>