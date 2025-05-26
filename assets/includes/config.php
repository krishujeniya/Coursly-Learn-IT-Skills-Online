<?php
session_start();

// Database configuration
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'students';


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// --- PHPMailer Setup (Moved here for global access if SendOTP is here) ---
require_once __DIR__ . '/Exception.php'; // Use __DIR__ for robust path
require_once __DIR__ . '/PHPMailer.php';
require_once __DIR__ . '/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// --- End PHPMailer Setup ---


// --- SendOTP Function (Moved here for global access) ---
function SendOTP($email_recipient_param, $conn_param = null) { // Added $conn_param if needed inside
    global $conn; // Use global $conn if not passed as param
    if ($conn_param) $conn = $conn_param; // Allow overriding global $conn

    $otp = mt_rand(100000, 999999);
    $myemail = ""; // Your sending email
    $myemailpass = ""; // Your email app password

    $to = $email_recipient_param;
    $subject = "Coursly - OTP Verification";
    $message =  "
    <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <img src='https://coursly-the-learning-platform.000webhostapp.com/assets/img/mylogob.png' alt='Coursly Logo' style='height: 50px;'>
            </div>
            <h2 style='color: #6a11cb;'>Welcome to Coursly!</h2>
            <p>Thank you for choosing Coursly. To secure your account, please use the One-Time Password (OTP) below:</p>
            <p style='text-align: center; font-size: 24px; font-weight: bold; color: #2575fc; margin: 20px 0; letter-spacing: 2px; padding: 10px; background-color: #f0f8ff; border-radius: 5px;'>
                Your OTP is: " . $otp . "
            </p>
            <p>This OTP is valid for a limited time. Do not share it.</p>
            <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
            <p>Visit us at <a href='https://coursly-the-learning-platform.000webhostapp.com' style='color: #00c6ff; text-decoration: none;'>coursly-the-learning-platform.000webhostapp.com</a>.</p>
            <p>Best regards,<br>The Coursly Team (AI DRAGO)</p>
        </div>
    </div>";

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $myemail;
        $mail->Password   = $myemailpass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom($myemail, 'Coursly Platform');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email_recipient'] = $email_recipient_param;
        $_SESSION['otp_pending_verification'] = true;
        unset($_SESSION['otp_verified']);
        header("location: otpVerificationPage.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['otp_send_error'] = 'Email could not be sent. Error: ' . $mail->ErrorInfo;
        // Determine where to redirect based on context if possible, or a generic error page
        if (basename($_SERVER['PHP_SELF']) == 'authenticationPage.php') {
             if(isset($_POST['login_email'])){ // Distinguish login from registration if form fields are present
                 header("location: loginPage.php");
             } else {
                 header("location: registrationPage.php");
             }
        } else {
             header("location: loginPage.php"); // Default fallback
        }
        exit();
    }
}
// --- End SendOTP Function ---


// Check current user status from database if session exists
if (isset($_SESSION['email'])) {
    $email_check_config = $_SESSION['email'];
    $sql_config = "SELECT * FROM r_users WHERE email = ?";
    $stmt_config = $conn->prepare($sql_config);
    $stmt_config->bind_param("s", $email_check_config);
    $stmt_config->execute();
    $result_config = $stmt_config->get_result();

    if ($result_config->num_rows > 0) {
        $row_config = $result_config->fetch_assoc();
        $_SESSION['email'] = $row_config["email"]; // Re-affirm from DB
        $_SESSION['username'] = $row_config["username"];
        // $_SESSION['avatar'] = $row_config["avatar"] ?? 'default-avatar.png'; // If you have an avatar column
    } else {
        // User in session not found in DB, clear session (security measure)
        session_unset();
        session_destroy();
        // No redirect here, let individual pages handle it if login is required
    }
    $stmt_config->close();
}

