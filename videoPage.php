<?php
// It's crucial that session_start() is called.
// If 'config.php' doesn't do it, add it here.
// session_start(); // Uncomment if not in config.php

include('assets/includes/config.php'); // Assumed to handle DB connection ($conn) and possibly session_start()

// <<<< START OF STEP 3 OTP PROTECTION BLOCK >>>>
if (!isset($_SESSION['username'])) {
    header("Location: loginPage.php");
    exit();
} elseif (!isset($_SESSION['otp_verified']) || !$_SESSION['otp_verified']) {
    $_SESSION['otp_pending_verification'] = true;
    if (isset($_SESSION['email'])) {
         $_SESSION['otp_email_recipient'] = $_SESSION['email'];
    } else {
        session_unset(); session_destroy();
        header("Location: loginPage.php"); exit();
    }
    header("Location: otpVerificationPage.php");
    exit();
}
// <<<< END OF STEP 3 OTP PROTECTION BLOCK >>>>

$recipientName = $_SESSION['username'];
$issuedDate = date("F j, Y");
$achievement = "Unknown";
$ytlink = "https://www.youtube.com/embed/VIDEOID";
$course = isset($_GET['course']) ? $_GET['course'] : '';
$email = $_SESSION['email'];

$questions = $answers = $options = [];

