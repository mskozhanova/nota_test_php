<?php
require 'vendor/autoload.php';

use Sunra\PhpSimple\HtmlDomParser;

/**
 * Downloads the content of a web page, extracts headings, abstracts, pictures, and links
 * from specific sections, and saves it in the 'wiki_sections' table.
 *
 * @author Roshan Kumar
 */

$dsn = 'mysql:host=localhost;dbname=databasename';
$username = 'username';
$password = 'password';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$url = 'https://www.wikipedia.org/';
$html = file_get_contents($url);
$dom = HtmlDomParser::str_get_html($html);

foreach ($dom->find('div#sections') as $section) {
    $title = $section->find('h2', 0)->plaintext;
    $abstract = $section->find('p', 0)->plaintext;
    $picture = $section->find('img', 0)->src;
    $links = $section->find('a');

    $query = $pdo->prepare("INSERT INTO wiki_sections (date_created, title, url, picture, abstract) VALUES (?, ?, ?, ?, ?)");
    $dateCreated = date('Y-m-d H:i:s');
    $query->execute([$dateCreated, $title, $url, $picture, $abstract]);

    foreach ($links as $link) {
        $linkText = $link->plaintext;
        $linkUrl = $link->href;
    }
}

$pdo = null;
$dom->clear();
?>
