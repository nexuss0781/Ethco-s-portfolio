<?php
/**
 * Company Information API Endpoint
 */

require_once '../config/database.php';
require_once '../includes/functions.php';

enableCORS();

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getCompanyInfo($db);
        break;
    
    case 'PUT':
        updateCompanyInfo($db);
        break;
    
    default:
        sendJSONResponse(['error' => 'Method not allowed'], 405);
}

function getCompanyInfo($db) {
    try {
        $query = "SELECT * FROM company_info LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $company_info = $stmt->fetch();
        
        if ($company_info) {
            sendJSONResponse(['success' => true, 'data' => $company_info]);
        } else {
            // Return default company info if none exists
            $default_info = [
                'company_name' => 'EthCo Coders',
                'tagline' => 'Bridging Ethiopian Innovation with Global Technology',
                'description' => 'EthCo Coders is a leading software development company based in Ethiopia.',
                'mission' => 'To empower Ethiopian businesses through innovative technology solutions.',
                'vision' => 'To become the premier technology partner for businesses across Ethiopia and beyond.',
                'address' => 'Addis Ababa, Ethiopia',
                'phone' => '+251-11-XXX-XXXX',
                'email' => 'info@ethcocoders.com',
                'website' => 'https://ethcocoders.com',
                'founded_year' => 2020
            ];
            sendJSONResponse(['success' => true, 'data' => $default_info]);
        }
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to fetch company info: ' . $e->getMessage()], 500);
    }
}

function updateCompanyInfo($db) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        sendJSONResponse(['error' => 'Invalid input data'], 400);
    }
    
    try {
        // Check if company info exists
        $check_query = "SELECT id FROM company_info LIMIT 1";
        $check_stmt = $db->prepare($check_query);
        $check_stmt->execute();
        $exists = $check_stmt->fetch();
        
        if ($exists) {
            // Update existing record
            $query = "UPDATE company_info SET 
                        company_name = ?, tagline = ?, description = ?, mission = ?, vision = ?,
                        address = ?, phone = ?, email = ?, website = ?, linkedin_url = ?,
                        twitter_url = ?, facebook_url = ?, instagram_url = ?, github_url = ?,
                        founded_year = ?, updated_at = CURRENT_TIMESTAMP
                      WHERE id = ?";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                sanitizeInput($input['company_name'] ?? ''),
                sanitizeInput($input['tagline'] ?? ''),
                sanitizeInput($input['description'] ?? ''),
                sanitizeInput($input['mission'] ?? ''),
                sanitizeInput($input['vision'] ?? ''),
                sanitizeInput($input['address'] ?? ''),
                sanitizeInput($input['phone'] ?? ''),
                sanitizeInput($input['email'] ?? ''),
                sanitizeInput($input['website'] ?? ''),
                sanitizeInput($input['linkedin_url'] ?? ''),
                sanitizeInput($input['twitter_url'] ?? ''),
                sanitizeInput($input['facebook_url'] ?? ''),
                sanitizeInput($input['instagram_url'] ?? ''),
                sanitizeInput($input['github_url'] ?? ''),
                (int)($input['founded_year'] ?? 0),
                $exists['id']
            ]);
        } else {
            // Insert new record
            $query = "INSERT INTO company_info 
                        (company_name, tagline, description, mission, vision, address, phone, email, 
                         website, linkedin_url, twitter_url, facebook_url, instagram_url, github_url, founded_year) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                sanitizeInput($input['company_name'] ?? ''),
                sanitizeInput($input['tagline'] ?? ''),
                sanitizeInput($input['description'] ?? ''),
                sanitizeInput($input['mission'] ?? ''),
                sanitizeInput($input['vision'] ?? ''),
                sanitizeInput($input['address'] ?? ''),
                sanitizeInput($input['phone'] ?? ''),
                sanitizeInput($input['email'] ?? ''),
                sanitizeInput($input['website'] ?? ''),
                sanitizeInput($input['linkedin_url'] ?? ''),
                sanitizeInput($input['twitter_url'] ?? ''),
                sanitizeInput($input['facebook_url'] ?? ''),
                sanitizeInput($input['instagram_url'] ?? ''),
                sanitizeInput($input['github_url'] ?? ''),
                (int)($input['founded_year'] ?? 0)
            ]);
        }
        
        sendJSONResponse(['success' => true, 'message' => 'Company information updated successfully']);
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to update company info: ' . $e->getMessage()], 500);
    }
}
?>