switch ($course) {
    case "CID1":
        $achievement = "Digital Marketing";
        $ytlink = "https://www.youtube.com/embed/G6DmDqYLWL8";
        $questions = [
            "What does SEO stand for?", "Which of these is primarily a social media marketing platform?", "What is 'A/B testing' in digital marketing?", "What does 'CTR' stand for in online advertising?", "What is the main goal of content marketing?", "Which of the following is an example of 'paid search' advertising?", "What is a 'landing page' designed for?", "What does 'ROI' stand for in a marketing context?", "Email marketing involves sending messages primarily to:", "What is 'affiliate marketing'?"
        ];
        $answers = ["a", "c", "b", "d", "a", "c", "b", "a", "d", "b"];
        $options = [
            ["A) Search Engine Optimization", "B) Social Engagement Optimization", "C) Site Engine Output", "D) Search Email Organization"],
            ["A) Google Analytics", "B) WordPress", "C) Instagram", "D) Salesforce"],
            ["A) Testing two different products against each other", "B) Comparing two versions of a webpage or ad to see which performs better", "C) Running ads on channel A and channel B simultaneously", "D) Authenticating users with two-factor authentication"],
            ["A) Cost To Run", "B) Conversion Tracking Rate", "C) Customer Targeting Resource", "D) Click-Through Rate"],
            ["A) Attracting and retaining a clearly defined audience by creating valuable content", "B) Directly selling products through aggressive advertising", "C) Optimizing website speed", "D) Only posting on social media"],
            ["A) Writing a blog post optimized for search engines", "B) Getting a link from another reputable website", "C) Ads appearing on Google search results pages (e.g., Google Ads)", "D) Sharing content on social media"],
            ["A) Displaying a company's entire product catalog", "B) A specific marketing or advertising campaign goal, like lead generation", "C) The main homepage of a website", "D) Providing customer support chat"],
            ["A) Return On Investment", "B) Rate Of Interaction", "C) Reach On Internet", "D) Revenue Over Invoices"],
            ["A) Randomly generated email addresses", "B) Competitors' employees", "C) Only new potential customers", "D) A list of subscribers who have opted-in"],
            ["A) Marketing through celebrity endorsements", "B) Earning a commission by promoting other people's (or company's) products", "C) Creating viral video content", "D) A type of email marketing"]
        ];
        break;
    case "CID2":
        $achievement = "Machine Learning";
        $ytlink = "https://www.youtube.com/embed/NWONeJKn6kc";
        $questions = [
            "Which type of machine learning uses labeled data to train models?", "What is 'overfitting' in machine learning?", "K-Means is an example of which type of algorithm?", "What is the primary purpose of a 'test set' in machine learning?", "Which of these is a common metric for evaluating classification models?", "What does a 'decision tree' use to make predictions?", "What is 'feature engineering'?", "Reinforcement learning is primarily about:", "What is 'Natural Language Processing (NLP)' a subfield of?", "Which task involves predicting a continuous value (e.g., house price)?"
        ];
        $answers = ["a", "c", "b", "d", "a", "c", "b", "d", "a", "b"];
        $options = [
            ["A) Supervised learning", "B) Unsupervised learning", "C) Reinforcement learning", "D) Semi-supervised learning"],
            ["A) The model performs poorly on training data", "B) The model is too simple to capture data patterns", "C) The model performs well on training data but poorly on new, unseen data", "D) The model uses too few features"],
            ["A) Classification", "B) Clustering", "C) Regression", "D) Dimensionality Reduction"],
            ["A) To train the model", "B) To select the best features", "C) To fine-tune hyperparameters", "D) To evaluate the model's performance on unseen data"],
            ["A) Accuracy", "B) Mean Squared Error", "C) R-squared", "D) Silhouette Score"],
            ["A) Complex mathematical equations", "B) Neural networks", "C) A series of if-else rules or questions", "D) Random chance"],
            ["A) Selecting the machine learning algorithm", "B) Creating new input features from existing data or transforming them", "C) Deploying the model to production", "D) Visualizing the model's predictions"],
            ["A) Grouping similar data points", "B) Learning from labeled examples", "C) Finding hidden patterns in data", "D) Training agents to make a sequence of decisions to maximize a reward"],
            ["A) Artificial Intelligence", "B) Database Management", "C) Network Security", "D) Quantum Computing"],
            ["A) Classification", "B) Regression", "C) Clustering", "D) Anomaly Detection"]
        ];
        break;
    case "CID3":
        $achievement = "Full-Stack Development";
        $ytlink = "https://www.youtube.com/embed/nu_pCVPKzTk";
        $questions = [
            "Which language is primarily used for styling web pages?", "What does HTML stand for?", "Which of these is a popular JavaScript framework/library for front-end development?", "What is the role of a 'database' in a web application?", "Node.js allows developers to:", "What is an API (Application Programming Interface)?", "Which HTTP method is typically used to retrieve data from a server?", "What is 'version control' (e.g., Git) used for?", "In the context of web development, 'responsive design' means:", "Which of these is primarily a back-end programming language?"
        ];
        $answers = ["b", "d", "a", "c", "b", "d", "a", "c", "b", "d"];
        $options = [
            ["A) HTML", "B) CSS", "C) JavaScript", "D) Python"],
            ["A) Hyperlink Text Management Language", "B) High-Level Textual Markup Language", "C) Home Tool Markup Language", "D) HyperText Markup Language"],
            ["A) React", "B) Django", "C) Spring", "D) Laravel"],
            ["A) To define the visual layout of web pages", "B) To add interactivity to buttons and forms", "C) To store, retrieve, and manage application data", "D) To compile JavaScript code"],
            ["A) Design user interfaces directly in the browser", "B) Run JavaScript code on the server-side", "C) Manage and query SQL databases only", "D) Create 3D graphics for web games"],
            ["A) A visual design tool for web pages", "B) A type of web server software", "C) A styling language similar to CSS", "D) A set of rules and protocols for building and interacting with software applications"],
            ["A) GET", "B) POST", "C) PUT", "D) DELETE"],
            ["A) Optimizing website loading speed", "B) Testing website security", "C) Tracking and managing changes to code over time", "D) Automatically deploying applications"],
            ["A) The website responds quickly to user clicks", "B) The website's layout adapts to different screen sizes and devices", "C) The server sends responses rapidly", "D) The website uses AI to respond to users"],
            ["A) HTML", "B) CSS", "C) JavaScript (in browser)", "D) Python (with Django/Flask)"]
        ];
        break;
    case "CID4":
        $achievement = "Data Science";
        $ytlink = "https://www.youtube.com/embed/aGu0fbkHhek";
        $questions = [
            "What is the primary goal of Exploratory Data Analysis (EDA)?", "Which Python library is commonly used for numerical computations and array manipulations in data science?", "In statistics, what does a 'p-value' help determine?", "What is 'data cleaning' or 'data preprocessing'?", "Which type of chart is best for showing the distribution of a single numerical variable?", "What is 'dimensionality reduction'?", "If a model has 'high bias', it generally means:", "What is the main difference between 'correlation' and 'causation'?", "Which of these is a common technique for handling missing data?", "What does a 'confusion matrix' help visualize in a classification problem?"
        ];
        $answers = ["c", "a", "d", "b", "a", "c", "b", "d", "a", "b"];
        $options = [
            ["A) To build predictive models immediately", "B) To deploy models to production", "C) To understand the data's main characteristics, often with visual methods", "D) To write the final project report"],
            ["A) NumPy", "B) Matplotlib", "C) Scikit-learn", "D) TensorFlow"],
            ["A) The accuracy of a predictive model", "B) The size of the dataset", "C) The correlation between two variables", "D) The statistical significance of an observed result, often against a null hypothesis"],
            ["A) Visualizing data using charts and graphs", "B) Preparing raw data for analysis by handling errors, missing values, and inconsistencies", "C) Training a machine learning model", "D) Selecting the most important features"],
            ["A) Histogram", "B) Scatter plot", "C) Line chart", "D) Pie chart"],
            ["A) Increasing the number of features in a dataset", "B) Converting categorical data to numerical data", "C) Reducing the number of input variables (features) while preserving important information", "D) Splitting data into training and testing sets"],
            ["A) The model is too complex and overfits the data", "B) The model is too simple and fails to capture the underlying patterns in data (underfitting)", "C) The model performs perfectly on the test set", "D) The model has too many features"],
            ["A) They are the same concept", "B) Causation implies correlation, but not vice-versa", "C) Correlation implies causation, but not vice-versa", "D) Correlation indicates a relationship, while causation means one event causes another"],
            ["A) Imputation (e.g., replacing with mean, median, or mode)", "B) Ignoring all rows with missing data, regardless of how many", "C) Adding more features to compensate", "D) Always using a complex model that can handle NaNs natively"],
            ["A) The distribution of feature values", "B) The performance, showing true positives, true negatives, false positives, and false negatives", "C) The learning curve of the model", "D) The relationship between two numerical variables"]
        ];
        break;
    case "CID5":
        $achievement = "Game Development";
        $ytlink = "https://www.youtube.com/embed/gB1F9G0JXOo";
        $questions = [
            "What is a 'game engine'?", "Which term describes the visual elements of a game, like characters and environments?", "What is 'level design' in game development?", "In 3D games, what does a 'mesh' typically represent?", "What is 'collision detection' in a game?", "Which of these is a popular game engine?", "What does 'FPS' commonly refer to in gaming performance?", "What is the role of a 'gameplay programmer'?", "What is 'procedural generation' in game development?", "What is 'playtesting' primarily used for?"
        ];
        $answers = ["b", "d", "a", "c", "b", "a", "d", "c", "b", "a"];
        $options = [
            ["A) The computer hardware that runs the game", "B) A software framework designed for the creation and development of video games", "C) The marketing plan for a game", "D) A type of game controller"],
            ["A) Game mechanics", "B) AI scripts", "C) Sound effects", "D) Assets (or art assets)"],
            ["A) Creating the environments, challenges, and experiences within a game's stages", "B) Writing the main story and dialogue for the game", "C) Optimizing the game's code for performance", "D) Marketing the game to different regions"],
            ["A) The game's background music", "B) A piece of text dialogue", "C) The collection of vertices, edges, and faces that define the shape of a 3D object", "D) A saved game state"],
            ["A) Detecting when the player is losing", "B) Determining when two or more game objects intersect or touch", "C) Finding bugs in the game's code", "D) The process of compiling game code"],
            ["A) Unity", "B) Photoshop", "C) Blender", "D) Audacity"],
            ["A) Fun Playable Segments", "B) Final Polish Stage", "C) Fictional Player Story", "D) Frames Per Second"],
            ["A) Designing the user interface", "B) Composing the game's music", "C) Implementing the rules, interactions, and systems that define how the game is played", "D) Creating 3D models for characters"],
            ["A) A method of testing game procedures", "B) Creating game content algorithmically rather than manually", "C) The legal procedures for publishing a game", "D) A specific genre of simulation games"],
            ["A) Gathering feedback on gameplay, finding bugs, and assessing player experience", "B) Promoting the game on social media", "C) Selling the game to publishers", "D) Translating the game into different languages"]
        ];
        break;
    case "CID6":
        $achievement = "Master In Android";
        $ytlink = "https://www.youtube.com/embed/mXjZQX3UzOs";
        $questions = [
            "What is an 'Activity' in Android development?", "Which file defines the app's structure, components, and permissions?", "What is 'Gradle' primarily used for in Android projects?", "Which layout allows you to arrange UI elements in rows and columns, similar to a table?", "What is an 'Intent' used for in Android?", "For storing small amounts of primitive data in key-value pairs, which Android storage option is most suitable?", "What is the purpose of `RecyclerView`?", "Which component is used to perform long-running operations in the background without a UI?", "What is the primary programming language officially supported for Android development by Google (besides Java)?", "What is Logcat used for in Android Studio?"
        ];
        $answers = ["a", "c", "b", "d", "a", "c", "b", "d", "a", "b"];
        $options = [
            ["A) A single screen with a user interface", "B) A background process that runs indefinitely", "C) A tool for compiling code", "D) A database management system"],
            ["A) build.gradle", "B) strings.xml", "C) AndroidManifest.xml", "D) MainActivity.java"],
            ["A) Designing UI layouts", "B) Automating the build process and managing dependencies", "C) Debugging Java code", "D) Storing user data"],
            ["A) LinearLayout", "B) RelativeLayout", "C) FrameLayout", "D) GridLayout (or TableLayout for older apps)"],
            ["A) To start activities, services, or deliver broadcasts (message passing)", "B) To define the visual appearance of a UI element", "C) To store large datasets locally", "D) To handle network requests"],
            ["A) SQLite Database", "B) Room Persistence Library", "C) SharedPreferences", "D) Internal/External Storage for files"],
            ["A) To draw complex 2D graphics", "B) To efficiently display large sets of data that can be scrolled", "C) To handle user input from the touchscreen", "D) To play audio and video content"],
            ["A) Activity", "B) Fragment", "C) ContentProvider", "D) Service"],
            ["A) Kotlin", "B) Swift", "C) C#", "D) Python"],
            ["A) To design user interface layouts", "B) To view system messages and app-specific log outputs for debugging", "C) To manage virtual devices (emulators)", "D) To compile the Android application package (APK)"]
        ];
        break;
    default:
        $questions = ["Course Not Found"];
        $answers = ["a"]; // Dummy answer
        $options = [["A) Please select a valid course from the courses page."]]; // More informative message
        $achievement = "Course Not Found";
        $ytlink = "https://www.youtube.com/embed/dQw4w9WgXcQ"; // A neutral placeholder
        break;
}



