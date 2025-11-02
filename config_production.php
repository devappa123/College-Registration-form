<?php
/**
 * Supabase Configuration - Production Version
 * Uses cURL for maximum compatibility
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
 * Make HTTP request to Supabase using cURL
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
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    
    // SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        error_log("cURL Error: $error");
        return ['response' => false, 'httpCode' => 0, 'error' => $error];
    }
    
    return [
        'response' => $response,
        'httpCode' => $httpCode,
        'error' => null
    ];
}

/**
 * Upload file to Supabase Storage
 */
function uploadToSupabase($file, $fileName) {
    $bucket = SUPABASE_BUCKET;
    $endpoint = "/storage/v1/object/{$bucket}/{$fileName}";
    $url = SUPABASE_URL . $endpoint;
    
    $fileContent = file_get_contents($file['tmp_name']);
    $mimeType = $file['type'];
    
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: ' . $mimeType,
        'x-upsert: false'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        error_log("File upload error for {$fileName}: $error");
        return false;
    }
    
    if ($httpCode === 200 || $httpCode === 201) {
        return SUPABASE_URL . "/storage/v1/object/public/{$bucket}/{$fileName}";
    }
    
    error_log("File upload failed for {$fileName}. HTTP Code: $httpCode, Response: $response");
    return false;
}

/**
 * Insert data into Supabase Database
 */
function insertToSupabase($data) {
    $endpoint = "/rest/v1/" . SUPABASE_TABLE;
    $url = SUPABASE_URL . $endpoint;
    
    $jsonData = json_encode($data);
    
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        error_log("Database insert error: $error");
        return false;
    }
    
    if ($httpCode === 201) {
        return json_decode($response, true);
    }
    
    error_log("Database insert failed. HTTP Code: $httpCode, Response: $response");
    return false;
}
?>
