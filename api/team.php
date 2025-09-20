<?php
/**
 * Team Members API Endpoint
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
            getTeamMember($db, $_GET['id']);
        } else {
            getAllTeamMembers($db);
        }
        break;
    
    case 'POST':
        createTeamMember($db);
        break;
    
    case 'PUT':
        if (isset($_GET['id'])) {
            updateTeamMember($db, $_GET['id']);
        } else {
            sendJSONResponse(['error' => 'Team member ID required'], 400);
        }
        break;
    
    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteTeamMember($db, $_GET['id']);
        } else {
            sendJSONResponse(['error' => 'Team member ID required'], 400);
        }
        break;
    
    default:
        sendJSONResponse(['error' => 'Method not allowed'], 405);
}

function getAllTeamMembers($db) {
    try {
        $query = "SELECT * FROM team_members WHERE active = 1 ORDER BY display_order ASC, name ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $team_members = $stmt->fetchAll();
        
        sendJSONResponse(['success' => true, 'data' => $team_members]);
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to fetch team members: ' . $e->getMessage()], 500);
    }
}

function getTeamMember($db, $id) {
    try {
        $query = "SELECT * FROM team_members WHERE id = ? AND active = 1";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $team_member = $stmt->fetch();
        
        if ($team_member) {
            sendJSONResponse(['success' => true, 'data' => $team_member]);
        } else {
            sendJSONResponse(['error' => 'Team member not found'], 404);
        }
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to fetch team member: ' . $e->getMessage()], 500);
    }
}

function createTeamMember($db) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['name']) || !isset($input['position'])) {
        sendJSONResponse(['error' => 'Name and position are required'], 400);
    }
    
    try {
        $query = "INSERT INTO team_members (name, position, bio, image_url, email, 
                                          linkedin_url, github_url, twitter_url, skills, display_order) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            sanitizeInput($input['name']),
            sanitizeInput($input['position']),
            sanitizeInput($input['bio'] ?? ''),
            sanitizeInput($input['image_url'] ?? ''),
            sanitizeInput($input['email'] ?? ''),
            sanitizeInput($input['linkedin_url'] ?? ''),
            sanitizeInput($input['github_url'] ?? ''),
            sanitizeInput($input['twitter_url'] ?? ''),
            sanitizeInput($input['skills'] ?? ''),
            (int)($input['display_order'] ?? 0)
        ]);
        
        $member_id = $db->lastInsertId();
        sendJSONResponse(['success' => true, 'message' => 'Team member created successfully', 'id' => $member_id], 201);
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to create team member: ' . $e->getMessage()], 500);
    }
}

function updateTeamMember($db, $id) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        sendJSONResponse(['error' => 'Invalid input data'], 400);
    }
    
    try {
        $query = "UPDATE team_members SET 
                    name = ?, position = ?, bio = ?, image_url = ?, email = ?,
                    linkedin_url = ?, github_url = ?, twitter_url = ?, skills = ?, 
                    display_order = ?, updated_at = CURRENT_TIMESTAMP
                  WHERE id = ?";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            sanitizeInput($input['name']),
            sanitizeInput($input['position']),
            sanitizeInput($input['bio'] ?? ''),
            sanitizeInput($input['image_url'] ?? ''),
            sanitizeInput($input['email'] ?? ''),
            sanitizeInput($input['linkedin_url'] ?? ''),
            sanitizeInput($input['github_url'] ?? ''),
            sanitizeInput($input['twitter_url'] ?? ''),
            sanitizeInput($input['skills'] ?? ''),
            (int)($input['display_order'] ?? 0),
            $id
        ]);
        
        if ($stmt->rowCount() > 0) {
            sendJSONResponse(['success' => true, 'message' => 'Team member updated successfully']);
        } else {
            sendJSONResponse(['error' => 'Team member not found or no changes made'], 404);
        }
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to update team member: ' . $e->getMessage()], 500);
    }
}

function deleteTeamMember($db, $id) {
    try {
        // Soft delete - set active to 0
        $query = "UPDATE team_members SET active = 0 WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            sendJSONResponse(['success' => true, 'message' => 'Team member deactivated successfully']);
        } else {
            sendJSONResponse(['error' => 'Team member not found'], 404);
        }
    } catch (Exception $e) {
        sendJSONResponse(['error' => 'Failed to deactivate team member: ' . $e->getMessage()], 500);
    }
}
?>

