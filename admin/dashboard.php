<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - EthCo Coders</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <img src="../assets/images/ethco_logo_new.png" alt="EthCo Coders" class="sidebar-logo">
                <h2 class="sidebar-title">Admin Panel</h2>
            </div>
            
            <nav class="sidebar-menu">
                <a href="dashboard.php" class="menu-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
                <a href="projects.php" class="menu-item">
                    <i class="fas fa-folder"></i>
                    Projects
                </a>
                <a href="services.php" class="menu-item">
                    <i class="fas fa-cogs"></i>
                    Services
                </a>
                <a href="team.php" class="menu-item">
                    <i class="fas fa-users"></i>
                    Team Members
                </a>
                <a href="blog.php" class="menu-item">
                    <i class="fas fa-blog"></i>
                    Blog Posts
                </a>
                <a href="messages.php" class="menu-item">
                    <i class="fas fa-envelope"></i>
                    Messages
                </a>
                <a href="chat.php" class="menu-item">
                    <i class="fas fa-comments"></i>
                    Chat
                </a>
                <a href="activity.php" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    Activity
                </a>
                <a href="admins.php" class="menu-item">
                    <i class="fas fa-user-shield"></i>
                    Admins
                </a>
                <a href="profile.php" class="menu-item">
                    <i class="fas fa-user"></i>
                    Profile
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    <div class="user-avatar" id="user-avatar">N</div>
                    <div class="user-info">
                        <p class="user-name" id="user-name">Natnael Ermiyas</p>
                        <p class="user-role" id="user-role">Super Admin</p>
                    </div>
                    <button class="logout-btn" onclick="logout()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </header>
            
            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Total Projects</h3>
                        <div class="card-icon projects">
                            <i class="fas fa-folder"></i>
                        </div>
                    </div>
                    <p class="card-value" id="projects-count">0</p>
                    <p class="card-change positive">+12% from last month</p>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Team Members</h3>
                        <div class="card-icon users">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <p class="card-value" id="team-count">4</p>
                    <p class="card-change positive">+1 new member</p>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Messages</h3>
                        <div class="card-icon messages">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    <p class="card-value" id="messages-count">0</p>
                    <p class="card-change">No new messages</p>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Blog Posts</h3>
                        <div class="card-icon posts">
                            <i class="fas fa-blog"></i>
                        </div>
                    </div>
                    <p class="card-value" id="posts-count">0</p>
                    <p class="card-change">Ready to publish</p>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="admin-table">
                <div class="table-header">
                    <h2 class="table-title">Recent Activity</h2>
                    <button class="add-btn" onclick="window.location.href='activity.php'">
                        <i class="fas fa-eye"></i> View All
                    </button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Description</th>
                            <th>User</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody id="activity-table">
                        <tr>
                            <td>Login</td>
                            <td>User logged in successfully</td>
                            <td>Natnael Ermiyas</td>
                            <td>Just now</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <script src="js/admin-dashboard.js"></script>
</body>
</html>

