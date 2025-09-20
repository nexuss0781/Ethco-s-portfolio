/**
 * EthCo Coders Portfolio - Main JavaScript
 */

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initNavigation();
    initSmoothScrolling();
    initContactForm();
    loadDynamicContent();
    initScrollAnimations();
    initDarkMode();
});

// Dark Mode functionality
function initDarkMode() {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const body = document.body;
    const icon = darkModeToggle.querySelector('i');
    
    // Check for saved theme preference or default to light mode
    const savedTheme = localStorage.getItem('theme') || 'light';
    body.setAttribute('data-theme', savedTheme);
    updateDarkModeIcon(savedTheme, icon);
    
    // Toggle dark mode
    darkModeToggle.addEventListener('click', function() {
        const currentTheme = body.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        body.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateDarkModeIcon(newTheme, icon);
        
        // Add a subtle animation
        darkModeToggle.style.transform = 'scale(0.9)';
        setTimeout(() => {
            darkModeToggle.style.transform = 'scale(1)';
        }, 150);
    });
}

function updateDarkModeIcon(theme, icon) {
    if (theme === 'dark') {
        icon.className = 'fas fa-sun';
    } else {
        icon.className = 'fas fa-moon';
    }
}

// Navigation functionality
function initNavigation() {
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');
    const navLinks = document.querySelectorAll('.nav-link');

    // Mobile menu toggle
    if (navToggle) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            navToggle.classList.toggle('active');
        });
    }

    // Close mobile menu when clicking on a link
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navMenu.classList.remove('active');
            navToggle.classList.remove('active');
        });
    });

    // Update active nav link on scroll
    window.addEventListener('scroll', updateActiveNavLink);
}

// Update active navigation link based on scroll position
function updateActiveNavLink() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');
    
    let current = '';
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        
        if (window.pageYOffset >= sectionTop - 200) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });

    // Header background on scroll
    const header = document.getElementById('header');
    if (window.pageYOffset > 100) {
        header.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
    } else {
        header.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
    }
}

// Smooth scrolling for anchor links
function initSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                const headerHeight = document.getElementById('header').offsetHeight;
                const targetPosition = targetSection.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Contact form functionality
function initContactForm() {
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactFormSubmit);
    }
}

// Handle contact form submission
async function handleContactFormSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    // Show loading state
    submitBtn.classList.add('loading');
    
    try {
        const response = await fetch('api/contact.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Thank you! Your message has been sent successfully.', 'success');
            form.reset();
        } else {
            showNotification(result.error || 'Failed to send message. Please try again.', 'error');
        }
    } catch (error) {
        console.error('Contact form error:', error);
        showNotification('Failed to send message. Please try again.', 'error');
    } finally {
        submitBtn.classList.remove('loading');
    }
}

// Load dynamic content
async function loadDynamicContent() {
    await Promise.all([
        loadServices(),
        loadFeaturedProjects(),
        loadTeamMembers(),
        loadTestimonials()
    ]);
}

// Load services
async function loadServices() {
    try {
        const response = await fetch('api/services.php');
        const result = await response.json();
        
        if (result.success && result.data) {
            renderServices(result.data);
        }
    } catch (error) {
        console.error('Failed to load services:', error);
    }
}

// Render services
function renderServices(services) {
    const servicesGrid = document.getElementById('services-grid');
    if (!servicesGrid) return;
    
    const serviceIcons = {
        'Web Development': 'fas fa-code',
        'Mobile App Development': 'fas fa-mobile-alt',
        'UI/UX Design': 'fas fa-paint-brush',
        'E-commerce Solutions': 'fas fa-shopping-cart',
        'Digital Marketing': 'fas fa-bullhorn',
        'Consulting & Strategy': 'fas fa-lightbulb'
    };
    
    servicesGrid.innerHTML = services.map(service => `
        <div class="service-card">
            <div class="service-icon">
                <i class="${serviceIcons[service.name] || 'fas fa-cog'}"></i>
            </div>
            <h3 class="service-title">${service.name}</h3>
            <p class="service-description">${service.short_description || service.description}</p>
        </div>
    `).join('');
}

// Load featured projects
async function loadFeaturedProjects() {
    try {
        const response = await fetch('api/projects.php?featured=true');
        const result = await response.json();
        
        if (result.success && result.data) {
            renderProjects(result.data.slice(0, 6)); // Show only 6 featured projects
        }
    } catch (error) {
        console.error('Failed to load projects:', error);
    }
}

