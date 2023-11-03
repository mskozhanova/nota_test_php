<?php
/**
 * This script finds and displays files in the /datafiles folder with names consisting of numbers
 * and letters of the Latin alphabet and having the .ixt extension, ordered by name.
 */

// Define the directory path
$directory = '/datafiles';

// Check if the directory exists
if (!is_dir($directory)) {
    echo "Directory '$directory' does not exist.";
    exit;
}

// Create a regular expression pattern to match the desired file names
$pattern = '/^[a-zA-Z0-9]+\.ixt$/';

// Initialize an array to store matching file names
$matchingFiles = [];

// Open the directory and loop through its contents
if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle)) {
        if (preg_match($pattern, $file)) {
            $matchingFiles[] = $file;
        }
    }
    closedir($handle);
}

// Sort the matching files alphabetically
sort($matchingFiles);

// Display the sorted file names
foreach ($matchingFiles as $file) {
    echo $file . "\n";
}
?>
