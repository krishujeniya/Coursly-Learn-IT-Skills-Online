<?php


// --- OTP Check for users who might have an active session but haven't verified OTP ---
// This is optional for a public index page, but good if you want to ensure
// any active session proceeds to OTP if needed.
if (isset($_SESSION['username']) && // User is known
    isset($_SESSION['otp_pending_verification']) && $_SESSION['otp_pending_verification'] && // OTP is pending
    (!isset($_SESSION['otp_verified']) || !$_SESSION['otp_verified']) // And OTP is NOT verified
   ) {
    // Redirect to OTP page if they are in a pending OTP state
    header("Location: otpVerificationPage.php");
    exit();
}
// --- End Optional OTP Check for Index ---


error_reporting(E_ALL);
ini_set('display_errors', 1);
include('assets/includes/config.php');

$selectedGroup = $_GET['group'] ?? 'ALL';

$cards = array(
    array(
        'group' => 'Marketing',
        'image' => 'dm.jpg',
        'course' => 'CID1',
        'title' => 'Digital Marketing',
        'description' => 'Master online marketing strategies, SEO, and social media marketing',
        'duration' => '6 weeks',
        'level' => 'Beginner'
    ),
    array(
        'group' => 'AI-ML',
        'image' => 'ml.jpg',
        'course' => 'CID2',
        'title' => 'Machine Learning',
        'description' => 'Learn algorithms, neural networks, and AI model deployment',
        'duration' => '8 weeks',
        'level' => 'Intermediate'
    ),
    array(
        'group' => 'WEB',
        'image' => 'fs.jpg',
        'course' => 'CID3',
        'title' => 'Full-Stack Development',
        'description' => 'Become proficient in both frontend and backend technologies',
        'duration' => '10 weeks',
        'level' => 'Beginner'
    ),
    array(
        'group' => 'AI-ML',
        'image' => 'ds.jpg',
        'course' => 'CID4',
        'title' => 'Data Science',
        'description' => 'Data analysis, visualization, and predictive modeling',
        'duration' => '8 weeks',
        'level' => 'Intermediate'
    ),
    array(
        'group' => 'AI-ML',
        'image' => 'gd.jpg',
        'course' => 'CID5',
        'title' => 'Game Development',
        'description' => 'Create interactive games using modern game engines',
        'duration' => '6 weeks',
        'level' => 'Advanced'
    ),
    array(
        'group' => 'AI-ML',
        'image' => 'ad.jpg',
        'course' => 'CID6',
        'title' => 'Android Development',
        'description' => 'Build mobile apps for the Android platform',
        'duration' => '6 weeks',
        'level' => 'Beginner'
    )
);

$groups = array_unique(array_column($cards, 'group'));

if ($selectedGroup && $selectedGroup !== 'ALL') {
    $filteredCards = array_filter($cards, function($card) use ($selectedGroup) {
        return $card['group'] === $selectedGroup;
    });
} else {
    $filteredCards = $cards;
}

getBaseHTML('Coursly - Learn IT Skills Online');

echo '
<main>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">Advance Your IT Career with Coursly</h1>
                    <p class="hero-subtitle">Learn in-demand tech skills from industry experts and earn recognized certifications</p>
                    <div class="hero-cta">
                        <a href="#courses" class="btn btn-gradient-primary btn-lg me-3">Explore Courses</a>
                        <a href="aboutPage.php" class="btn btn-outline-light btn-lg">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <img src="https://user-images.githubusercontent.com/74038190/229223263-cf2e4b07-2615-4f87-9c38-e37600f8381a.gif" alt="Online Learning" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section bg-dark2">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Why Choose Coursly</h2>
                <p class="section-subtitle">Our platform offers the best learning experience for tech enthusiasts</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-gradient-blue">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h3 class="feature-title">Hands-on Projects</h3>
                        <p class="feature-text">Apply what you learn with real-world projects and build a portfolio.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-gradient-purple">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h3 class="feature-title">Certification</h3>
                        <p class="feature-text">Earn industry-recognized certificates to showcase your skills.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-gradient-teal">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h3 class="feature-title">Expert Instructors</h3>
                        <p class="feature-text">Learn from professionals with years of industry experience.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section id="courses" class="courses-section pb-5">
        <div class="container-fluid px-lg-5">
            <div class="section-header">
                <h2 class="section-title">Our Popular Courses</h2>
                <p class="section-subtitle">Browse through our comprehensive course catalog</p>
            </div>
            
            <div class="course-filter mb-4 d-flex flex-wrap gap-2 justify-content-center">
                <a href="?group=ALL" class="btn btn-filter '.($selectedGroup === 'ALL' ? 'active' : '').'">All Courses</a>';
                
