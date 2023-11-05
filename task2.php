<?php
/**
 * Script to download a Wikipedia page, extract content, and store it in a database table.
 */

// Database connection settings
$host = 'your_host';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // URL of the Wikipedia page
    $url = 'https://www.wikipedia.org/';

    // Fetch the page content
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $pageContent = curl_exec($curl);
    curl_close($curl);

    // Create a DOMDocument object and load the page content
    $dom = new DOMDocument();
    @$dom->loadHTML($pageContent);

    // Extract sections, headings, abstracts, pictures, and links
    $sections = $dom->getElementsByTagName('section');

    // Prepare and execute SQL insert query for each section
    $stmt = $pdo->prepare("INSERT INTO wiki_sections (date_created, title, url, picture, abstract) VALUES (NOW(), :title, :url, :picture, :abstract)");

    foreach ($sections as $section) {
        $title = $section->getElementsByTagName('h2')->item(0)->nodeValue;
        $abstract = $section->getElementsByTagName('p')->item(0)->nodeValue;

        $links = $section->getElementsByTagName('a');
        $url = '';
        foreach ($links as $link) {
            $url = $link->getAttribute('href');
            break;
        }

        $pictures = $section->getElementsByTagName('img');
        $picture = '';
        foreach ($pictures as $pic) {
            $picture = $pic->getAttribute('src');
            break;
        }

        // Execute the SQL insert query
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':url', $url);
        $stmt->bindParam(':picture', $picture);
        $stmt->bindParam(':abstract', $abstract);
        $stmt->execute();
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
