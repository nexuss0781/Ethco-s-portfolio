<?php
require_once 'includes/language.php';
?>
<!DOCTYPE html>
<html lang="<?php echo get_current_language(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EthCo Coders - Ethiopian Software Development Company</title>
    <meta name="description" content="EthCo Coders is a leading software development company in Ethiopia, specializing in web development, mobile apps, and digital solutions.">
    <meta name="keywords" content="Ethiopian software development, web development Ethiopia, mobile app development, EthCo Coders">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/ethco_logo.png">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Dark Mode Toggle -->
    <button class="dark-mode-toggle" id="dark-mode-toggle" aria-label="Toggle dark mode">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Spline Background Effect -->
    <div class="spline-bg">
        <div class="spline-curve"></div>
        <div class="spline-curve"></div>
        <div class="spline-curve"></div>
    </div>
    <!-- Header -->
    <header class="header" id="header">
        <nav class="navbar">
            <div class="container">
                <div class="nav-brand">
                    <img src="assets/images/ethco_logo_new.png" alt="EthCo Coders Logo" class="logo">
                </div>
                
                <ul class="nav-menu" id="nav-menu">
                    <li class="nav-item">
                        <a href="#home" class="nav-link active"><?php echo __("home"); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#about" class="nav-link"><?php echo __("about"); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#services" class="nav-link"><?php echo __("services"); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#portfolio" class="nav-link"><?php echo __("portfolio"); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#team" class="nav-link"><?php echo __("team"); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#contact" class="nav-link"><?php echo __("contact"); ?></a>
                    </li>                    <li class="nav-item lang-select">
                        <select id="language-switcher" onchange="window.location.href='?lang=' + this.value;">
                            <option value="en" <?php echo (get_current_language() == 'en') ? 'selected' : ''; ?>>English</option>
                            <option value="am" <?php echo (get_current_language() == 'am') ? 'selected' : ''; ?>>አማርኛ</option>
                            <option value="om" <?php echo (get_current_language() == 'om') ? 'selected' : ''; ?>>Oromiffaa</option>
                        </select>
                    </li>                    </li>
                </ul>
                
                <div class="nav-toggle" id="nav-toggle">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-background">
            <img src="assets/images/hero_image.png" alt="EthCo Coders Team" class="hero-bg-image">
            <div class="hero-overlay"></div>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <?php echo __("hero_title"); ?>
                </h1>
                <p class="hero-description">
                    <?php echo __("hero_description"); ?>
                </p>
                <div class="hero-buttons">
                    <a href="#portfolio" class="btn btn-primary"><?php echo __("view_work"); ?></a>
                    <a href="#contact" class="btn btn-secondary"><?php echo __("get_in_touch"); ?></a>
                </div>
            </div>
        </div>
        
        <div class="scroll-indicator">
            <div class="scroll-arrow"></div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo __("our_services"); ?></h2>
                <p class="section-description">
                    <?php echo __("services_description"); ?>
                </p>
            </div>
            
            <div class="services-grid" id="services-grid">
                <!-- Services will be loaded dynamically -->
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="portfolio" id="portfolio">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo __("featured_projects"); ?></h2>
                <p class="section-description">
                    <?php echo __("projects_description"); ?>
                </p>
            </div>
            
            <div class="portfolio-grid" id="portfolio-grid">
                <!-- Projects will be loaded dynamically -->
            </div>
            
            <div class="portfolio-actions">
                <a href="portfolio.php" class="btn btn-outline"><?php echo __("view_all_projects"); ?></a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2 class="section-title"><?php echo __("about_us"); ?></h2>
                    <p class="about-description">
                        <?php echo __("about_description"); ?>
                    </p>
                    
                    <div class="about-stats">
                        <div class="stat-item">
                            <span class="stat-number">50+</span>
                            <span class="stat-label"><?php echo __("projects_completed"); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">30+</span>
                            <span class="stat-label"><?php echo __("happy_clients"); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">4+</span>
                            <span class="stat-label"><?php echo __("years_experience"); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="about-image">
                    <img src="assets/images/natnael_ermiyas.png" alt="EthCo Coders Team" class="about-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team" id="team">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo __("meet_our_team"); ?></h2>
                <p class="section-description">
                    <?php echo __("team_description"); ?>
                </p>
            </div>
            
            <div class="team-grid" id="team-grid">
                <!-- T
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo __("what_clients_say"); ?></h2>
                <p class="section-description">
                    <?php echo __("testimonials_description"); ?>
                </p>
            </div>
            
            <div class="testimonials-slider" id="testimonials-slider">
                <!-- Testimonials will be loaded dynamically -->
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo __("contact_us"); ?></h2>
                <p class="section-description">
                    <?php echo __("contact_cta"); ?>
                </p>
                
            </div>
            
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4><?php echo __("address"); ?></h4>
                            <p>Ethiopia Addis Ababa gulele wereda 4 wetatoch meakel no.2</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
          https://ethcocoders.zya.me/                  <h4><?php echo __("phone"); ?></h4>
                            <p>+251-921-779-888</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4><?php echo __("email"); ?></h4>
                            <p>ethcodecoders@gmail.com</p>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
                
                <form class="contact-form" id="contact-form">
                    <div class="form-group">
                        <input type="text" id="name" name="name" placeholder="<?php echo __("your_name"); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="<?php echo __("your_email"); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="text" id="subject" name="subject" placeholder="Subject">
                    </div>
                    
                    <div class="form-group">
                        <textarea id="message" name="message" placeholder="<?php echo __("your_message"); ?>" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-full">
                        <span class="btn-text"><?php echo __("send_message"); ?></span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Sending...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-brand">
                        <img src="assets/images/ethco_logo.png" alt="EthCo Coders Logo" class="footer-logo">
                        <p class="footer-description">
                            Bridging Ethiopian Innovation with Global Technology
                        </p>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-title"><?php echo __("quick_links"); ?></h4>
                    <ul class="footer-links">
                        <li><a href="#home"><?php echo __("home"); ?></a></li>
                        <li><a href="#about"><?php echo __("about"); ?></a></li>
                        <li><a href="#services"><?php echo __("services"); ?></a></li>
                        <li><a href="#portfolio"><?php echo __("portfolio"); ?></a></li>
                        <li><a href="#contact"><?php echo __("contact"); ?></a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-title">Services</h4>
                    <ul class="footer-links">
                        <li><a href="#services">Web Development</a></li>
                        <li><a href="#services">Mobile Apps</a></li>
                        <li><a href="#services">UI/UX Design</a></li>
                        <li><a href="#services">E-commerce</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-title"><?php echo __("contact_info"); ?></h4>
                    <div class="footer-contact">
                        <p><i class="fas fa-map-marker-alt"></i> Ethiopia Addis Ababa gulele wereda 4 wetatoch meakel no.2</p>
                        <p><i class="fas fa-phone"></i> +251-911-779-888</p>
                        <p><i class="fas fa-envelope"></i> ethcodecoders@gmail.com</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo __("copyright"); ?></p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
</body>
</html>

