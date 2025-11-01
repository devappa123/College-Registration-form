<?php
/**
 * DEBUG VERSION - Admission Form Submission Handler
 * Use this to see detailed error messages
 */

// Include configuration
require_once 'config.php';

// Set JSON response headers
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

/**
 * Send JSON response and exit
 */
function sendResponse($success, $message, $data = null, $debug = null) {
    $response = [
        'success' => $success,
        'message' => $message,
        'data' => $data
    ];
    
    if ($debug) {
        $response['debug'] = $debug;
    }
    
    echo json_encode($response);
    exit;
}

// Test basic configuration
if (!defined('SUPABASE_URL') || !defined('SUPABASE_KEY')) {
    sendResponse(false, 'Configuration error: Supabase credentials not set', null, [
        'SUPABASE_URL_defined' => defined('SUPABASE_URL'),
        'SUPABASE_KEY_defined' => defined('SUPABASE_KEY')
    ]);
}

// Check if file_get_contents is allowed for URLs
if (!ini_get('allow_url_fopen')) {
    sendResponse(false, 'allow_url_fopen is disabled in php.ini', null, [
        'suggestion' => 'Enable allow_url_fopen in php.ini'
    ]);
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method', null, [
        'received_method' => $_SERVER['REQUEST_METHOD']
    ]);
}

try {
    // Check if files were uploaded
    $debug = [
        'files_received' => array_keys($_FILES),
        'post_received' => array_keys($_POST)
    ];
    
    // Collect and sanitize form data
    $formData = [
        'application_id' => 'ADM' . date('Y') . mt_rand(100000, 999999),
        'full_name' => htmlspecialchars($_POST['fullName'] ?? ''),
        'dob' => htmlspecialchars($_POST['dob'] ?? ''),
        'gender' => htmlspecialchars($_POST['gender'] ?? ''),
        'mobile' => htmlspecialchars($_POST['mobile'] ?? ''),
        'email' => htmlspecialchars($_POST['email'] ?? ''),
        'address' => htmlspecialchars($_POST['address'] ?? ''),
        'parent_name' => htmlspecialchars($_POST['parentName'] ?? ''),
        'parent_phone' => htmlspecialchars($_POST['parentPhone'] ?? ''),
        'school_10' => htmlspecialchars($_POST['school10'] ?? ''),
        'board_10' => htmlspecialchars($_POST['board10'] ?? ''),
        'year_10' => htmlspecialchars($_POST['year10'] ?? ''),
        'percentage_10' => htmlspecialchars($_POST['percentage10'] ?? ''),
        'college_12' => htmlspecialchars($_POST['college12'] ?? ''),
        'board_12' => htmlspecialchars($_POST['board12'] ?? ''),
        'year_12' => htmlspecialchars($_POST['year12'] ?? ''),
        'percentage_12' => htmlspecialchars($_POST['percentage12'] ?? ''),
        'exam_type' => htmlspecialchars($_POST['examType'] ?? ''),
        'exam_rank' => htmlspecialchars($_POST['rank'] ?? ''),
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Test Supabase connection first
    $testEndpoint = "/rest/v1/" . SUPABASE_TABLE . "?limit=1";
    $testResult = supabaseRequest($testEndpoint, 'GET', null);
    
    if ($testResult['httpCode'] === 0) {
        sendResponse(false, 'Cannot connect to Supabase. Check your internet connection or Supabase URL.', null, [
            'supabase_url' => SUPABASE_URL,
            'curl_error' => 'Connection failed'
        ]);
    }
    
    if ($testResult['httpCode'] === 401 || $testResult['httpCode'] === 403) {
        sendResponse(false, 'Invalid Supabase API key', null, [
            'http_code' => $testResult['httpCode'],
            'response' => $testResult['response']
        ]);
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
        if (!isset($_FILES[$fieldName])) {
            sendResponse(false, "Missing file: {$fieldName}", null, $debug);
        }
        
        $file = $_FILES[$fieldName];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            sendResponse(false, "File upload error for {$fieldName}", null, [
                'error_code' => $file['error'],
                'error_message' => getUploadErrorMessage($file['error'])
            ]);
        }
        
        if ($file['size'] > 2097152) {
            sendResponse(false, "File {$fieldName} is too large (max 2MB)", null, [
                'file_size' => $file['size']
            ]);
        }
        
        if (!in_array($file['type'], $allowedTypes)) {
            sendResponse(false, "Invalid file type for {$fieldName}", null, [
                'received_type' => $file['type'],
                'allowed_types' => $allowedTypes
            ]);
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueFileName = $formData['application_id'] . '_' . $fieldName . '_' . time() . '.' . $extension;
        
        // Upload to Supabase Storage
        $fileUrl = uploadToSupabase($file, $uniqueFileName);
        
        if (!$fileUrl) {
            sendResponse(false, "Failed to upload file: {$fieldName}", null, [
                'file_name' => $uniqueFileName,
                'check' => 'Verify bucket "admission_docs" exists and is public in Supabase Storage'
            ]);
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
        sendResponse(false, 'Failed to save application data', null, [
            'check' => 'Verify table "admissions" exists in Supabase'
        ]);
    }
    
    // Success response
    sendResponse(true, 'Application submitted successfully', [
        'applicationId' => $formData['application_id']
    ]);
    
} catch (Exception $e) {
    sendResponse(false, 'Server error: ' . $e->getMessage(), null, [
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}

function getUploadErrorMessage($code) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in HTML form',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the upload'
    ];
    
    return $errors[$code] ?? 'Unknown upload error';
}
?>
