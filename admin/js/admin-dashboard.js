// Admin Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Check authentication
    checkAuth();
    
    // Load dashboard data
    loadDashboardData();
    
    // Load recent activity
    loadRecentActivity();
});

function checkAuth() {
    const token = localStorage.getItem('admin_token');
    if (!token) {
        window.location.href = 'login.php';
        return;
    }
    
    // Verify token with server (optional)
    // You can add token verification here
}

async function loadDashboardData() {
    try {
        // Load projects count
        const projectsResponse = await fetch('../api/projects.php');
        const projectsData = await projectsResponse.json();
        if (projectsData.success && projectsData.data) {
            document.getElementById('projects-count').textContent = projectsData.data.length;
        }
        
        // Load team count
        const teamResponse = await fetch('../api/team.php');
        const teamData = await teamResponse.json();
        if (teamData.success && teamData.data) {
            document.getElementById('team-count').textContent = teamData.data.length;
        }
        
        // Load messages count
        const messagesResponse = await fetch('api/messages.php');
        const messagesData = await messagesResponse.json();
        if (messagesData.success && messagesData.data) {
            document.getElementById('messages-count').textContent = messagesData.data.length;
        }
        
        // Load blog posts count
        const postsResponse = await fetch('api/blog.php');
        const postsData = await postsResponse.json();
        if (postsData.success && postsData.data) {
            document.getElementById('posts-count').textContent = postsData.data.length;
        }
        
    } catch (error) {
        console.error('Error loading dashboard data:', error);
    }
}

async function loadRecentActivity() {
    try {
        const response = await fetch('api/activity.php?limit=5');
        const data = await response.json();
        
        if (data.success && data.data) {
            const tbody = document.getElementById('activity-table');
            tbody.innerHTML = '';
            
            data.data.forEach(activity => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${activity.action}</td>
                    <td>${activity.description}</td>
                    <td>${activity.admin_name || 'System'}</td>
                    <td>${formatTime(activity.created_at)}</td>
                `;
                tbody.appendChild(row);
            });
        }
    } catch (error) {
        console.error('Error loading recent activity:', error);
    }
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);
    
    if (minutes < 1) return 'Just now';
    if (minutes < 60) return `${minutes}m ago`;
    if (hours < 24) return `${hours}h ago`;
    if (days < 7) return `${days}d ago`;
    
    return date.toLocaleDateString();
}

function logout() {
    if (confirm('Are you sure you want to logout?')) {
        localStorage.removeItem('admin_token');
        window.location.href = 'login.php';
    }
}

// Auto-refresh dashboard data every 30 seconds
setInterval(() => {
    loadDashboardData();
    loadRecentActivity();
}, 30000);