foreach ($groups as $group) {
    echo '<a href="?group='.$group.'" class="btn btn-filter '.($selectedGroup === $group ? 'active' : '').'">'.$group.'</a>';
}

echo '      </div>
            
            <div class="row g-4 justify-content-center">';

foreach ($filteredCards as $card) {
    echo '
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="course-card h-100">
                        <div class="course-badge position-absolute end-0 m-2">'.$card['level'].'</div>
                        <div class="course-image">
                            <img src="assets/img/'.$card['image'].'" alt="'.$card['title'].'" class="img-fluid w-100">
                        </div>
                        <div class="course-body">
                            <div class="course-category text-uppercase small mb-2">'.$card['group'].'</div>
                            <h3 class="course-title fs-5">'.$card['title'].'</h3>
                            <p class="course-description small">'.$card['description'].'</p>
                            <div class="course-meta d-flex justify-content-between small mt-auto">
                                <span><i class="far fa-clock me-1"></i>'.$card['duration'].'</span>
                                <span><i class="fas fa-signal me-1"></i>'.$card['level'].'</span>
                            </div>
                        </div>
                        <div class="course-footer mt-2">
                            <a href="videoPage.php?course='.$card['course'].'" class="btn btn-gradient-primary btn-sm w-100">Start Learning</a>
                        </div>
                    </div>
                </div>';
}

