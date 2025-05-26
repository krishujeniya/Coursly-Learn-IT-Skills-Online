<?php
// Ensure session is started if your config.php doesn't handle it globally
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
include('assets/includes/config.php'); // Should handle DB connection $conn

// Sanitize GET parameters
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8') : 'Recipient Name';
$date = isset($_GET['date']) ? htmlspecialchars($_GET['date'], ENT_QUOTES, 'UTF-8') : 'Issue Date';
$course = isset($_GET['course']) ? htmlspecialchars($_GET['course'], ENT_QUOTES, 'UTF-8') : 'Course Name';

// A unique ID could be generated/fetched if you store certificate records
$certificate_id = "CN-" . date("Ymd") . "-" . substr(md5($name . $course . $date), 0, 6);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="title" content="Certificate of Achievement">
        <meta name="description" content="Certificate of Achievement">
        <meta name="keywords" content="Online courses, E-learning platform, Online education, Learning management system, Distance learning, Virtual classrooms, Online certifications, Skill development courses, Professional training, Online tutorials, Educational resources, Interactive learning, Personalized learning, Online workshops, Video lectures, Web-based learning, Online test preparation, Continuing education, Career advancement courses, Self-paced learning, Digital learning, Mobile learning, Online course marketplace, Affordable online education, Industry-specific courses, Online learning community, Online degree programs, Expert-led courses, Flexible learning options">
        
        <title>Certificate of Achievement</title>
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link rel="stylesheet" href="assets/css/main.css">
        
        <link rel="stylesheet" href="assets/css/certificate-style.css">

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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Open+Sans:wght@400;600&family=Playfair+Display:wght@700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/certificate-style.css">
</head>
<body>
    <div class="certificate-page-container">
        <div class="certificate-wrapper">
            <div class="certificate-border">
                <div class="certificate-content">

                    <header class="certificate-header">
                        <img src="assets/img/mylogob.png" alt="Coursly Logo" class="logo">
                        <h1 class="main-title">Certificate of Achievement</h1>
                    </header>

                    <section class="certificate-body">
                        <p class="presented-to">This certificate is proudly presented to</p>
                        <h2 class="recipient-name"><?= $name ?></h2>
                        <p class="achievement-text">
                            for successfully completing the course requirements and demonstrating proficiency in
                        </p>
                        <h3 class="course-name"><?= $course ?></h3>
                        <p class="completion-date">
                            Awarded on this day, <?= $date ?>
                        </p>
                    </section>

                    <footer class="certificate-footer">
                        <div class="signatures-container">
                            <div class="signature-block">
                                <img src="assets/img/28s.png" alt="Signature of Krish Ujeniya" class="signature-img">
                                <hr class="signature-line">
                                <p class="signer-name">Mr. Krish Ujeniya</p>
                                <p class="signer-title">CEO of Coursly</p>
                            </div>
                            
                            <div class="signature-block">
                                <img src="assets/img/38sign.png" alt="Signature of Zeel Makwana" class="signature-img">
                                <hr class="signature-line">
                                <p class="signer-name">Mr. Zeel Makwana</p>
                                <p class="signer-title">President of Coursly</p>
                            </div>
                        </div>
                        <p class="certificate-id">Certificate ID: <?= $certificate_id ?></p>
                        <p class="issuing-authority">Issued by Coursly Inc.</p>
                    </footer>

                </div>
            </div>
        </div>

        <div class="certificate-actions">
            <button onclick="window.print()" class="btn btn-gradient-primary">
                <i class="fas fa-print"></i> Print Certificate
            </button>
            <a href="index.php" class="btn btn-outline-light">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>

    <!-- FontAwesome for icons - if not already in your main.css setup -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>