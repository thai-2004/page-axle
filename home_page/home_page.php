<?php
require_once '../config/database.php';
require_once '../models/Category.php';
require_once '../models/News.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize objects
$category = new Category($db);
$news = new News($db);

// Get categories
$categories = $category->getAll();
$categoryList = $categories->fetchAll(PDO::FETCH_ASSOC);

// Get latest news
$latestNews = $news->getLatest(6);
$newsList = $latestNews->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduPress - Online Learning Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
    /* Featured Courses Section Styles */
    .featured-courses {
        padding: 80px 0;
        background: #fff;
    }

    .featured-courses .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .featured-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }

    .featured-header-left {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .featured-header-left h2 {
        font-size: 32px;
        font-weight: 600;
        color: #1D2026;
        margin: 0;
    }

    .featured-header-left p {
        font-size: 16px;
        color: #6D737A;
        margin: 0;
    }

    .all-courses-btn {
        padding: 8px 20px;
        border: 1px solid #E0E0E0;
        border-radius: 30px;
        background: transparent;
        color: #1D2026;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .all-courses-btn:hover {
        background: #FF782D;
        color: white;
        border-color: #FF782D;
    }

    .featured-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-top: 40px;
    }

    .featured-card {
        background: #FFFFFF;
        border: 1px solid #E0E0E0;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%; /* Ensure all cards have same height */
    }

    .featured-card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }

    .featured-image {
        position: relative;
        width: 100%;
        height: 220px; /* Increased height for better proportion */
        overflow: hidden;
    }

    .featured-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .featured-tag {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
    }

    .featured-content {
        padding: 20px;
    }

    .featured-author {
        font-size: 14px;
        color: #6D737A;
        margin-bottom: 8px;
    }

    .featured-author span {
        color: #FF782D;
    }

    .featured-title {
        font-size: 18px;
        font-weight: 600;
        color: #1D2026;
        margin: 0 0 16px 0;
        line-height: 1.4;
    }

    .featured-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 14px;
        color: #6D737A;
    }

    .featured-meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .featured-price {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 16px;
        min-height: 30px; /* Ensure consistent height */
    }

    .featured-price > div {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 100px; /* Ensure consistent width for price area */
    }

    .price {
        font-size: 20px;
        font-weight: 600;
        color: #FF782D;
        white-space: nowrap; /* Prevent price from wrapping */
    }

    .price.free {
        color: #22C55E;
    }

    .price-original {
        text-decoration: line-through;
        color: #6D737A;
        font-size: 14px;
        white-space: nowrap; /* Prevent price from wrapping */
    }

    .view-more {
        color: #1D2026;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .view-more:hover {
        color: #FF782D;
    }

    @media (max-width: 1200px) {
        .featured-courses .container {
            max-width: 100%;
            padding: 0 20px;
        }
        
        .featured-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .featured-grid {
            grid-template-columns: 1fr;
        }
        
        .featured-image {
            height: 200px;
        }
    }

    .banner-child {
        width: 100%;
        position: relative;
        height: 300px;
        margin: 40px 0;
        border-radius: 16px;
        overflow: hidden;
    }

    .banner-child img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .banner-child::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            rgba(231, 255, 243, 0.9) 0%, 
            rgba(243, 243, 243, 0.9) 50%, 
            rgba(255, 231, 231, 0.9) 100%);
    }

    .banner-child .container {
        position: relative;
        height: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        z-index: 2;
    }

    .banner-content {
        max-width: 450px;
        position: relative;
        z-index: 3;
    }

    .banner-content img {
        display: none; /* Remove duplicate image */
    }

    .banner-content h2 {
        font-size: 28px;
        font-weight: 600;
        color: #1D2026;
        margin-bottom: 12px;
        line-height: 1.3;
    }

    .banner-content p {
        font-size: 15px;
        color: #6D737A;
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .explorer-btn {
        display: inline-block;
        padding: 12px 24px;
        background: #FF782D;
        color: white;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .explorer-btn:hover {
        background: #ff6b1a;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .banner-child {
            border-radius: 0;
        }
        
        .banner-content {
            text-align: center;
            padding: 0 20px;
        }
    }

    /* Statistics Section Styles */
    .statistics {
        padding: 80px 0;
        background: #fff;
    }

    .statistics .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .statistics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    .stat-card {
        background: #F8F9FA;
        border-radius: 16px;
        padding: 32px 24px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .stat-number {
        font-size: 36px;
        font-weight: 600;
        color: #FF782D;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 16px;
        font-weight: 500;
        color: #1D2026;
    }

    @media (max-width: 1200px) {
        .statistics .container {
            max-width: 100%;
            padding: 0 20px;
        }
    }

    @media (max-width: 992px) {
        .statistics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .statistics-grid {
            grid-template-columns: 1fr;
        }
        
        .banner-child {
            height: 400px;
        }
        
        .banner-content {
            text-align: center;
            margin: 0 auto;
        }
    }

    /* Testimonials Section Styles */
    .testimonials {
        padding: 80px 0;
        background: #fff;
        overflow: hidden;
    }

    .testimonials .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
    }

    .testimonials-content {
        flex: 1;
        max-width: 500px;
    }

    .testimonials-content h2 {
        font-size: 32px;
        font-weight: 600;
        color: #1D2026;
        margin-bottom: 16px;
        line-height: 1.3;
    }

    .testimonials-content p {
        font-size: 15px;
        color: #6D737A;
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .certification-list {
        list-style: none;
        padding: 0;
        margin: 0 0 32px 0;
    }

    .certification-list li {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        color: #1D2026;
        font-size: 15px;
    }

    .certification-list li i {
        color: #22C55E;
        font-size: 16px;
    }

    .testimonial-btn {
        display: inline-block;
        padding: 12px 24px;
        background: #FF782D;
        color: white;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .testimonial-btn:hover {
        background: #ff6b1a;
        transform: translateY(-2px);
    }

    .testimonials-image {
        flex: 1;
        max-width: 600px;
    }

    .testimonials-image img {
        width: 100%;
        height: auto;
        object-fit: contain;
    }

    @media (max-width: 992px) {
        .testimonials .container {
            flex-direction: column;
            text-align: center;
        }

        .testimonials-content {
            max-width: 100%;
        }

        .certification-list li {
            justify-content: center;
        }

        .testimonials-image {
            max-width: 100%;
            order: -1;
        }
    }

    @media (max-width: 576px) {
        .testimonials {
            padding: 60px 0;
        }

        .testimonials-content h2 {
            font-size: 28px;
        }
    }

    /* Banner Child 1 Section Styles */
    .banner-child-1 {
        width: 100%;
        padding: 60px 0;
        background: linear-gradient(90deg, #E7F0FF 0%, #F3F3F3 50%, #FFE7E7 100%);
        border-radius: 16px;
        margin: 40px 0;
        overflow: hidden;
        position: relative;
    }

    .banner-child-1 .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .banner-child-1 img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 16px;
    }

    .banner-child-1::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        
        z-index: 1;
    }

    .banner-child-1 h2 {
        font-size: 32px;
        font-weight: 600;
        color: #1D2026;
        margin-bottom: 16px;
        line-height: 1.3;
        position: relative;
    }

    .banner-child-1 p {
        font-size: 16px;
        color: #6D737A;
        margin-bottom: 32px;
        line-height: 1.6;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }

    .banner-child-1 .explorer-btn {
        display: inline-block;
        padding: 12px 32px;
        background: #FF782D;
        color: white;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
    }

    .banner-child-1 .explorer-btn:hover {
        background: #ff6b1a;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .banner-child-1 {
            border-radius: 0;
            padding: 40px 0;
        }

        .banner-child-1 img {
            border-radius: 0;
        }

        .banner-child-1 h2 {
            font-size: 28px;
        }

        .banner-child-1 p {
            font-size: 15px;
            padding: 0 20px;
        }
    }

    /* Student Feedbacks Section Styles */
    .student-feedbacks {
        padding: 80px 0;
        background: #fff;
    }

    .student-feedbacks .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .feedback-header {
        text-align: center;
        margin-bottom: 48px;
    }

    .feedback-header h2 {
        font-size: 32px;
        font-weight: 600;
        color: #1D2026;
        margin-bottom: 12px;
    }

    .feedback-header p {
        font-size: 16px;
        color: #6D737A;
    }

    .feedback-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    .feedback-card {
        background: #FFFFFF;
        border: 1px solid #E0E0E0;
        border-radius: 16px;
        padding: 32px 24px;
        transition: all 0.3s ease;
    }

    .feedback-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .feedback-quote {
        font-size: 48px;
        color: #FF782D;
        margin-bottom: 16px;
    }

    .feedback-text {
        font-size: 15px;
        color: #6D737A;
        line-height: 1.6;
        margin-bottom: 24px;
        min-height: 120px;
    }

    .feedback-author {
        margin-top: 24px;
    }

    .feedback-author h4 {
        font-size: 18px;
        font-weight: 600;
        color: #1D2026;
        margin-bottom: 4px;
    }

    .feedback-author p {
        font-size: 14px;
        color: #6D737A;
    }

    @media (max-width: 1200px) {
        .feedback-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .feedback-grid {
            grid-template-columns: 1fr;
        }
        
        .feedback-text {
            min-height: auto;
        }
    }

    /* Footer Styles */
    .footer {
        background: #F8F9FA;
        padding: 80px 0 40px;
    }

    .footer .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .footer-content {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }

    .footer-brand {
        max-width: 400px;
    }

    .footer-brand img {
        height: 40px;
        margin-bottom: 20px;
    }

    .footer-brand p {
        font-size: 15px;
        color: #6D737A;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .footer-info {
        margin-bottom: 20px;
    }

    .footer-info p {
        font-size: 14px;
        color: #6D737A;
        margin-bottom: 8px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .footer-info i {
        color: #1D2026;
        margin-top: 4px;
    }

    .footer-column h4 {
        font-size: 18px;
        font-weight: 600;
        color: #1D2026;
        margin-bottom: 24px;
    }

    .footer-column ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-column ul li {
        margin-bottom: 16px;
    }

    .footer-column ul li a {
        color: #6D737A;
        text-decoration: none;
        font-size: 15px;
        transition: color 0.3s ease;
    }

    .footer-column ul li a:hover {
        color: #FF782D;
    }

    .social-links {
        display: flex;
        gap: 12px;
    }

    .social-links a {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1D2026;
        transition: all 0.3s ease;
    }

    .social-links a:hover {
        background: #FF782D;
        color: #fff;
    }

    .copyright {
        text-align: center;
        padding-top: 40px;
        border-top: 1px solid #E0E0E0;
    }

    .copyright p {
        font-size: 14px;
        color: #6D737A;
    }

    @media (max-width: 992px) {
        .footer-content {
            grid-template-columns: 1fr 1fr;
        }
        
        .footer-brand {
            grid-column: 1 / -1;
            max-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .footer-content {
            grid-template-columns: 1fr;
        }
        
        .footer {
            padding: 60px 0 30px;
        }
    }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <div class="logo">
                    <img src="assets/LOGO.jpg" alt="EduPress Logo">
                </div>
                <div class="nav-links">
                    <a href="#home" style="color: #FF782D;">Home</a>
                    <a href="#courses">Course</a>
                    <a href="#blog">Blog</a>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle">
                            Page <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a href="about.php">About Us</a>
                            <a href="team.php">Our Team</a>
                            <a href="pricing.php">Pricing Plans</a>
                            <div class="dropdown-divider"></div>
                            <a href="faq.php">FAQ</a>
                            <a href="testimonials.php">Testimonials</a>
                            <a href="contact.php">Contact Us</a>
                        </div>
                    </div>
                    <a href="learnpress.php">LearnPress Add-On</a>
                    <a href="Premium_theme.php" >Premium Theme</a>
                </div>
                <div class="login_bar">
                    <a class="login_button" href="login.php">Login/Signup</a>
                    <img src="assets/Search.jpg" alt="Login/Signup">
                </div>
            </nav>
        </div>
    </header>
    <div class ="body">
    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Build Skills With<br>Online Course</h1>
                    <p>We denounce with righteous indignation and dislike men who are so beguiled and demoralized that cannot trouble.</p>
                    <a href="#courses" class="cta-button">Posts Comment</a>
                </div>
                <div class="hero-image">
                    <img src="assets/banner.jpg" alt="Online Learning">
                </div>
            </div>
        </div>
    </section>

    <!-- Top Categories Section -->
    <section class="top-categories">
        <div class="container-categories">
            <div class="section-header">
                <div class="section-header-left">
                    <h2>Top Categories</h2>
                    <p>Explore our Popular Categories</p>
                </div>
                <button class="all-categories-btn">All Categories</button>
            </div>
            <div class="categories-grid">
                <?php foreach($categoryList as $cat): ?>
                <div class="category-card">
                    <i class="<?php echo $cat['icon']; ?> category-icon"></i>
                    <h3><?php echo $cat['name']; ?></h3>
                    <p><?php echo $cat['course_count']; ?> Courses</p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Featured Courses Section -->
    <section class="featured-courses">
        <div class="container">
            <div class="featured-header">
                <div class="featured-header-left">
                    <h2>Latest News</h2>
                    <p>Stay Updated with Our Latest Articles</p>
                </div>
                <button class="all-courses-btn">All News</button>
            </div>
            <div class="featured-grid">
                <?php foreach($newsList as $newsItem): ?>
                <div class="featured-card">
                    <div class="featured-image">
                        <img src="assets/news/<?php echo $newsItem['image']; ?>" alt="<?php echo $newsItem['title']; ?>">
                        <span class="featured-tag"><?php echo $newsItem['category_name']; ?></span>
                    </div>
                    <div class="featured-content">
                        <p class="featured-author">by <span><?php echo $newsItem['author']; ?></span></p>
                        <h3 class="featured-title"><?php echo $newsItem['title']; ?></h3>
                        <div class="featured-meta">
                            <span><i class="far fa-eye"></i> <?php echo $newsItem['views']; ?> Views</span>
                            <span><i class="far fa-calendar"></i> <?php echo date('d M Y', strtotime($newsItem['created_at'])); ?></span>
                        </div>
                        <div class="featured-price">
                            <a href="news.php?id=<?php echo $newsItem['id']; ?>" class="view-more">Read More</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!--banner child--> 
    <section class="banner-child">
        <img src="assets/banner-child.jpg" alt="Banner Child">
        <div class="container">
            <div class="banner-content">
                <h2>GET MORE POWER FROM LearnPress Add-Ons</h2>
                <p>The next level of LearnPress - LMS WordPress Plugin. More Powerful, Flexible and Magical Inside.</p>
                <a href="#" class="explorer-btn">Explorer Course</a>
            </div>
        </div>
    </section>

    <section class="statistics">
    <div class="container">
        <div class="statistics-grid">
            <div class="stat-card">
                <div class="stat-number">25K+</div>
                <div class="stat-label">Active Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">899</div>
                <div class="stat-label">Total Courses</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">158</div>
                <div class="stat-label">Instructor</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">100%</div>
                <div class="stat-label">Satisfaction Rate</div>
            </div>
        </div>
    </div>
</section>

<section class="testimonials">
    <div class="container">
        <div class="testimonials-content">
            <h2>Grow Us Your Skill<br>With LearnPress LMS</h2>
            <p>We denounce with righteous indignation and dislike men who are so beguiled and demoralized that cannot trouble.</p>
            <ul class="certification-list">
                <li><i class="fas fa-check-circle"></i> Certification</li>
                <li><i class="fas fa-check-circle"></i> Certification</li>
                <li><i class="fas fa-check-circle"></i> Certification</li>
                <li><i class="fas fa-check-circle"></i> Certification</li>
            </ul>
            <a href="#" class="testimonial-btn">Explorer Course</a>
        </div>
        <div class="testimonials-image">
            <img src="assets/Vector.jpg" alt="Learning Illustration">
        </div>
    </div>
</section>

<section class="banner-child-1">
    <div class="container">
        <img src="assets/banner-child-1.jpg" alt="Banner Child 1">
        <h2>PROVIDING AMAZING</h2>
        <h2>Education WordPress Theme</h2>
        <p>The next level of LMS WordPress Theme. Learn anytime and anywhere.</p>
        <a href="#" class="explorer-btn">Explorer Course</a>
    </div>
</section>

<section class="student-feedbacks">
    <div class="container">
        <div class="feedback-header">
            <h2>Student Feedbacks</h2>
            <p>What Students Say About Academy LMS</p>
        </div>
        <div class="feedback-grid">
            <div class="feedback-card">
                <div class="feedback-quote">"</div>
                <p class="feedback-text">I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system and expound</p>
                <div class="feedback-author">
                    <h4>Roe Smith</h4>
                    <p>Designer</p>
                </div>
            </div>
            <div class="feedback-card">
                <div class="feedback-quote">"</div>
                <p class="feedback-text">I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system and expound</p>
                <div class="feedback-author">
                    <h4>Roe Smith</h4>
                    <p>Designer</p>
                </div>
            </div>
            <div class="feedback-card">
                <div class="feedback-quote">"</div>
                <p class="feedback-text">I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system and expound</p>
                <div class="feedback-author">
                    <h4>Roe Smith</h4>
                    <p>Designer</p>
                </div>
            </div>
            <div class="feedback-card">
                <div class="feedback-quote">"</div>
                <p class="feedback-text">I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system and expound</p>
                <div class="feedback-author">
                    <h4>Roe Smith</h4>
                    <p>Designer</p>
                </div>
            </div>
        </div>
    </div>
</section>

</div>
   
     <!-- Footer -->
     <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <img src="assets/LOGO.jpg" alt="EduPress Logo">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <div class="footer-info">
                        <p><i class="fas fa-map-marker-alt"></i> Address: 2321 New Design St., Lorem Ipsum10 Hudson Yards, USA</p>
                        <p><i class="fas fa-phone"></i> Tel: + (123) 2500-567-8988</p>
                        <p><i class="fas fa-envelope"></i> Mail: supportlms@gmail.com</p>
                    </div>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-pinterest-p"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h4>GET HELP</h4>
                    <ul>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Latest Articles</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>PROGRAMS</h4>
                    <ul>
                        <li><a href="#">Art & Design</a></li>
                        <li><a href="#">Business</a></li>
                        <li><a href="#">IT & Software</a></li>
                        <li><a href="#">Languages</a></li>
                        <li><a href="#">Programming</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>Copyright © 2024 LearnPress LMS | Powered by ThimPress</p>
            </div>
        </div>
    </footer>
    </div>
</body>
</html>
