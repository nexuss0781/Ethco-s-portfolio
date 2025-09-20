<?php
/**
 * Admin Login API Endpoint
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../config/database.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['username']) || !isset($input['password'])) {
        echo json_encode(['success' => false, 'error' => 'Username and password are required']);
        exit;
    }
    
    $username = trim($input['username']);
    $password = $input['password'];
    
    // Find user by username or email
    $query = "SELECT * FROM admin_users WHERE (username = :username OR email = :username) AND active = 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
        exit;
    }
    
    // For demo purposes, accept 'password' as the password
    // In production, use password_verify($password, $user['password_hash'])
    if ($password !== 'password' && !password_verify($password, $user['password_hash'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
        exit;
    }
    
    // Generate session token
    $sessionToken = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Store session
    $sessionQuery = "INSERT INTO admin_sessions (admin_id, session_token, expires_at, ip_address, user_agent) 
                     VALUES (:admin_id, :session_token, :expires_at, :ip_address, :user_agent)";
    $sessionStmt = $db->prepare($sessionQuery);
    $sessionStmt->bindParam(':admin_id', $user['id']);
    $sessionStmt->bindParam(':session_token', $sessionToken);
    $sessionStmt->bindParam(':expires_at', $expiresAt);
    $sessionStmt->bindParam(':ip_address', $ipAddress);
    $sessionStmt->bindParam(':user_agent', $userAgent);
    $sessionStmt->execute();
    
    // Update last login
    $updateQuery = "UPDATE admin_users SET last_login = NOW() WHERE id = :id";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindParam(':id', $user['id']);
    $updateStmt->execute();
    
    // Log activity
    logActivity($db, $user['id'], 'login', 'User logged in successfully', $ipAddress, $userAgent);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'token' => $sessionToken,
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'role' => $user['role'],
            'avatar_url' => $user['avatar_url']
        ]
    ]);
    
} catch (Exception $e) {
    error_log("Admin login error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Internal server error']);
}

function logActivity($db, $adminId, $action, $description, $ipAddress, $userAgent) {
    try {
        $query = "INSERT INTO activity_logs (admin_id, action, description, ip_address, user_agent) 
                  VALUES (:admin_id, :action, :description, :ip_address, :user_agent)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':admin_id', $adminId);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':ip_address', $ipAddress);
        $stmt->bindParam(':user_agent', $userAgent);
        $stmt->execute();
    } catch (Exception $e) {
        error_log("Activity log error: " . $e->getMessage());
    }
}
?>

