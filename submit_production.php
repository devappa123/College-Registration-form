<?php
/**
 * Admission Form Submission Handler - Production Version
 * Optimized for speed and reliability
 */

// Use production config (change to config.php after testing)
require_once 'config_production.php';

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

/**
 * Send JSON response
 */
function sendResponse($success, $message, $data = null) {
    http_response_code($success ? 200 : 400);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Validate file
 */
function validateFile($file, $allowedTypes, $maxSize = 2097152) {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'error' => 'File upload error'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'error' => 'File too large (max 2MB)'];
    }
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['valid' => false, 'error' => 'Invalid file type'];
    }
    
    return ['valid' => true];
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

try {
    // Generate application ID
    $applicationId = 'ADM' . date('Y') . mt_rand(100000, 999999);
    
    // Collect form data
    $formData = [
        'application_id' => $applicationId,
        'full_name' => htmlspecialchars(trim($_POST['fullName'] ?? '')),
        'dob' => htmlspecialchars($_POST['dob'] ?? ''),
        'gender' => htmlspecialchars($_POST['gender'] ?? ''),
        'mobile' => htmlspecialchars($_POST['mobile'] ?? ''),
        'email' => htmlspecialchars($_POST['email'] ?? ''),
        'address' => htmlspecialchars($_POST['address'] ?? ''),
        'parent_name' => htmlspecialchars(trim($_POST['parentName'] ?? '')),
        'parent_phone' => htmlspecialchars($_POST['parentPhone'] ?? ''),
        'school_10' => htmlspecialchars(trim($_POST['school10'] ?? '')),
        'board_10' => htmlspecialchars(trim($_POST['board10'] ?? '')),
        'year_10' => htmlspecialchars($_POST['year10'] ?? ''),
        'percentage_10' => htmlspecialchars($_POST['percentage10'] ?? ''),
        'college_12' => htmlspecialchars(trim($_POST['college12'] ?? '')),
        'board_12' => htmlspecialchars(trim($_POST['board12'] ?? '')),
        'year_12' => htmlspecialchars($_POST['year12'] ?? ''),
        'percentage_12' => htmlspecialchars($_POST['percentage12'] ?? ''),
        'exam_type' => htmlspecialchars($_POST['examType'] ?? ''),
        'exam_rank' => htmlspecialchars($_POST['rank'] ?? ''),
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Validate required fields
    $requiredFields = ['full_name', 'dob', 'gender', 'mobile', 'email'];
    foreach ($requiredFields as $field) {
        if (empty($formData[$field])) {
            sendResponse(false, "Missing required field: $field");
        }
    }
    
    // File configuration
    $fileFields = [
        'photoUpload' => ['image/jpeg', 'image/jpg', 'image/png'],
        'markcard10' => ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'],
        'markcard12' => ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'],
        'tcUpload' => ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf']
    ];
    
    $fileUrls = [];
    $timestamp = time();
    
    // Upload files
    foreach ($fileFields as $fieldName => $allowedTypes) {
        if (!isset($_FILES[$fieldName])) {
            sendResponse(false, "Missing file: $fieldName");
        }
        
        $file = $_FILES[$fieldName];
        
        // Validate file
        $validation = validateFile($file, $allowedTypes);
        if (!$validation['valid']) {
            sendResponse(false, "$fieldName: " . $validation['error']);
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueFileName = "{$applicationId}_{$fieldName}_{$timestamp}." . strtolower($extension);
        
        // Upload to Supabase
        $fileUrl = uploadToSupabase($file, $uniqueFileName);
        
        if (!$fileUrl) {
            sendResponse(false, "Failed to upload $fieldName. Please try again.");
        }
        
        $fileUrls[$fieldName] = $fileUrl;
    }
    
    // Add file URLs to form data
    $formData['photo_url'] = $fileUrls['photoUpload'];
    $formData['markcard_10_url'] = $fileUrls['markcard10'];
    $formData['markcard_12_url'] = $fileUrls['markcard12'];
    $formData['tc_url'] = $fileUrls['tcUpload'];
    
    // Insert into database
    $insertResult = insertToSupabase($formData);
    
    if (!$insertResult) {
        sendResponse(false, 'Failed to save application. Please try again.');
    }
    
    // Success
    sendResponse(true, 'Application submitted successfully', [
        'applicationId' => $applicationId
    ]);
    
} catch (Exception $e) {
    error_log('Submission error: ' . $e->getMessage());
    sendResponse(false, 'An error occurred. Please try again.');
}
?>
