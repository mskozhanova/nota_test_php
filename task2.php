<?php
/**
 * Download data from https://www.wikipedia.org/, extract headings, abstracts, pictures, and links
 * from sections, and save it in the wiki_sections table in a MySQL database.
 */

// Database connection details
$databaseHost = 'your_database_host';
$databaseName = 'your_database_name';
$databaseUser = 'your_database_user';
$databasePassword = 'your_database_password';

// Create a new PDO instance for database connection
try {
    $pdo = new PDO("mysql:host=$databaseHost;dbname=$databaseName", $databaseUser, $databasePassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// URL to fetch data from
$url = 'https://www.wikipedia.org/';

// Initialize cURL session to fetch the web page
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("cURL error: " . curl_error($ch));
}

// Parse the HTML content
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($response);

// Extract headings, abstracts, pictures, and links
$sections = $dom->getElementsByTagName('section');
foreach ($sections as $section) {
    // Extract data as needed
    $title = $section->getElementsByTagName('h2')->item(0)->textContent;
    $abstract = $section->getElementsByTagName('p')->item(0)->textContent;
    $picture = ''; // Extract picture URL if applicable
    $url = ''; // Extract URL if applicable

    // Insert the extracted data into the database
    $dateCreated = date("Y-m-d H:i:s");
    $stmt = $pdo->prepare("INSERT INTO wiki_sections (date_created, title, url, picture, abstract) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$dateCreated, $title, $url, $picture, $abstract]);
}

// Close the cURL session
curl_close($ch);
