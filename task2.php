<?php
// Database connection settings
$servername = "your_database_host";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// URL of the Wikipedia page to download
$url = "https://www.wikipedia.org/";

// Download the page content using cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$pageContent = curl_exec($curl);
curl_close($curl);

// Create a DOMDocument to parse the HTML
$dom = new DOMDocument();
libxml_use_internal_errors(true); // Disable error reporting
$dom->loadHTML($pageContent);
libxml_clear_errors();

// Extract and save headings, abstracts, pictures, and links
$sections = $dom->getElementsByTagName('section'); // Adjust the tag as needed
foreach ($sections as $section) {
    $title = $section->getElementsByTagName('h2')->item(0)->textContent;
    $abstract = $section->getElementsByTagName('p')->item(0)->textContent;
    $image = $section->getElementsByTagName('img')->item(0)->getAttribute('src');
    $link = $section->getElementsByTagName('a')->item(0)->getAttribute('href');

    // Insert data into the database
    $sql = "INSERT INTO wiki_sections (title, url, picture, abstract, date_created) 
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $link, $image, $abstract);
    $stmt->execute();
}

// Close the database connection
$conn->close();