function printtest($conn, $questions, $answers, $options, $achievement, $recipientName, $issuedDate, $course, $email) {
    $total_questions = count($questions);
    $score = 0;

    // Security: Ensure $course and $email are safe for use in SQL if not already validated
    // $course is somewhat validated by the switch, $email comes from session.
    $safe_course_col = $conn->real_escape_string($course); // For column name - though whitelisting is better
    $safe_exam_col = "Exam" . $conn->real_escape_string($course);
    $safe_email = $conn->real_escape_string($email);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['answers'])) {
        $user_answers = $_POST['answers'];

        // Ensure $answers array exists and has entries for the submitted questions
        foreach ($user_answers as $index => $user_answer) {
            if (isset($answers[$index])) {
                $correct_answer = $answers[$index];
                if (strtolower(trim($user_answer)) == strtolower(trim($correct_answer))) {
                    $score++;
                }
            }
        }
    }

    echo '<div class="quiz-container">';
    echo '<h2 class="quiz-title">' . htmlspecialchars($achievement) . ' Assessment</h2>';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['answers'])) {
        if ($score >= 5) {
            echo '<div class="quiz-result success">';
            echo '<h3><i class="fas fa-trophy"></i> Congratulations!</h3>';
            echo '<p class="score">Your score: <span>' . htmlspecialchars($score) . '</span> out of 10</p>';
            echo '<a href="certificatePage.php?course=' . urlencode($achievement) . '&name=' . urlencode($recipientName) . '&date=' . urlencode($issuedDate) . '" class="btn btn-gradient-primary">View Certificate</a>';
            echo '</div>';

            // Check if $course is a valid pre-defined course ID to prevent SQL injection if it were less controlled
            // The switch statement in the main part of the script effectively whitelists $course
            $allowed_courses = ["CID1", "CID2", "CID3", "CID4", "CID5", "CID6"];
            if (in_array($course, $allowed_courses)) {
                // Use backticks for column names if they are dynamic and match known safe values
                $sql11 = "UPDATE r_certificates SET `$safe_course_col` = 'true' WHERE username = '$safe_email'";
                $sql12 = "UPDATE r_certificates SET `$safe_exam_col` = '" . $conn->real_escape_string($issuedDate) . "' WHERE username = '$safe_email'";
                
                // Check if a row exists, if not, insert one.
                $check_sql = "SELECT username FROM r_certificates WHERE username = '$safe_email'";
                $check_result = $conn->query($check_sql);
                if ($check_result && $check_result->num_rows == 0) {
                    // Insert a new row with default false values for other courses if necessary
                    // This is a simplified insert, ideally, you'd have a more robust way to handle new users
                    $insert_sql = "INSERT INTO r_certificates (username, `$safe_course_col`, `$safe_exam_col`) VALUES ('$safe_email', 'true', '".$conn->real_escape_string($issuedDate)."')";
                    $conn->query($insert_sql);
                } else {
                    $conn->query($sql11);
                    $conn->query($sql12);
                }
            }

        } else {
            echo '<div class="quiz-result fail">';
            echo '<h3><i class="fas fa-redo-alt"></i> Try Again</h3>';
            echo '<p class="score">Your score: <span>' . htmlspecialchars($score) . '</span> out of 10 (Minimum 5 required)</p>';
            echo '<a class="btn btn-gradient-secondary" href="videoPage.php?course=' . urlencode($course) . '">Retake Course</a>';
            echo '</div>';
        }
    } else {
        if ($questions[0] === "Course Not Found") {
             echo '<div class="alert alert-warning">' . htmlspecialchars($questions[0]) . '. ' . htmlspecialchars($options[0][0]) . '</div>';
        } else {
            echo '<form method="post" action="#assessment" class="quiz-form">'; // Action targets the assessment tab anchor
            echo '<ol class="question-list">';

            // Ensure we don't try to pick more questions than available
            $num_questions_to_pick = min(10, count($questions));
            if ($num_questions_to_pick > 0 && count($questions) >= $num_questions_to_pick) {
                 $question_indexes = (count($questions) > $num_questions_to_pick) ? array_rand($questions, $num_questions_to_pick) : array_keys($questions);
                 if (!is_array($question_indexes)) { // array_rand returns single key if num_req is 1
                    $question_indexes = [$question_indexes];
                 }

                foreach ($question_indexes as $index) {
                    $question = $questions[$index];
                    $question_options = $options[$index];

                    echo '<li class="question-item">';
                    echo '<div class="question-text">' . htmlspecialchars($question) . '</div>';
                    echo '<div class="options-container">';
                    foreach ($question_options as $option_key => $option) {
                        // Value should be the letter (A, B, C, D) which corresponds to 'a', 'b', 'c', 'd' in answers
                        $option_value = chr(65 + $option_key); // A, B, C, D
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input" type="radio" name="answers[' . $index . ']" id="q' . $index . '-' . md5($option) . '" value="' . strtolower($option_value) . '" required>';
                        echo '<label class="form-check-label" for="q' . $index . '-' . md5($option) . '">' . htmlspecialchars($option) . '</label>';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</li>';
                }
            } else {
                echo '<li>No questions available for this assessment.</li>';
            }

            echo '</ol>';
            if ($num_questions_to_pick > 0) {
                echo '<button type="submit" class="btn btn-gradient-primary">Submit Answers</button>';
            }
            echo '</form>';
        }
    }
    echo '</div>';
}

          
function getBaseHTML($title = 'Coursly e-Learning Platform', $description = 'Coursly is a comprehensive e-learning platform offering a wide range of online courses for learning success. Start your educational journey today!') {
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="title" content="'.$title.'">
        <meta name="description" content="'.$description.'">
        <meta name="keywords" content="Online courses, E-learning platform, Online education, Learning management system, Distance learning, Virtual classrooms, Online certifications, Skill development courses, Professional training, Online tutorials, Educational resources, Interactive learning, Personalized learning, Online workshops, Video lectures, Web-based learning, Online test preparation, Continuing education, Career advancement courses, Self-paced learning, Digital learning, Mobile learning, Online course marketplace, Affordable online education, Industry-specific courses, Online learning community, Online degree programs, Expert-led courses, Flexible learning options">
        
        <title>'.$title.'</title>
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link rel="stylesheet" href="assets/css/main.css">
        
        <!-- Favicon -->
        <link rel="icon" href="assets/img/mylogos.png" type="image/png">
        
        <!-- Google Tag Manager -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-7R0YHLW6YM"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag("js", new Date());
            gtag("config", "G-7R0YHLW6YM");
        </script>
    </head>
    <body class="dark-theme">';
    
    includeNavigation();
}

