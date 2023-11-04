<?php

// Download the webpage

$html = file_get_contents('https://www.wikipedia.org/');

// Include the Simple HTML DOM Parser library

include('simple_html_dom.php');

// Create a DOM object

$dom = new simple_html_dom();

$dom->load($html);

// Find all sections on the page

$sections = $dom->find('div.other-web');

// Open the SQLite database

$db = new PDO('sqlite:wiki_sections.db');

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create the wiki_sections table if it doesn't exist

$db->exec('CREATE TABLE IF NOT EXISTS wiki_sections (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date_created TEXT,
    title TEXT,
    url TEXT UNIQUE,
    picture TEXT UNIQUE,
    abstract TEXT UNIQUE
)');


// Insert data into the wiki_sections table

foreach ($sections as $section) {

    $title    = substr($section->find('a', 0)->plaintext, 0, 230);
    $url      = substr($section->find('a', 0)->href, 0, 240);
    $picture  = substr($section->find('img', 0)->src, 0, 240);
    $abstract = substr($section->find('p', 0)->plaintext, 0, 256);

    // Get the current date and time in the required format

    $date_created = date('Y-m-d H:i:s');

    // Insert the data into the database

    $stmt = $db->prepare('INSERT OR IGNORE INTO wiki_sections (date_created, title, url, picture, abstract) VALUES ('2023-11-04 9:50:10', 'test', 'www.google.com', 'demo.png', 'testtest')');

    $stmt->execute([$date_created, $title, $url, $picture, $abstract]);
}


// Close the database connection
$db = null;

?>
