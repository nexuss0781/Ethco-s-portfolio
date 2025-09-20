-- EthCo Coders Portfolio Website Database Schema

-- Create database
CREATE DATABASE IF NOT EXISTS ethco_portfolio;
USE ethco_portfolio;

-- Projects table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    short_description VARCHAR(500),
    technologies VARCHAR(500),
    project_url VARCHAR(255),
    github_url VARCHAR(255),
    image_url VARCHAR(255),
    featured BOOLEAN DEFAULT FALSE,
    status ENUM('completed', 'in_progress', 'planned') DEFAULT 'completed',
    client_name VARCHAR(255),
    project_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Project images table (for multiple images per project)
CREATE TABLE project_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

-- Team members table
CREATE TABLE team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    bio TEXT,
    image_url VARCHAR(255),
    email VARCHAR(255),
    linkedin_url VARCHAR(255),
    github_url VARCHAR(255),
    twitter_url VARCHAR(255),
    skills VARCHAR(500),
    display_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Blog posts table
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt VARCHAR(500),
    featured_image VARCHAR(255),
    author_id INT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES team_members(id) ON DELETE SET NULL
);

-- Blog categories table
CREATE TABLE blog_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blog post categories junction table
CREATE TABLE blog_post_categories (
    blog_post_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (blog_post_id, category_id),
    FOREIGN KEY (blog_post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE CASCADE
);

-- Services table
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    short_description VARCHAR(500),
    icon VARCHAR(255),
    features TEXT,
    price_range VARCHAR(100),
    display_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    phone VARCHAR(50),
    company VARCHAR(255),
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Testimonials table
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    client_position VARCHAR(255),
    client_company VARCHAR(255),
    testimonial TEXT NOT NULL,
    client_image VARCHAR(255),
    project_id INT,
    rating INT DEFAULT 5,
    featured BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);

-- Company information table
CREATE TABLE company_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    tagline VARCHAR(500),
    description TEXT,
    mission TEXT,
    vision TEXT,
    address TEXT,
    phone VARCHAR(50),
    email VARCHAR(255),
    website VARCHAR(255),
    linkedin_url VARCHAR(255),
    twitter_url VARCHAR(255),
    facebook_url VARCHAR(255),
    instagram_url VARCHAR(255),
    github_url VARCHAR(255),
    founded_year INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data

-- Company information
INSERT INTO company_info (
    company_name, 
    tagline, 
    description, 
    mission, 
    vision,
    address,
    phone,
    email,
    website,
    linkedin_url,
    github_url,
    founded_year
) VALUES (
    'EthCo Coders',
    'Bridging Ethiopian Innovation with Global Technology',
    'EthCo Coders is a leading software development company based in Ethiopia, specializing in web development, mobile applications, and digital solutions. We combine local expertise with international standards to deliver exceptional results.',
    'To empower Ethiopian businesses and organizations through innovative technology solutions while showcasing the incredible talent of Ethiopian developers to the world.',
    'To become the premier technology partner for businesses across Ethiopia and beyond, driving digital transformation and economic growth.',
    'Addis Ababa, Ethiopia',
    '+251-11-XXX-XXXX',
    'info@ethcocoders.com',
    'https://ethcocoders.com',
    'https://linkedin.com/company/ethco-coders',
    'https://github.com/ethco-coders',
    2020
);

-- Sample services
INSERT INTO services (name, description, short_description, display_order) VALUES
('Web Development', 'Full-stack web development using modern technologies like PHP, JavaScript, React, and more. We create responsive, scalable, and user-friendly websites.', 'Custom websites and web applications built with modern technologies.', 1),
('Mobile App Development', 'Native and cross-platform mobile applications for iOS and Android. We use React Native, Flutter, and native development approaches.', 'iOS and Android apps that engage users and drive business growth.', 2),
('UI/UX Design', 'User-centered design approach to create intuitive and beautiful interfaces. We focus on user experience and modern design principles.', 'Beautiful, intuitive designs that users love and businesses need.', 3),
('E-commerce Solutions', 'Complete e-commerce platforms with payment integration, inventory management, and customer management systems.', 'Full-featured online stores that drive sales and growth.', 4),
('Digital Marketing', 'SEO, social media marketing, and digital advertising to help businesses reach their target audience effectively.', 'Strategic digital marketing to boost your online presence.', 5),
('Consulting & Strategy', 'Technology consulting and digital transformation strategy to help businesses leverage technology effectively.', 'Expert guidance for your digital transformation journey.', 6);

