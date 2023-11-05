<?php
/**
 * Find files in the /datafiles folder with names consisting of numbers and letters of the Latin alphabet,
 * having the .ixt extension, and display the names of these files ordered by name.
 */

// Directory path to search for files
$directory = '/datafiles';

// Regular expression pattern to match files with names consisting of numbers and Latin alphabet letters with .ixt extension
$pattern = '/^[A-Za-z0-9]+\.ixt$/';

// Initialize an array to store file names that match the criteria
$matchingFiles = [];

// Get all files in the directory
$files = scandir($directory);

// Iterate through the files and match the pattern
foreach ($files as $file) {
    if (preg_match($pattern, $file) && is_file($directory . '/' . $file)) {
        $matchingFiles[] = $file; // Add the matching file to the array
    }
}

// Sort the matching file names alphabetically
sort($matchingFiles);

// Display the sorted file names
foreach ($matchingFiles as $file) {
    echo $file . PHP_EOL; // Display each file name
}
