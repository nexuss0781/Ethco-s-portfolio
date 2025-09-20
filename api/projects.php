<?php
/**
 * Projects API Endpoint
 */

require_once '../config/database.php';
require_once '../includes/functions.php';

enableCORS();

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$path_parts = explode('/', trim(parse_url($request_uri, PHP_URL_PATH), '/'));

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getProject($db, $_GET['id']);
        } elseif (isset($_GET['featured'])) {
            getFeaturedProjects($db);
        } else {
            getAllProjects($db);
        }
        break;
    
    case 'POST':
        createProject($db);
        break;
    
    case 'PUT':
        if (isset($_GET['id'])) {
            updateProject($db, $_GET['id']);
        } else {
            sendJSONResponse(['error' => 'Project ID required'], 400);
        }
        break;
    
    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteProject($db, $_GET['id']);
        } else {
            sendJSONResponse(['error' => 'Project ID required'], 400);
        }
        break;
    
    default:
        sendJSONResponse(['error' => 'Method not allowed'], 405);
}

function getAllProjects($db) {
    try {
        $query = "SELECT p.*, 
                         GROUP_CONCAT(pi.image_url) as additional_images,
                         t.testimonial, t.client_name as testimonial_client
                  FROM projects p 
                  LEFT JOIN project_images pi ON p.id = pi.project_id 
                  LEFT JOIN testimonials t ON p.id = t.project_id AND t.featured = 1
                  GROUP BY p.id 
                  ORDER BY p.created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $projects = $stmt->fetchAll();
        
        // Process additional images
        foreach ($projects as &$project) {
            $project['additional_images'] = $project['additional_images'] ? 
                explode(',', $project['additional_images']) : [];
        }
        
        sendJSONResponse(['success' => true, 'data' => $projects]);
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to fetch projects: ' . $e->getMessage()], 500);
    }
}

function getProject($db, $id) {
    try {
        $query = "SELECT p.*, 
                         GROUP_CONCAT(pi.image_url) as additional_images,
                         t.testimonial, t.client_name as testimonial_client, t.client_position, t.client_company
                  FROM projects p 
                  LEFT JOIN project_images pi ON p.id = pi.project_id 
                  LEFT JOIN testimonials t ON p.id = t.project_id
                  WHERE p.id = ? 
                  GROUP BY p.id";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $project = $stmt->fetch();
        
        if ($project) {
            $project['additional_images'] = $project['additional_images'] ? 
                explode(',', $project['additional_images']) : [];
            sendJSONResponse(['success' => true, 'data' => $project]);
        } else {
            sendJSONResponse(['error' => 'Project not found'], 404);
        }
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to fetch project: ' . $e->getMessage()], 500);
    }
}

function getFeaturedProjects($db) {
    try {
        $query = "SELECT * FROM projects WHERE featured = 1 ORDER BY created_at DESC LIMIT 6";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $projects = $stmt->fetchAll();
        
        sendJSONResponse(['success' => true, 'data' => $projects]);
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to fetch featured projects: ' . $e->getMessage()], 500);
    }
}

function createProject($db) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['title']) || !isset($input['description'])) {
        sendJSONResponse(['error' => 'Title and description are required'], 400);
    }
    
    try {
        $query = "INSERT INTO projects (title, description, short_description, technologies, 
                                      project_url, github_url, image_url, featured, status, 
                                      client_name, project_date) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            sanitizeInput($input['title']),
            sanitizeInput($input['description']),
            sanitizeInput($input['short_description'] ?? ''),
            sanitizeInput($input['technologies'] ?? ''),
            sanitizeInput($input['project_url'] ?? ''),
            sanitizeInput($input['github_url'] ?? ''),
            sanitizeInput($input['image_url'] ?? ''),
            isset($input['featured']) ? (bool)$input['featured'] : false,
            sanitizeInput($input['status'] ?? 'completed'),
            sanitizeInput($input['client_name'] ?? ''),
            $input['project_date'] ?? null
        ]);
        
        $project_id = $db->lastInsertId();
        sendJSONResponse(['success' => true, 'message' => 'Project created successfully', 'id' => $project_id], 201);
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to create project: ' . $e->getMessage()], 500);
    }
}

function updateProject($db, $id) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        sendJSONResponse(['error' => 'Invalid input data'], 400);
    }
    
    try {
        $query = "UPDATE projects SET 
                    title = ?, description = ?, short_description = ?, technologies = ?,
                    project_url = ?, github_url = ?, image_url = ?, featured = ?, 
                    status = ?, client_name = ?, project_date = ?, updated_at = CURRENT_TIMESTAMP
                  WHERE id = ?";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            sanitizeInput($input['title']),
            sanitizeInput($input['description']),
            sanitizeInput($input['short_description'] ?? ''),
            sanitizeInput($input['technologies'] ?? ''),
            sanitizeInput($input['project_url'] ?? ''),
            sanitizeInput($input['github_url'] ?? ''),
            sanitizeInput($input['image_url'] ?? ''),
            isset($input['featured']) ? (bool)$input['featured'] : false,
            sanitizeInput($input['status'] ?? 'completed'),
            sanitizeInput($input['client_name'] ?? ''),
            $input['project_date'] ?? null,
            $id
        ]);
        
        if ($stmt->rowCount() > 0) {
            sendJSONResponse(['success' => true, 'message' => 'Project updated successfully']);
        } else {
            sendJSONResponse(['error' => 'Project not found or no changes made'], 404);
        }
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to update project: ' . $e->getMessage()], 500);
    }
}

function deleteProject($db, $id) {
    try {
        $query = "DELETE FROM projects WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            sendJSONResponse(['success' => true, 'message' => 'Project deleted successfully']);
        } else {
            sendJSONResponse(['error' => 'Project not found'], 404);
        }
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to delete project: ' . $e->getMessage()], 500);
    }
}
?>

