<?php

$host = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_NAME');

try {
    // Connecting to the database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $url = 'https://www.wikipedia.org/';

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL and get the page content
    $page_content = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Create a DOMDocument to parse the HTML content
    $dom = new DOMDocument;
    @$dom->loadHTML($page_content);

    // Extract headings, abstracts, pictures, and links from the page
    $headings = [];
    $abstracts = [];
    $pictures = [];
    $links = [];

    $sections = $dom->getElementsByTagName('section');
    foreach ($sections as $section) {
        $heading = $section->getElementsByTagName('h2')->item(0)->nodeValue;
        $abstract = $section->getElementsByTagName('p')->item(0)->nodeValue;
        $img = $section->getElementsByTagName('img')->item(0)->getAttribute('src');
        $link = $section->getElementsByTagName('a')->item(0)->getAttribute('href');

        $headings[] = $heading;
        $abstracts[] = $abstract;
        $pictures[] = $img;
        $links[] = $link;
    }

    // Prepare and execute SQL queries to insert data into the database
    $insertStatement = $pdo->prepare("INSERT INTO wiki_sections (date_created, title, url, picture, abstract) VALUES (NOW(), ?, ?, ?, ?)");

    for ($i = 0; $i < count($headings); $i++) {
        $insertStatement->execute([$headings[$i], $links[$i], $pictures[$i], $abstracts[$i]]);
    }

    echo 'Data has been successfully scraped and saved to the database.';
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}
?>
