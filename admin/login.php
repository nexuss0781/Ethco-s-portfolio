<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - EthCo Coders</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="../assets/images/ethco_logo_new.png" alt="EthCo Coders" class="login-logo">
                <h1>Admin Panel</h1>
                <p>Sign in to manage your website</p>
            </div>
            
            <form class="login-form" id="login-form">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember_me">
                        <span class="checkmark"></span>
                        Remember me
                    </label>
                </div>
                
                <button type="submit" class="login-btn">
                    <span class="btn-text">Sign In</span>
                    <span class="btn-loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Signing in...
                    </span>
                </button>
            </form>
            
            <div class="login-footer">
                <p>Default credentials: <strong>natnael</strong> / <strong>password</strong></p>
                <a href="../index.php">← Back to Website</a>
            </div>
        </div>
    </div>
    
    <script src="js/admin-login.js"></script>
</body>
</html>

