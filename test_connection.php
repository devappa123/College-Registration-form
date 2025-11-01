<?php
/**
 * Test Supabase Connection
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing Supabase Connection</h1>";

// Include config
require_once 'config.php';

echo "<h2>1. Configuration Check</h2>";
echo "SUPABASE_URL: " . SUPABASE_URL . "<br>";
echo "SUPABASE_KEY: " . substr(SUPABASE_KEY, 0, 20) . "...<br>";
echo "SUPABASE_BUCKET: " . SUPABASE_BUCKET . "<br>";
echo "SUPABASE_TABLE: " . SUPABASE_TABLE . "<br>";

// Test if allow_url_fopen is enabled
echo "<h2>2. PHP Configuration</h2>";
echo "allow_url_fopen: " . (ini_get('allow_url_fopen') ? 'YES ✓' : 'NO ✗') . "<br>";

// Test Supabase connection
echo "<h2>3. Testing Supabase Connection</h2>";
echo "Testing URL: " . SUPABASE_URL . "/rest/v1/" . SUPABASE_TABLE . "?limit=1<br>";

// Capture errors
$lastError = '';
set_error_handler(function($errno, $errstr) use (&$lastError) {
    $lastError = $errstr;
});

$testEndpoint = "/rest/v1/" . SUPABASE_TABLE . "?limit=1";
$result = supabaseRequest($testEndpoint, 'GET', null);

restore_error_handler();

echo "HTTP Code: " . $result['httpCode'] . "<br>";
if ($lastError) {
    echo "<strong style='color: red;'>Error: " . htmlspecialchars($lastError) . "</strong><br>";
}
echo "Response: <pre>" . htmlspecialchars($result['response']) . "</pre><br>";

if ($result['httpCode'] === 200) {
    echo "<strong style='color: green;'>✓ Connection Successful!</strong><br>";
} else if ($result['httpCode'] === 404) {
    echo "<strong style='color: red;'>✗ Table 'admissions' not found!</strong><br>";
    echo "Please run the SQL to create the table.<br>";
} else if ($result['httpCode'] === 401 || $result['httpCode'] === 403) {
    echo "<strong style='color: red;'>✗ Authentication Failed!</strong><br>";
    echo "Check your SUPABASE_KEY in config.php<br>";
} else if ($result['httpCode'] === 0) {
    echo "<strong style='color: red;'>✗ Cannot connect to Supabase!</strong><br>";
    echo "Check your internet connection and SUPABASE_URL<br>";
} else {
    echo "<strong style='color: orange;'>⚠ Unexpected response</strong><br>";
}

// Test storage bucket
echo "<h2>4. Testing Storage Bucket</h2>";
$storageEndpoint = "/storage/v1/bucket/" . SUPABASE_BUCKET;
$storageResult = supabaseRequest($storageEndpoint, 'GET', null);

echo "HTTP Code: " . $storageResult['httpCode'] . "<br>";

if ($storageResult['httpCode'] === 200) {
    echo "<strong style='color: green;'>✓ Bucket exists!</strong><br>";
} else if ($storageResult['httpCode'] === 404) {
    echo "<strong style='color: red;'>✗ Bucket 'admission_docs' not found!</strong><br>";
    echo "Please create the bucket in Supabase Storage.<br>";
} else {
    echo "<strong style='color: orange;'>⚠ HTTP Code: " . $storageResult['httpCode'] . "</strong><br>";
    echo "Response: <pre>" . htmlspecialchars($storageResult['response']) . "</pre>";
}

echo "<h2>Summary</h2>";
if ($result['httpCode'] === 200 && $storageResult['httpCode'] === 200) {
    echo "<strong style='color: green; font-size: 20px;'>✓ Everything looks good! You can submit the form.</strong>";
} else {
    echo "<strong style='color: red; font-size: 20px;'>✗ Fix the issues above before submitting the form.</strong>";
}
?>