function includeNavigation() {
    echo '
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/img/mylogob.png" alt="Coursly Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutPage.php"><i class="fas fa-info-circle"></i> About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-graduation-cap"></i> Courses
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="videoPage.php?course=CID1"><i class="fas fa-bullhorn"></i> Digital Marketing</a></li>
                            <li><a class="dropdown-item" href="videoPage.php?course=CID2"><i class="fas fa-brain"></i> Machine Learning</a></li>
                            <li><a class="dropdown-item" href="videoPage.php?course=CID3"><i class="fas fa-code"></i> Full-Stack Development</a></li>
                            <li><a class="dropdown-item" href="videoPage.php?course=CID4"><i class="fas fa-database"></i> Data Science</a></li>
                            <li><a class="dropdown-item" href="videoPage.php?course=CID5"><i class="fas fa-gamepad"></i> Game Development</a></li>
                            <li><a class="dropdown-item" href="videoPage.php?course=CID6"><i class="fab fa-android"></i> Android Development</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">';
                    
    if (isset($_SESSION['username'])) {
        echo '<div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle user-profile" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="ms-2 d-none d-lg-inline">'.$_SESSION['username'].'</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    
                    <li><a class="dropdown-item" href="logoutPage.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
              </div>';
    } else {
        echo '<a href="loginPage.php" class="nav-link"><i class="fas fa-sign-in-alt"></i> Login</a>';
    }
    
    echo '          </li>
                </ul>
            </div>
        </div>
    </nav>';
}

function includeFooter() {
    echo '
    <footer class="footer bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <img src="assets/img/mylogob.png" alt="Coursly Logo" height="40">
                    </div>
                    <p class="footer-text">Your gateway to IT excellence through comprehensive e-learning courses and industry-recognized certifications.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="aboutPage.php">About Us</a></li>
                        <li><a href="index.php#courses">All Courses</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6"->
                    <h5 class="footer-heading">Courses</h5>
                    <ul class="footer-links">
                        <li><a href="videoPage.php?course=CID1">Digital Marketing</a></li>
                        <li><a href="videoPage.php?course=CID2">Machine Learning</a></li>
                        <li><a href="videoPage.php?course=CID3">Full-Stack Development</a></li>
                        <li><a href="videoPage.php?course=CID4">Data Science</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="footer-heading">Connect With Us</h5>
                    <div class="social-links">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                    <div class="newsletter mt-3">
                        <p>Subscribe to our newsletter</p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Your email" required>
                            <button type="submit"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="row">
                <div class="col-md-6">
                    <p class="copyright">&copy; 2030 Coursly. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="credits">Designed with <i class="fas fa-heart text-danger"></i> by AI DRAGO Team</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    
    </body>
    </html>';
}
?>