getBaseHTML(htmlspecialchars($achievement) . ' Course | Coursly');

// START HTML OUTPUT
?>

<main class="course-page">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="course-header mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="index.php#courses">Courses</a></li>
                            <li class="breadcrumb-item " aria-current="page"><?= htmlspecialchars($achievement) ?></li>
                        </ol>
                    </nav>
                    <h1 class="course-title"><?= htmlspecialchars($achievement) ?> Course</h1>
                    <div class="course-meta">
                        <span class="badge bg-primary">
                            <?= htmlspecialchars($course == 'CID1' ? 'Marketing' : ($course == 'CID3' ? 'WEB' : ($course == 'CID2' || $course == 'CID4' || $course == 'CID6' ? 'Tech' : 'General'))) ?>
                        </span>
                        <span><i class="far fa-clock"></i> <?= htmlspecialchars($course == 'CID3' ? '10 weeks' : '6-8 weeks') ?></span>
                        <span><i class="fas fa-signal"></i> <?= htmlspecialchars($course == 'CID5' ? 'Advanced' : ($course == 'CID3' ? 'Beginner' : 'Intermediate')) ?></span>
                    </div>
                </div>

                <!-- Video -->
                <?php if ($achievement !== "Course Not Found"): ?>
                <div class="course-video-container mb-5">
                    <div class="ratio ratio-16x9">
                        <iframe src="<?= htmlspecialchars($ytlink) ?>" title="<?= htmlspecialchars($achievement) ?> Video Lesson" allowfullscreen></iframe>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Tabs -->
                <div class="course-tabs">
                    <ul class="nav nav-tabs" id="courseTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab" aria-controls="about" aria-selected="true">About</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="curriculum-tab" data-bs-toggle="tab" data-bs-target="#curriculum" type="button" role="tab" aria-controls="curriculum" aria-selected="false">Curriculum</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="assessment-tab" data-bs-toggle="tab" data-bs-target="#assessment" type="button" role="tab" aria-controls="assessment" aria-selected="false">Assessment</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="courseTabContent">
                        <!-- About Tab -->
                        <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="about-tab">
                            <?php if ($achievement === "Course Not Found"): ?>
                                <h3 class="tab-title">Course Information</h3>
                                <p>The course you are looking for (ID: <?= htmlspecialchars($course) ?>) could not be found. Please check the course ID or select a course from our <a href="courses.php">courses page</a>.</p>
                            <?php else: ?>
                                <h3 class="tab-title">Course Description</h3>
                                <p>This comprehensive <?= htmlspecialchars($achievement) ?> course will take you from fundamentals to advanced topics with hands-on projects.</p>
                                <h4 class="mt-4">What You'll Learn</h4>
                                <ul class="learning-list">
                                    <li><i class="fas fa-check-circle"></i> Core concepts and principles of <?= htmlspecialchars($achievement) ?></li>
                                    <li><i class="fas fa-check-circle"></i> Industry-standard tools and technologies</li>
                                    <li><i class="fas fa-check-circle"></i> Practical applications and case studies</li>
                                    <li><i class="fas fa-check-circle"></i> Best practices and optimization techniques</li>
                                    <li><i class="fas fa-check-circle"></i> Portfolio-worthy project development</li>
                                </ul>
                                <h4 class="mt-4">Prerequisites</h4>
                                <p><?= htmlspecialchars($course == 'CID5' ? 'Basic programming knowledge recommended' : ($course == 'CID3' ? 'No prior experience required' : 'Basic understanding of programming concepts recommended')) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Curriculum Tab -->
                        <div class="tab-pane fade" id="curriculum" role="tabpanel" aria-labelledby="curriculum-tab">
                            <?php if ($achievement === "Course Not Found"): ?>
                                <p>Curriculum information is unavailable as the course was not found.</p>
                            <?php else: ?>
                            <h3 class="tab-title">Course Curriculum</h3>
                            <div class="accordion" id="curriculumAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Module 1: Introduction to <?= htmlspecialchars($achievement) ?></button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#curriculumAccordion">
                                        <div class="accordion-body">
                                            <ul class="lesson-list">
                                                <li><i class="far fa-play-circle"></i> Course Overview</li>
                                                <li><i class="far fa-play-circle"></i> Key Concepts</li>
                                                <li><i class="far fa-play-circle"></i> Industry Applications</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Module 2: Core Principles</button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#curriculumAccordion">
                                        <div class="accordion-body">
                                            <ul class="lesson-list">
                                                <li><i class="far fa-play-circle"></i> Fundamental Techniques</li>
                                                <li><i class="far fa-play-circle"></i> Tools & Frameworks</li>
                                                <li><i class="far fa-play-circle"></i> Workflows</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Module 3: Advanced Topics</button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#curriculumAccordion">
                                        <div class="accordion-body">
                                            <ul class="lesson-list">
                                                <li><i class="far fa-play-circle"></i> Optimization Techniques</li>
                                                <li><i class="far fa-play-circle"></i> Performance Tuning</li>
                                                <li><i class="far fa-play-circle"></i> Emerging Trends</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Assessment Tab -->
                        <div class="tab-pane fade" id="assessment" role="tabpanel" aria-labelledby="assessment-tab">
                            <div class="assessment-container">
                                <?php
                                // Use $conn->real_escape_string for all string values going into SQL
                                $safe_email_check = $conn->real_escape_string($email);
                                $safe_course_check = $conn->real_escape_string($course); // $course is from GET, then switch-cased

                                $sql_check_certificate = "SELECT * FROM r_certificates WHERE username = '$safe_email_check'";
                                $result_check_certificate = $conn->query($sql_check_certificate);
                                $certificate_already_earned = false;
                                $row_certificate_status = null; // Initialize

                                if ($result_check_certificate && $result_check_certificate->num_rows > 0) {
                                    $row_certificate_status = $result_check_certificate->fetch_assoc();
                                    // Check if the specific course column exists and indicates completion
                                    if (isset($row_certificate_status[$safe_course_check]) && $row_certificate_status[$safe_course_check] != "false" && $row_certificate_status[$safe_course_check] != "") {
                                        $certificate_already_earned = true;
                                        $examKey = "Exam" . $safe_course_check;
                                        $earnedDate = isset($row_certificate_status[$examKey]) ? $row_certificate_status[$examKey] : 'an unknown date';
                                        
                                        echo '
                                        <div class="certificate-earned mb-4">
                                            <div class="certificate-badge"><i class="fas fa-certificate"></i></div>
                                            <h3>Certificate Previously Earned!</h3>
                                            <p>You earned the certificate for <strong>' . htmlspecialchars($achievement) . '</strong> on ' . htmlspecialchars($earnedDate) . '.</p>
                                            <a href="certificatePage.php?course=' . urlencode($achievement) . '&name=' . urlencode($recipientName) . '&date=' . urlencode($earnedDate) . '" class="btn btn-gradient-primary">View Certificate</a>
                                            <p class="mt-2">You can retake the assessment below. Passing again will update your completion date.</p>
                                        </div>';
                                    }
                                }

                                // Now, always call printtest to display the assessment form or its results,
                                // but only if the course itself is valid (not "Course Not Found").
                                if ($achievement !== "Course Not Found") {
                                    printtest($conn, $questions, $answers, $options, $achievement, $recipientName, $issuedDate, $course, $email);
                                } else {
                                    // If the course was "Course Not Found" from the start, printtest will show its own message.
                                    // This handles the case where the $course GET parameter was invalid.
                                    printtest($conn, $questions, $answers, $options, $achievement, $recipientName, $issuedDate, $course, $email);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="course-sidebar">
                    <?php if ($achievement !== "Course Not Found"): ?>
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">Course Progress</h4>
                        <div class="progress mb-3" role="progressbar" aria-label="Course progress" aria-valuenow="<?= ($certificate_already_earned ? '100' : '75') ?>" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-gradient-primary" style="width: <?= ($certificate_already_earned ? '100' : '75') ?>%"></div>
                        </div>
                        <p><?= ($certificate_already_earned ? 'Completed' : 'In Progress (Complete assessment to finish)') ?></p>
                         <?php if ($certificate_already_earned && isset($earnedDate)): ?>
                            <p><small>Completed on: <?= htmlspecialchars($earnedDate) ?></small></p>
                        <?php endif; ?>
                    </div>

                    <div class="sidebar-card">
                        <h4 class="sidebar-title">Instructor</h4>
                        <div class="instructor-card">
                            <?php
                            $instructors = [
                                "CID1" => ["Sarah Johnson", "Digital Marketing Expert", "10+ years in digital marketing with Fortune 500 companies", "sarah.jpg"],
                                "CID2" => ["Dr. Michael Chen", "AI Researcher", "PhD in CS focused on Machine Learning", "michael.jpg"],
                                "CID3" => ["David Rodriguez", "Senior Developer", "Full-stack developer with 8+ years of experience", ""],
                                "CID4" => ["Emily Wilson", "Data Scientist", "Data science consultant for major firms", "emily.jpg"],
                                "CID5" => ["Alex Thompson", "Game Developer", "Lead developer on multiple AAA games", "alex.jpg"],
                                "CID6" => ["Priya Patel", "Android Engineer", "Published Android developer with 5M+ downloads", "priya.jpg"]
                            ];
                            // Provide a default instructor if $course is not in $instructors
                            $default_instructor = ["Site Admin", "Course Facilitator", "Expert in this field.", "default.jpg"];
                            $instructor = $instructors[$course] ?? $default_instructor;
                            $instructor_image = file_exists("assets/img/instructors/" . $instructor[3]) ? "assets/img/instructors/" . $instructor[3] : "assets/img/instructors/default.jpg";
                            ?>
                            <img src="<?= htmlspecialchars($instructor_image) ?>" class="instructor-avatar" alt="<?= htmlspecialchars($instructor[0]) ?>">
                            <h5 class="instructor-name"><?= htmlspecialchars($instructor[0]) ?></h5>
                            <p class="instructor-title"><?= htmlspecialchars($instructor[1]) ?></p>
                            <p class="instructor-bio"><?= htmlspecialchars($instructor[2]) ?></p>
                        </div>
                    </div>
                    <?php else: ?>
                     <div class="sidebar-card">
                        <h4 class="sidebar-title">Information</h4>
                        <p>Course details are not available because the selected course was not found.</p>
                        <a href="courses.php" class="btn btn-primary">Browse Courses</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php includeFooter(); ?>