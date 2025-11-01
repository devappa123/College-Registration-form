<?php
/**
 * Supabase Configuration (Using PowerShell for HTTP requests)
 * Workaround for PHP SSL issues on Windows
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
 * Helper function to make HTTP requests using PowerShell
 */
function supabaseRequest($endpoint, $method = 'GET', $data = null, $contentType = 'application/json') {
    $url = SUPABASE_URL . $endpoint;
    $apiKey = SUPABASE_KEY;
    
    // Escape data for PowerShell
    $headers = "@{\"apikey\"=\"{$apiKey}\";\"Authorization\"=\"Bearer {$apiKey}\"";
    
    if ($contentType) {
        $headers .= ";\"Content-Type\"=\"{$contentType}\"";
    }
    
    $headers .= "}";
    
    // Build PowerShell command
    if ($method === 'GET') {
        $cmd = "powershell -Command \"Invoke-WebRequest -Uri '{$url}' -Headers {$headers} -Method GET -UseBasicParsing | Select-Object -ExpandProperty Content\"";
    } else if ($method === 'POST') {
        // For file uploads, save data to temp file
        if ($contentType !== 'application/json') {
            $tempFile = sys_get_temp_dir() . '\\upload_' . uniqid() . '.tmp';
            file_put_contents($tempFile, $data);
            
            $cmd = "powershell -Command \"Invoke-WebRequest -Uri '{$url}' -Headers {$headers} -Method POST -InFile '{$tempFile}' -ContentType '{$contentType}' -UseBasicParsing | Select-Object -ExpandProperty Content\"";
        } else {
            // For JSON, escape and inline
            $escapedData = str_replace('"', '`"', $data);
            $escapedData = str_replace('$', '`$', $escapedData);
            
            $cmd = "powershell -Command \"Invoke-WebRequest -Uri '{$url}' -Headers {$headers} -Method POST -Body '{$escapedData}' -ContentType 'application/json' -UseBasicParsing | Select-Object -ExpandProperty Content\"";
        }
    }
    
    // Execute command
    $output = shell_exec($cmd);
    
    // Clean up temp file if created
    if (isset($tempFile) && file_exists($tempFile)) {
        unlink($tempFile);
    }
    
    // Determine HTTP code (201 for POST success, 200 for GET success)
    $httpCode = 0;
    if ($output !== null && $output !== false) {
        $httpCode = ($method === 'POST') ? 201 : 200;
    }
    
    return [
        'response' => $output,
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
    
    $result = supabaseRequest($endpoint, 'POST', $jsonData, 'application/json');
    
    if ($result['httpCode'] === 201 || $result['httpCode'] === 200) {
        return json_decode($result['response'], true);
    }
    
    return false;
}
?>
