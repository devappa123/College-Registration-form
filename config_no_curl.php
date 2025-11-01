<?php
/**
 * Supabase Configuration (No cURL Required)
 * Uses file_get_contents with stream context instead
 */

// Supabase Project URL
define('SUPABASE_URL', 'https://kxnbjlczjehcglkbfezi.supabase.co');

// Supabase Anon/Public Key
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imt4bmJqbGN6amVoY2dsa2JmZXppIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTg5NzI5MzcsImV4cCI6MjA3NDU0ODkzN30.jtTT5rt0RZ5JfKSYuPo4BnOWTl5woov-fXB5uZqEI0o');

// Supabase Storage Bucket Name
define('SUPABASE_BUCKET', 'admission_docs');

// Database Table Name
define('SUPABASE_TABLE', 'admissions');

/**
 * Helper function to make HTTP requests to Supabase using file_get_contents
 */
function supabaseRequest($endpoint, $method = 'GET', $data = null, $contentType = 'application/json') {
    $url = SUPABASE_URL . $endpoint;
    
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
    ];
    
    if ($contentType) {
        $headers[] = 'Content-Type: ' . $contentType;
    }
    
    $options = [
        'http' => [
            'method' => $method,
            'header' => implode("\r\n", $headers),
            'ignore_errors' => true,
            'timeout' => 30
        ]
    ];
    
    if ($data !== null && ($method === 'POST' || $method === 'PUT')) {
        $options['http']['content'] = $data;
    }
    
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    
    // Get HTTP response code
    $httpCode = 0;
    if (isset($http_response_header)) {
        if (preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response_header[0], $matches)) {
            $httpCode = intval($matches[1]);
        }
    }
    
    return [
        'response' => $response,
        'httpCode' => $httpCode
    ];
}

/**
 * Upload file to Supabase Storage
 */
function uploadToSupabase($file, $fileName) {
    $bucket = SUPABASE_BUCKET;
    $endpoint = "/storage/v1/object/{$bucket}/{$fileName}";
    
    $fileContent = file_get_contents($file['tmp_name']);
    $mimeType = $file['type'];
    
    $result = supabaseRequest($endpoint, 'POST', $fileContent, $mimeType);
    
    if ($result['httpCode'] === 200 || $result['httpCode'] === 201) {
        // Return public URL
        return SUPABASE_URL . "/storage/v1/object/public/{$bucket}/{$fileName}";
    }
    
    return false;
}

/**
 * Insert data into Supabase Database
 */
function insertToSupabase($data) {
    $endpoint = "/rest/v1/" . SUPABASE_TABLE;
    
    // Add Prefer header for returning data
    $url = SUPABASE_URL . $endpoint;
    
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation'
    ];
    
    $jsonData = json_encode($data);
    
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => implode("\r\n", $headers),
            'content' => $jsonData,
            'ignore_errors' => true,
            'timeout' => 30
        ]
    ];
    
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    
    // Get HTTP response code
    $httpCode = 0;
    if (isset($http_response_header)) {
        if (preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response_header[0], $matches)) {
            $httpCode = intval($matches[1]);
        }
    }
    
    if ($httpCode === 201) {
        return json_decode($response, true);
    }
    
    return false;
}
?>
