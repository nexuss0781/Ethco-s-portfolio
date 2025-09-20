<?php
/**
 * Contact API Endpoint
 */

require_once '../config/database.php';
require_once '../includes/functions.php';

enableCORS();

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        handleContactForm($db);
        break;
    
    case 'GET':
        if (isset($_GET['admin']) && $_GET['admin'] === 'true') {
            getContactMessages($db);
        } else {
            sendJSONResponse(['error' => 'Method not allowed'], 405);
        }
        break;
    
    default:
        sendJSONResponse(['error' => 'Method not allowed'], 405);
}

function handleContactForm($db) {
    // Rate limiting
    $client_ip = getClientIP();
    if (!checkRateLimit($client_ip, 5, 3600)) {
        sendJSONResponse(['error' => 'Too many requests. Please try again later.'], 429);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (!$input || !isset($input['name']) || !isset($input['email']) || !isset($input['message'])) {
        sendJSONResponse(['error' => 'Name, email, and message are required'], 400);
    }
    
    // Validate email
    if (!validateEmail($input['email'])) {
        sendJSONResponse(['error' => 'Invalid email address'], 400);
    }
    
    // Validate message length
    if (strlen($input['message']) < 10) {
        sendJSONResponse(['error' => 'Message must be at least 10 characters long'], 400);
    }
    
    try {
        $query = "INSERT INTO contact_messages (name, email, subject, message, phone, company) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            sanitizeInput($input['name']),
            sanitizeInput($input['email']),
            sanitizeInput($input['subject'] ?? ''),
            sanitizeInput($input['message']),
            sanitizeInput($input['phone'] ?? ''),
            sanitizeInput($input['company'] ?? '')
        ]);
        
        // Send email notification (optional)
        sendEmailNotification($input);
        
        sendJSONResponse([
            'success' => true, 
            'message' => 'Thank you for your message! We will get back to you soon.'
        ], 201);
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to send message: ' . $e->getMessage()], 500);
    }
}

function getContactMessages($db) {
    try {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        $offset = ($page - 1) * $limit;
        
        $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
        
        $where_clause = '';
        $params = [];
        
        if ($status_filter) {
            $where_clause = 'WHERE status = ?';
            $params[] = $status_filter;
        }
        
        // Get total count
        $count_query = "SELECT COUNT(*) as total FROM contact_messages $where_clause";
        $count_stmt = $db->prepare($count_query);
        $count_stmt->execute($params);
        $total = $count_stmt->fetch()['total'];
        
        // Get messages
        $query = "SELECT * FROM contact_messages $where_clause 
                  ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $messages = $stmt->fetchAll();
        
        sendJSONResponse([
            'success' => true,
            'data' => $messages,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to fetch messages: ' . $e->getMessage()], 500);
    }
}

function sendEmailNotification($data) {
    // Simple email notification (you can enhance this with a proper email service)
    $to = 'info@ethcocoders.com';
    $subject = 'New Contact Form Submission - ' . ($data['subject'] ?? 'No Subject');
    
    $message = "New contact form submission:\n\n";
    $message .= "Name: " . $data['name'] . "\n";
    $message .= "Email: " . $data['email'] . "\n";
    $message .= "Phone: " . ($data['phone'] ?? 'Not provided') . "\n";
    $message .= "Company: " . ($data['company'] ?? 'Not provided') . "\n";
    $message .= "Subject: " . ($data['subject'] ?? 'No subject') . "\n\n";
    $message .= "Message:\n" . $data['message'] . "\n";
    
    $headers = "From: noreply@ethcocoders.com\r\n";
    $headers .= "Reply-To: " . $data['email'] . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Uncomment the line below to enable email sending
    // mail($to, $subject, $message, $headers);
}
?>

