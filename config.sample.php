<?php
/**
 * Supabase Configuration Template
 * 
 * INSTRUCTIONS:
 * 1. Copy this file and rename it to 'config.php'
 * 2. Replace the placeholder values below with your actual Supabase credentials
 * 3. Save the file
 * 
 * HOW TO GET YOUR CREDENTIALS:
 * 1. Go to your Supabase project dashboard (https://app.supabase.com)
 * 2. Navigate to Settings → API
 * 3. Copy the following:
 *    - Project URL (looks like: https://xxxxx.supabase.co)
 *    - anon/public key (long string of characters)
 * 
 * SECURITY NOTE:
 * - Never commit config.php to version control
 * - Keep your API keys secure
 * - Use environment variables in production
 */

// Supabase Project URL
// Example: https://abcdefghijklmnop.supabase.co
define('SUPABASE_URL', 'https://your-project-id.supabase.co');

// Supabase Anon/Public Key
// This is a long string found in Settings → API → Project API keys → anon public
define('SUPABASE_KEY', 'your-anon-public-key-here');

// Supabase Storage Bucket Name
// You must create this bucket in Supabase Storage section
define('SUPABASE_BUCKET', 'admission_docs');

// Database Table Name
// You must create this table using the SQL provided in README.md
define('SUPABASE_TABLE', 'admissions');

/**
 * Helper function to make HTTP requests to Supabase
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
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
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
    
    $jsonData = json_encode($data);
    
    $result = supabaseRequest($endpoint, 'POST', $jsonData);
    
    if ($result['httpCode'] === 201) {
        return json_decode($result['response'], true);
    }
    
    return false;
}
?>