echo '      </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section bg-dark2">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">What Our Students Say</h2>
                <p class="section-subtitle">Success stories from our alumni community</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Coursly helped me transition from marketing to a tech career. The Machine Learning course gave me the skills I needed to land my dream job."</p>
                        <div class="testimonial-author">
                            <img src="data:image/jpeg;base64,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" alt="Sarah Johnson" class="testimonial-avatar">
                            <div>
                                <h5>Sarah Johnson</h5>
                                <p>Data Scientist at TechCorp</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="testimonial-text">"The Full-Stack Development course was comprehensive and well-structured. I built several projects that impressed my current employer."</p>
                        <div class="testimonial-author">
                            <img src="data:image/jpeg;base64,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" alt="Michael Chen" class="testimonial-avatar">
                            <div>
                                <h5>Michael Chen</h5>
                                <p>Full-Stack Developer at WebSolutions</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"As a working professional, the flexible schedule was perfect. The Digital Marketing certification helped me get a promotion."</p>
                        <div class="testimonial-author">
                            <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxITEhUSEhMVFhUVGBUWFRUVFxcWFRkdFxgXFxcVFRUYHSggGBolHRgWIjEhJSkrLi8uFx8zODMsNygtLisBCgoKDg0OGhAQGi0gHiUtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0rKy0tLS0tLS0tLS0tLSstLS0tLS0tLf/AABEIAOEA4QMBIgACEQEDEQH/xAAcAAEAAQUBAQAAAAAAAAAAAAAAAwIEBQYHAQj/xABFEAACAQIDBAYFCQQJBQAAAAAAAQIDEQQhMQUSQVEGE2FxgZEHIqGx8DJCUnKCssHR4SMzwvEUJDRDU2JzkqIVNWOz4v/EABkBAQADAQEAAAAAAAAAAAAAAAABAgQDBf/EACMRAQEAAgICAgIDAQAAAAAAAAABAhEDIRIxBEFRYTJC8CL/2gAMAwEAAhEDEQA/AN3ABKAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADW9tdMsNQbin1k8/Vjp4yegS2QHMKvpFxDv+zpxT0tvNpdreV/A8pekTER+VCMux5e1EbifGuoA0bAekenLKrRlDtjJTXjkmbHs7pHhq2UJ58pJx+9a42jTLAAlAAAAAAAAAAAAAAAAAAAAAAAAAAAB5KSSu8kj05x6RukUnN4Sk7RVnUkr3vruXXgRamTa76WdNYxTpYd9kqnfwh+ZoEXvu745/rz8xQwrm+L5/yMgsA0lZM55ZO2OFQ08Mlw8b3KKuFjyfldfoZKlh5crX55eWZdUdlVJO6fnn7zlctO0w21x4bldfHcS4atOD7uOvx+htUdjStZqL9n5lpitjZdpE5oXh3Gc6NdM7+pVs7cU81bi09fjw3mE00mtHmjhWKw0qcr8uK1y4pnSvR/tpVqToyl69Phx3efb/I045bZcsdNtABZQAAAAAAAAAAAAAAAAAAAAAAABRXqbsZS+im/JXOFT3qtW7zlKT3nxbbbdjte252w9V6eo/yOU7HpRlWW6stf5nPkuo68c3W39HNiQhFNq7ZsdLZ8PoryLfAQskZOnKx5vlbd16NmuogWzKa+avI8eFS4IualV8iic7jaFrWoKxg8bQtczlWT5GMxgizUdp4eLTujF9HMR/R8RTrXe6pbs7cnlJPwafgbHjYXTNWxNLdm8+5c+/l+ps4b9MvyMft21MFlsWtv0KUlxhHXsVn7i9NTEAAAAAAAAAAAAAAAAAAAAAAAAs9s097D1Vzpz9zOb9Fae9Vt7OzVfidRrU96Lj9JNeasc36PVowqzlLJLeV83o0uHgcOffj078H8m8U47qL/AA8crs1zE7T3040PWmnuvik+Ta0fYzXts4XH01vzrOKv9OnfPs3zJhw2+2vLkn06W1EoionLtndJK0PlSqTXP1ZeyMmzYq/SijCgpzqKMpSUY6u7avZtZRy1va3EteO76iJlNd1tGMnCKu2kjXto4/DtNKpG/fc1ipjpY5fs6VSabtvP1IX5JvNvsSZiFKnCUo9TZxtvN1KrWdmm1uLLNeaLzi63Yi8mvVbGqqate5gukEErT7Un4HlOrVWcFTlyV5RT7pWa8ywr7VdaDUobjjK2t9L34cC2GFl3EcmcuOq6x0RbeDot67r+9IzBh+ilo4ShF2T3PkvJ5tvTxMwa2EAAQAAAAAAAAAAAAAAAAAAAAALPFYqUalOCV99u+drWV8vZ5mq7T2FGliElOe5WcpSi7LNXe7dcONja8XH9pRlynbwkpJ+3dLbpNhX1lOpwjvK3K9s7mLlysyvbfxY4+OOp/tsdsDZ6p0ZRWqq1Xnr603JN9u64kWOwSlGSl85p72W8mmmt18M0ZGEKi9emlKM0t+De67pZSi7W0smnyRTiMRVirvDysuKnT/GRWZ33E+E9VquA2HaVoxe5dyblq29c3/IuNn9F6OIw9StVi5OpOpKndu0YpuMZQtpe29fX1kXfXYnFRlHD0+qi04urUccr5NwjG+8/JGz4HCdXRVKKyjFQXJKKSXuFyv5W8Z+HPeiFecIdXF+tSc4Ncbp634XVn4k9bZKe9u0lFyylbj2MlxGx6lKvKvQnFSfyqU/kyssndZxfbZl/sjG4rEQ3o06EbOzTnNu67N1FssrfVMcZPpjMHsvdilbs+Owws6EHGGScXUc1l9OUpL7xtO09n4mz360Yp6qlBxlZ6pTlJ270r8mjCVaMW1HgtPDQSos2vKsJRnR3ZOL3tE8rJZt8zopo9Chv16HKz+9FP2XN4O/D9uHyfqAAOzKAAAAAAAAAAAAAAAAAAAAAI66drpXcWpJfVadvG1vEh25XU4ZLk/dky6LfaUXKD3Vd209uvgZOfjtu418HJNaq22dK0UQ9I6zmo0Y/P+U1witfy8RhH6ifIt9r0pu0oPN2XuMuPtqQ1qMnFRpuUElb1W1p3FKxGJpxcU966ycrt+fEsMFiMQ8R1NdOnBp2qRTlF9l9F4mzPZKcMsTZNrgk8/HU7zGoyyk9tGrbOqObqVHeXPl2IzGzcaqdt1Wa1XMo6Q4Pq4yVGpUqzWiSVr71rN6LLPUw+ycBVqOPWv1rp2jos9L8WTlOu0S/htG2sWpU7rjoapxNn2zh7KNKPCyZi8Dszra3V3ccm7rO1s1lxV7FOOW9Iysk2vtiQlKrTvpFZe9+5G3FhsvZqpLN7z0ulZW82X5s48bjO2PmzmeXQADo4gAAAAAAAAAAAAAAAAAAAAAAAMKn1dSVN6POPcydO63fI923h7w318qGfhxRjqGM0fmedzYeOT0OLPyxZadK9nx7C1x2NjGycfYXWHqqSumeVKKepGOVjr5MFiqzlHditfAo2TT6uV33mSxlGyNb2hjLPJ5t2J3ci5MvicWrOb8O896H096dSq+CUV4u79y8zXMViLpQ8zZuh+0KVnhr2qpdZuvLei8t6POzVnyyO/Dj2zc16bKADUxgAAAAAAAAAAAAAAAAAAAAAAAABjMb0hwlK+/XpprVJ70v9sbsC9xbShLeaStxNWxWHlTl2cGU4jpFTxlahhqKnuOoqlWUlZSjSTq7qWtnu8baGw0IxrUoT4SjF+auZPkzuVs+NerGuUq9Sm7wzXFP8CZ9IJJZxfvMo9mkb2YuRnlaLpruO2vVnkotLyMUqDT3pu75cjasTgbcCwhs9yeh0lUsYvCYVykY3pVVnh6+HqU5btSEHJPjnL1b801fLtZ0TY+zI3d1dRSc3w7Id71fZ3o5b00xnW4urK+Se5Hujk/bvGrgx68mbmy/q6r0Y2/TxdLfjZTjZVIXzi+a5xfB+GqMwfPmErzhJShKUZLSUW4vzWZsWz+muOpu3W9YuVSKl/yVpe07acHYQc+wnpGl/e0E+2Emv+Mk/eZnC9PMJL5XWU/rRuvODY1UNoBjsLt3C1MoV6bfJyUX5SszIogAAAAAAAAAAAALXaO0aVCO/WnGEeF3m+yK1b7EBdBu2bOf7X9IuscNT+3U/CC/F+Bp20ttYjEP9rVlJfRvaHhBZewaTp1faPSrB0U96tGT+jT9eXd6uS8WjTdq+kWtLLDwjTX0pevPvt8le00tlLJ0nS92htjEVv3tac+xv1fCK9Uio03x8vzKMMot9pPOaJG0ejXD7+OXZSrNeMdz+I2D0X7T63DOlJ+tTnUj22cm4+V7eBifRDnj32UKn36S/EbKovAbTxNF5RlNzhycaj3427r2+yzh8jHeLtw3/rTos6WZTKmTUqykkyaTVjFpp2xFegrZlvhcO5y3aazfHgubLnHRcmoxV75WWpndnYBUYW+c/lP8EdOLjud/SvJyTCfthukFSOEwk3D5sZNN6yk+L7W7HBK+t3nfO/4nXvSri7YeNPjKSb7l8ew5RKB6WtTUYd77WsUe6ST55fl+JJKFsz2FNt55LW3ECaKKxbI9AMnwmPq0v3dScPqyaXksmRJHoGw4Ppvi4fKcai/zxz842M9gPSDSdlWpTg+cXvx8sn7Gc/aKZLMag7fg8VCrBVKclKL0a0/R9hMcn9H+2HSxSpN/s63qtcFL5ku/5v2lyOsFLNIAAQAAAxfSba/9Fw86tk5ZRgno5S0v2LN+BxzaG0atee/Wm5y0u9F2JLJLsRvfpTrZUKfBupN/ZUYr7zOfqJaRMUJEiiEiWxKUTRS4EzieJZgUxorxFiZIhmwN59EMf67J8qE/bOl+RtnpM2M5RhjKa9ej6tS2rpvO/wBl590pGh9AtrrC1q9WSct3DVJqK1bjOklG/C7kjP7E21tSvV6yrNdVL+5jFRglyWV39psXDymiZeN2yHRza2/CzM1165mD2hSw2HrLdo4iPWbrtT3eqW82srxyzWaT4qxllhMnKF5JJuV1aySvrxMGXx+Sd66bJzYVd1qb3GotqpNO0k2nG6ys1o/jvs9mbTrRvCblUV7JPOSs2rb3HTj7DIYzEU40nVcluNXjbV/rfIt9g0JdXvuNnN5c0n8e09DCY44yRjytttrQPSViZOuoSTVrOPararxbNPsbt6UnavRh9Gm5P7crZ/7DSnEVClwKdFckcHwYlHOK8X4frbyAkUT3cK4LMWA8SsUtFTQkvwJFEiKT1fgSVXxIpZRQFoqjjJNOzTTT4prNM7tsbHqvQp1l8+KbXJ6SXg00cGkszovor2reNTCyfyf2lPubSml47r+0yuSHQAAUAAAc79J871qMeUJP/dL/AOTSoo2f0i1b4y30YU153l/EaxfMvPSyqKK1EjRJED2xGTX1IZMCpsilqVyZRLUDMdHKTliIrnFwknpJSyafkn3pHddn4OEYJJLRHD+ir/rEH9U7tgs4om3pUrYaLTTV09UW1SKpxyV1bR6Lu/IvJRktGYXbW16dOcKdT51r2Xb8Za5CFWtLYrquLnZQi7xpxVoq+d7c8zYo0kklyK6UotJxd00mmuT0BFqXF/STV3sfUX0I04f8VL+JmsRMt0pxHWYzEz51ZxXdB7iflFGKJgIUldt+C8NfaJuyv5ElKFlblr+IHp5UlmJFE3oSPUevQ8RVN5AW1d8O08xfA8ecort9yuKubYFq1kX3Rvaf9HxNKrwjK0vqy9WV/B370iyqIgIH0Nvx+kvMHBv+r1/8WfmwV8UO9gAqOQdNql8dX7JQXlTgjB1Hmn4GR6QVt/FV5f8AlqeSk0vcYytpflmXWSokgyOOhWgJGyOZI0RyQFCZSyqWRSBsPRGF8TFd3vO54RWijiPQhf1ymjudOOQqCTLHG7Io1rOrDe3W2ndrwunp2GQmsgkJRHGKSSSslZJL3FNaooxlJ6RTb8FcrWph+mOI6vBYiWn7OUU+2fqL2yIHC6k3K8nrJuT73m/eU2K2ilFhFU1UfF+H628i5Zb0c5SfL1V4fq2T1JZICiTG7l5niK2svIkeJFMnkymUimTyAho26zuTZ7UkUYHOcn2Iqqq7AgkiCaLyoi0qAUAXBA+iggUVpWjJ8k37Dmhw3E1N6cpfSlJ+bbIZoReSFRl1jDv1V5EsdUW+Fd0+8uFwAkbImSIoaAjWpUkGEgNl6Dv+vUfE7tE4L0OlbGUH/mO9x0FQ8keSZ7NZFs8VDrFBySk77sbq7ta7t8cSBPY070p193Bbv+JUpx8r1P4Dc5HN/S9iP7PS/wBSb8N2K98gObsjrVN2Lfl+HtJJFrindxj4vw09vuLCbCwtFFU37zzePLkiumj2byfxxI+sZRKLYEe/n8e8VJBR+OJHLV5/FgKNnyzl4FxX1sWmz/lSetnp4FzWXPL3kCKZBIlZDMkQgHpA+iyLF/u5/Vl7mAc0OFR0RTMAusjwvHwLqPAACqJ49PIACiZVLU9AGZ6Lf2vD/XR3+GiPQKh4zQ8d/wBwf14f+xADEb3I5Z6XP7RR/wBJ/fZ4CJ7Gici0q/vPsr8TwFhNQ4/HFEkQBAjxKKp4C30hGuJax1YBVKnZvypd6LvEagAQIgmegCEAED//2Q==" alt="David Rodriguez" class="testimonial-avatar">
                            <div>
                                <h5>David Rodriguez</h5>
                                <p>Marketing Director at BrandUp</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>';

includeFooter();
?>