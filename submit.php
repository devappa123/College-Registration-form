<?php
/**
 * Admission Form Submission Handler (No cURL Required)
 */

// Include configuration
require_once 'config.php';

// Set JSON response headers
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Send JSON response and exit
 */
function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

/**
 * Validate file upload
 */
function validateFile($file, $allowedTypes, $maxSize = 2097152) {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    if ($file['size'] > $maxSize) {
        return false;
    }
    
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }
    
    return true;
}

/**
 * Generate unique application ID
 */
function generateApplicationId() {
    return 'ADM' . date('Y') . mt_rand(100000, 999999);
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

try {
    // Collect and sanitize form data
    $formData = [
        'application_id' => generateApplicationId(),
        'full_name' => sanitizeInput($_POST['fullName'] ?? ''),
        'dob' => sanitizeInput($_POST['dob'] ?? ''),
        'gender' => sanitizeInput($_POST['gender'] ?? ''),
        'mobile' => sanitizeInput($_POST['mobile'] ?? ''),
        'email' => sanitizeInput($_POST['email'] ?? ''),
        'address' => sanitizeInput($_POST['address'] ?? ''),
        'parent_name' => sanitizeInput($_POST['parentName'] ?? ''),
        'parent_phone' => sanitizeInput($_POST['parentPhone'] ?? ''),
        'school_10' => sanitizeInput($_POST['school10'] ?? ''),
        'board_10' => sanitizeInput($_POST['board10'] ?? ''),
        'year_10' => sanitizeInput($_POST['year10'] ?? ''),
        'percentage_10' => sanitizeInput($_POST['percentage10'] ?? ''),
        'college_12' => sanitizeInput($_POST['college12'] ?? ''),
        'board_12' => sanitizeInput($_POST['board12'] ?? ''),
        'year_12' => sanitizeInput($_POST['year12'] ?? ''),
        'percentage_12' => sanitizeInput($_POST['percentage12'] ?? ''),
        'exam_type' => sanitizeInput($_POST['examType'] ?? ''),
        'exam_rank' => sanitizeInput($_POST['rank'] ?? ''),
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Validate required fields
    $requiredFields = ['full_name', 'dob', 'gender', 'mobile', 'email', 'address', 'parent_name', 'parent_phone'];
    foreach ($requiredFields as $field) {
        if (empty($formData[$field])) {
            sendResponse(false, "Missing required field: {$field}");
        }
    }
    
    // Validate files
    $fileFields = [
        'photoUpload' => ['image/jpeg', 'image/jpg', 'image/png'],
        'markcard10' => ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'],
        'markcard12' => ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'],
        'tcUpload' => ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf']
    ];
    
    $fileUrls = [];
    
    foreach ($fileFields as $fieldName => $allowedTypes) {
        if (!isset($_FILES[$fieldName]) || !validateFile($_FILES[$fieldName], $allowedTypes)) {
            sendResponse(false, "Invalid or missing file: {$fieldName}");
        }
        
        // Generate unique filename
        $file = $_FILES[$fieldName];
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueFileName = $formData['application_id'] . '_' . $fieldName . '_' . time() . '.' . $extension;
        
        // Upload to Supabase Storage
        $fileUrl = uploadToSupabase($file, $uniqueFileName);
        
        if (!$fileUrl) {
            sendResponse(false, "Failed to upload file: {$fieldName}. Check if bucket 'admission_docs' exists and is public in Supabase Storage.");
        }
        
        $fileUrls[$fieldName] = $fileUrl;
    }
    
    // Add file URLs to form data
    $formData['photo_url'] = $fileUrls['photoUpload'];
    $formData['markcard_10_url'] = $fileUrls['markcard10'];
    $formData['markcard_12_url'] = $fileUrls['markcard12'];
    $formData['tc_url'] = $fileUrls['tcUpload'];
    
    // Insert data into Supabase Database
    $insertResult = insertToSupabase($formData);
    
    if (!$insertResult) {
        sendResponse(false, 'Failed to save application data. Check if table "admissions" exists in Supabase.');
    }
    
    // Success response
    sendResponse(true, 'Application submitted successfully', [
        'applicationId' => $formData['application_id']
    ]);
    
} catch (Exception $e) {
    sendResponse(false, 'Server error: ' . $e->getMessage());
}
?>
