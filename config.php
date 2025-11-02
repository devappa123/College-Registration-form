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
    
    // Build headers for PowerShell
    $headersList = [
        "apikey" => $apiKey,
        "Authorization" => "Bearer {$apiKey}"
    ];
    
    if ($contentType) {
        $headersList["Content-Type"] = $contentType;
    }
    
    // Convert to PowerShell hashtable syntax
    $headerParts = [];
    foreach ($headersList as $key => $value) {
        $headerParts[] = "'{$key}'='{$value}'";
    }
    $headers = "@{" . implode(';', $headerParts) . "}";
    
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
    $url = SUPABASE_URL . "/storage/v1/object/{$bucket}/{$fileName}";
    $apiKey = SUPABASE_KEY;
    $mimeType = $file['type'];
    $filePath = $file['tmp_name'];
    
    // Build headers for file upload
    $headers = "@{'apikey'='{$apiKey}';'Authorization'='Bearer {$apiKey}';'Content-Type'='{$mimeType}';'x-upsert'='false'}";
    
    // Use PowerShell to upload file
    $cmd = "powershell -Command \"Invoke-WebRequest -Uri '{$url}' -Headers {$headers} -Method POST -InFile '{$filePath}' -ContentType '{$mimeType}' -UseBasicParsing\"";
    
    $output = shell_exec($cmd . " 2>&1");
    
    // Check if upload was successful (PowerShell doesn't return much on success)
    // If there's no error output, consider it successful
    if ($output !== null && stripos($output, 'error') === false && stripos($output, 'exception') === false) {
        // Return public URL
        return SUPABASE_URL . "/storage/v1/object/public/{$bucket}/{$fileName}";
    }
    
    // Log error for debugging
    error_log("File upload failed for {$fileName}: " . $output);
    
    return false;
}

/**
 * Insert data into Supabase Database
 */
function insertToSupabase($data) {
    $url = SUPABASE_URL . "/rest/v1/" . SUPABASE_TABLE;
    $apiKey = SUPABASE_KEY;
    
    // Build headers with Prefer header
    $headers = "@{'apikey'='{$apiKey}';'Authorization'='Bearer {$apiKey}';'Content-Type'='application/json';'Prefer'='return=representation'}";
    
    $jsonData = json_encode($data);
    
    // Escape JSON for PowerShell
    $escapedData = str_replace('"', '`"', $jsonData);
    $escapedData = str_replace('$', '`$', $escapedData);
    $escapedData = str_replace("'", "`'", $escapedData);
    
    // Use a temp file for complex JSON to avoid escaping issues
    $tempFile = sys_get_temp_dir() . '\\data_' . uniqid() . '.json';
    file_put_contents($tempFile, $jsonData);
    
    $cmd = "powershell -Command \"\$body = Get-Content -Path '{$tempFile}' -Raw; Invoke-WebRequest -Uri '{$url}' -Headers {$headers} -Method POST -Body \$body -UseBasicParsing | Select-Object -ExpandProperty Content\"";
    
    $output = shell_exec($cmd);
    
    // Clean up temp file
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
    
    if ($output !== null && $output !== false && trim($output) !== '') {
        $decoded = json_decode(trim($output), true);
        if ($decoded !== null) {
            return $decoded;
        }
        return true; // Success even if can't decode response
    }
    
    return false;
}
?>
