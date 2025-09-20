<?php
/**
 * Services API Endpoint
 */

require_once '../config/database.php';
require_once '../includes/functions.php';

enableCORS();

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getService($db, $_GET['id']);
        } else {
            getAllServices($db);
        }
        break;
    
    case 'POST':
        createService($db);
        break;
    
    case 'PUT':
        if (isset($_GET['id'])) {
            updateService($db, $_GET['id']);
        } else {
            sendJSONResponse(['error' => 'Service ID required'], 400);
        }
        break;
    
    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteService($db, $_GET['id']);
        } else {
            sendJSONResponse(['error' => 'Service ID required'], 400);
        }
        break;
    
    default:
        sendJSONResponse(['error' => 'Method not allowed'], 405);
}

function getAllServices($db) {
    try {
        $query = "SELECT * FROM services WHERE active = 1 ORDER BY display_order ASC, name ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $services = $stmt->fetchAll();
        
        sendJSONResponse(['success' => true, 'data' => $services]);
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to fetch services: ' . $e->getMessage()], 500);
    }
}

function getService($db, $id) {
    try {
        $query = "SELECT * FROM services WHERE id = ? AND active = 1";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $service = $stmt->fetch();
        
        if ($service) {
            sendJSONResponse(['success' => true, 'data' => $service]);
        } else {
            sendJSONResponse(['error' => 'Service not found'], 404);
        }
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to fetch service: ' . $e->getMessage()], 500);
    }
}

function createService($db) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['name']) || !isset($input['description'])) {
        sendJSONResponse(['error' => 'Name and description are required'], 400);
    }
    
    try {
        $query = "INSERT INTO services (name, description, short_description, icon, 
                                      features, price_range, display_order) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            sanitizeInput($input['name']),
            sanitizeInput($input['description']),
            sanitizeInput($input['short_description'] ?? ''),
            sanitizeInput($input['icon'] ?? ''),
            sanitizeInput($input['features'] ?? ''),
            sanitizeInput($input['price_range'] ?? ''),
            (int)($input['display_order'] ?? 0)
        ]);
        
        $service_id = $db->lastInsertId();
        sendJSONResponse(['success' => true, 'message' => 'Service created successfully', 'id' => $service_id], 201);
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to create service: ' . $e->getMessage()], 500);
    }
}

function updateService($db, $id) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        sendJSONResponse(['error' => 'Invalid input data'], 400);
    }
    
    try {
        $query = "UPDATE services SET 
                    name = ?, description = ?, short_description = ?, icon = ?,
                    features = ?, price_range = ?, display_order = ?, updated_at = CURRENT_TIMESTAMP
                  WHERE id = ?";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            sanitizeInput($input['name']),
            sanitizeInput($input['description']),
            sanitizeInput($input['short_description'] ?? ''),
            sanitizeInput($input['icon'] ?? ''),
            sanitizeInput($input['features'] ?? ''),
            sanitizeInput($input['price_range'] ?? ''),
            (int)($input['display_order'] ?? 0),
            $id
        ]);
        
        if ($stmt->rowCount() > 0) {
            sendJSONResponse(['success' => true, 'message' => 'Service updated successfully']);
        } else {
            sendJSONResponse(['error' => 'Service not found or no changes made'], 404);
        }
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to update service: ' . $e->getMessage()], 500);
    }
}

function deleteService($db, $id) {
    try {
        // Soft delete - set active to 0
        $query = "UPDATE services SET active = 0 WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            sendJSONResponse(['success' => true, 'message' => 'Service deactivated successfully']);
        } else {
            sendJSONResponse(['error' => 'Service not found'], 404);
        }
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to deactivate service: ' . $e->getMessage()], 500);
    }
}
?>