// Render projects
function renderProjects(projects) {
    const portfolioGrid = document.getElementById('portfolio-grid');
    if (!portfolioGrid) return;
    
    portfolioGrid.innerHTML = projects.map(project => `
        <div class="project-card">
            <img src="${project.image_url || 'assets/images/placeholder-project.jpg'}" 
                 alt="${project.title}" class="project-image">
            <div class="project-content">
                <h3 class="project-title">${project.title}</h3>
                <p class="project-description">${project.short_description || truncateText(project.description, 100)}</p>
                <div class="project-tech">
                    ${project.technologies ? project.technologies.split(',').map(tech => 
                        `<span class="tech-tag">${tech.trim()}</span>`
                    ).join('') : ''}
                </div>
                <div class="project-links">
                    ${project.project_url ? `<a href="${project.project_url}" target="_blank" class="project-link">View Live</a>` : ''}
                    ${project.github_url ? `<a href="${project.github_url}" target="_blank" class="project-link">View Code</a>` : ''}
                </div>
            </div>
        </div>
    `).join('');
}

// Load team members
async function loadTeamMembers() {
    try {
        const response = await fetch('api/team.php');
        const result = await response.json();
        
        if (result.success && result.data) {
            renderTeamMembers(result.data);
        }
    } catch (error) {
        console.error('Failed to load team members:', error);
    }
}

// Render team members
function renderTeamMembers(teamMembers) {
    const teamGrid = document.getElementById('team-grid');
    if (!teamGrid) return;
    
    teamGrid.innerHTML = teamMembers.map(member => `
        <div class="team-card">
            <img src="${member.image_url || 'assets/images/placeholder-avatar.jpg'}" 
                 alt="${member.name}" class="team-image">
            <h3 class="team-name">${member.name}</h3>
            <p class="team-position">${member.position}</p>
            <p class="team-bio">${member.bio || ''}</p>
            <div class="team-social">
                ${member.linkedin_url ? `<a href="${member.linkedin_url}" target="_blank" class="social-link"><i class="fab fa-linkedin"></i></a>` : ''}
                ${member.github_url ? `<a href="${member.github_url}" target="_blank" class="social-link"><i class="fab fa-github"></i></a>` : ''}
                ${member.twitter_url ? `<a href="${member.twitter_url}" target="_blank" class="social-link"><i class="fab fa-twitter"></i></a>` : ''}
            </div>
        </div>
    `).join('');
}

// Load testimonials
async function loadTestimonials() {
    try {
        // For now, we'll use the sample testimonials from the database
        // In a real implementation, you might have a separate testimonials API endpoint
        const testimonials = [
            {
                testimonial: "EthCo Coders delivered an exceptional e-commerce platform that exceeded our expectations. Their attention to detail and understanding of the Ethiopian market made all the difference.",
                client_name: "Meron Tadesse",
                client_position: "CEO",
                client_company: "Local Business Group"
            },
            {
                testimonial: "The mobile banking app developed by EthCo Coders has revolutionized how our customers interact with our services. Professional, secure, and user-friendly.",
                client_name: "Dr. Alemseged Worku",
                client_position: "IT Director",
                client_company: "Ethiopian Bank"
            },
            {
                testimonial: "The school management system has streamlined our operations significantly. The team was professional and delivered exactly what we needed.",
                client_name: "Tigist Haile",
                client_position: "Principal",
                client_company: "Addis Ababa School"
            }
        ];
        
        renderTestimonials(testimonials);
    } catch (error) {
        console.error('Failed to load testimonials:', error);
    }
}

// Render testimonials
function renderTestimonials(testimonials) {
    const testimonialsSlider = document.getElementById('testimonials-slider');
    if (!testimonialsSlider) return;
    
    testimonialsSlider.innerHTML = testimonials.map(testimonial => `
        <div class="testimonial-card">
            <p class="testimonial-text">"${testimonial.testimonial}"</p>
            <div class="testimonial-author">${testimonial.client_name}</div>
            <div class="testimonial-position">${testimonial.client_position}, ${testimonial.client_company}</div>
        </div>
    `).join('');
}

// Scroll animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animatedElements = document.querySelectorAll('.service-card, .project-card, .team-card, .testimonial-card');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

// Utility functions
function truncateText(text, length) {
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        max-width: 400px;
        animation: slideInRight 0.3s ease;
    `;
    
    // Add to DOM
    document.body.appendChild(notification);
    
    // Close functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.remove();
    });
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Add notification animation styles
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }
    
    .notification-close:hover {
        opacity: 0.8;
    }
`;
document.head.appendChild(notificationStyles);



    // Language Switcher
    const languageSwitcher = document.getElementById("language-switcher");
    if (languageSwitcher) {
        languageSwitcher.addEventListener("change", (event) => {
            const selectedLang = event.target.value;
            window.location.href = `?lang=${selectedLang}`;
        });
    }