-- Sample team members
INSERT INTO team_members (name, position, bio, skills, display_order) VALUES
('Abebe Tadesse', 'Lead Developer & Founder', 'Experienced full-stack developer with 8+ years in web development. Passionate about building scalable solutions and mentoring young developers.', 'PHP, JavaScript, React, Node.js, MySQL, AWS', 1),
('Hanan Mohammed', 'UI/UX Designer', 'Creative designer with a keen eye for user experience. Specializes in creating beautiful and functional interfaces that users love.', 'Figma, Adobe Creative Suite, User Research, Prototyping', 2),
('Dawit Bekele', 'Mobile Developer', 'Mobile development specialist with expertise in both native and cross-platform development. Loves creating apps that make a difference.', 'React Native, Flutter, iOS, Android, Firebase', 3),
('Sara Alemayehu', 'Project Manager', 'Experienced project manager who ensures projects are delivered on time and within budget. Expert in agile methodologies.', 'Project Management, Agile, Scrum, Team Leadership', 4);

-- Sample blog categories
INSERT INTO blog_categories (name, slug, description) VALUES
('Web Development', 'web-development', 'Articles about web development trends, tutorials, and best practices'),
('Mobile Development', 'mobile-development', 'Mobile app development insights and tutorials'),
('Design', 'design', 'UI/UX design articles and design inspiration'),
('Technology Trends', 'tech-trends', 'Latest technology trends and industry insights'),
('Company News', 'company-news', 'Updates and news from EthCo Coders');

-- Sample projects
INSERT INTO projects (
    title, 
    description, 
    short_description, 
    technologies, 
    featured, 
    status, 
    client_name, 
    project_date
) VALUES
('Ethiopian E-commerce Platform', 'A comprehensive e-commerce platform designed specifically for Ethiopian businesses. Features include multi-language support, local payment integration, and inventory management.', 'Modern e-commerce platform for Ethiopian businesses', 'PHP, MySQL, JavaScript, Bootstrap, Stripe API', TRUE, 'completed', 'Local Business Group', '2024-06-15'),
('Mobile Banking App', 'Secure mobile banking application with biometric authentication, transaction history, and bill payment features.', 'Secure mobile banking solution with modern features', 'React Native, Node.js, MongoDB, JWT', TRUE, 'completed', 'Ethiopian Bank', '2024-08-01'),
('Educational Management System', 'Complete school management system with student records, grade tracking, and parent communication features.', 'Comprehensive school management platform', 'PHP, MySQL, jQuery, Chart.js', FALSE, 'completed', 'Addis Ababa School', '2024-05-20'),
('Restaurant Ordering System', 'Online food ordering platform with real-time order tracking and payment integration.', 'Modern food ordering and delivery platform', 'React, Node.js, Express, MongoDB', TRUE, 'completed', 'Addis Restaurant Chain', '2024-07-10');

-- Sample testimonials
INSERT INTO testimonials (client_name, client_position, client_company, testimonial, rating, featured, project_id) VALUES
('Meron Tadesse', 'CEO', 'Local Business Group', 'EthCo Coders delivered an exceptional e-commerce platform that exceeded our expectations. Their attention to detail and understanding of the Ethiopian market made all the difference.', 5, TRUE, 1),
('Dr. Alemseged Worku', 'IT Director', 'Ethiopian Bank', 'The mobile banking app developed by EthCo Coders has revolutionized how our customers interact with our services. Professional, secure, and user-friendly.', 5, TRUE, 2),
('Tigist Haile', 'Principal', 'Addis Ababa School', 'The school management system has streamlined our operations significantly. The team was professional and delivered exactly what we needed.', 5, FALSE, 3);

-- Create indexes for better performance
CREATE INDEX idx_projects_featured ON projects(featured);
CREATE INDEX idx_projects_status ON projects(status);
CREATE INDEX idx_blog_posts_status ON blog_posts(status);
CREATE INDEX idx_blog_posts_published ON blog_posts(published_at);
CREATE INDEX idx_contact_messages_status ON contact_messages(status);
CREATE INDEX idx_testimonials_featured ON testimonials(featured);
CREATE INDEX idx_team_members_active ON team_members(active);



-- Create admin user and grant privileges
CREATE USER IF NOT EXISTS 'ethco_user'@'localhost' IDENTIFIED BY 'ethco_password';
GRANT ALL PRIVILEGES ON ethco_portfolio.* TO 'ethco_user'@'localhost';
FLUSH PRIVILEGES;

-- Create admin tables
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    full_name VARCHAR(255),
    role ENUM('admin', 'editor', 'viewer') DEFAULT 'admin',
    active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin_sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS admin_activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Insert default admin user
INSERT INTO admin_users (username, password, email, full_name, role) VALUES
("natnael", "$2y$10$9.t.e.l.p.a.s.s.w.o.r.d.H.a.s.h.e.d", "natnaelermiyas@gmail.com", "Natnael Ermiyas", "admin");


