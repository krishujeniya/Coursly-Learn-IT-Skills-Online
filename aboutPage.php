<?php 
include('assets/includes/config.php');

getBaseHTML('Coursly - Learn IT Skills Online');

?>

<main class="pt-5">
    <section class="about-hero py-5 bg-black-gradient">
        <div class="container text-center py-4">
            <h1 class="display-4 fw-bold mb-4 brand-text">About Coursly</h1>
            <p class="lead mb-4">Empowering learners with cutting-edge tech education</p>
            <div class="hero-divider mx-auto"></div>
        </div>
    </section>

    <section class="about-content py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="about-image-container border-neon p-3 rounded">
                        <img src="assets/img/mylogob.png" alt="Coursly Learning Platform" class="img-fluid rounded">
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="section-title mb-4">Our Mission</h2>
                    <p class="lead">Coursly is revolutionizing online education by providing high-quality, accessible tech courses to learners worldwide.</p>
                    <p>We believe that education should be engaging, practical, and tailored to the needs of the modern workforce. Our platform combines expert instruction with hands-on projects to deliver real-world skills.</p>
                    
                    <div class="mission-stats mt-5">
                        <div class="row g-4 text-center">
                            <div class="col-md-4">
                                <div class="stat-card bg-dark-gradient p-3 rounded border-neon">
                                    <h3 class="stat-number text-neon">50,000+</h3>
                                    <p class="stat-label">Students</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card bg-dark-gradient p-3 rounded border-neon">
                                    <h3 class="stat-number text-neon">100+</h3>
                                    <p class="stat-label">Courses</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card bg-dark-gradient p-3 rounded border-neon">
                                    <h3 class="stat-number text-neon">95%</h3>
                                    <p class="stat-label">Satisfaction</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-section py-5 bg-dark-gradient">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Meet the AI DRAGO Team</h2>
                <p class="section-subtitle">The brilliant minds behind Coursly</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="team-card bg-black p-4 rounded text-center border-neon">
                        <div class="team-img-container mx-auto mb-4">
                            <img src="data:image/jpeg;base64,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" alt="Krish Ujeniya" class="img-fluid rounded-circle">
                        </div>
                        <h3 class="team-name">Krish Ujeniya</h3>
                        <p class="team-position text-neon">CEO & Founder</p>
                        <p class="team-bio">Visionary leader with 10+ years in edtech innovation</p>
                        <div class="team-social mt-3">
                            <a href="#" class="text-white mx-2"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-white mx-2"><i class="fab fa-github"></i></a>
                            <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="team-card bg-black p-4 rounded text-center border-neon">
                        <div class="team-img-container mx-auto mb-4">
                            <img src="https://i.ibb.co/d0nrQ0xr/download-imresizer.jpg" alt="Zeel Makwana" class="img-fluid rounded-circle">
                        </div>
                        <h3 class="team-name">Zeel Makwana</h3>
                        <p class="team-position text-neon">President</p>
                        <p class="team-bio">Education specialist with a passion for accessible learning</p>
                        <div class="team-social mt-3">
                            <a href="#" class="text-white mx-2"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-white mx-2"><i class="fab fa-github"></i></a>
                            <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="values-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Our Core Values</h2>
                <p class="section-subtitle">What drives us every day</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="value-card bg-dark-gradient p-4 rounded border-neon h-100">
                        <div class="value-icon mb-3 text-neon">
                            <i class="fas fa-graduation-cap fa-3x"></i>
                        </div>
                        <h3 class="value-title">Quality Education</h3>
                        <p class="value-description">We deliver courses created by industry experts with real-world relevance.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="value-card bg-dark-gradient p-4 rounded border-neon h-100">
                        <div class="value-icon mb-3 text-neon">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                        <h3 class="value-title">Student Focus</h3>
                        <p class="value-description">Your learning journey is our top priority with personalized support.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="value-card bg-dark-gradient p-4 rounded border-neon h-100">
                        <div class="value-icon mb-3 text-neon">
                            <i class="fas fa-lightbulb fa-3x"></i>
                        </div>
                        <h3 class="value-title">Innovation</h3>
                        <p class="value-description">We constantly evolve our platform with cutting-edge technology.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
</main>

<?php
includeFooter();
?>