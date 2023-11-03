<?php
// Define the directory where you want to search for files.
$directory = '/datafiles';

// Define the regular expression pattern to match files with names consisting of Latin letters and numbers and ending with .ixt.
$pattern = '/^[a-zA-Z0-9]+\.ixt$/';

// Initialize an array to store matching file names.
$matchingFiles = [];

// Check if the directory exists and is readable.
if (is_dir($directory) && is_readable($directory)) {
    // Open the directory.
    if ($handle = opendir($directory)) {
        // Loop through each file in the directory.
        while (false !== ($file = readdir($handle))) {
            // Use preg_match to check if the file name matches the defined pattern.
            if (preg_match($pattern, $file)) {
                // Add the matching file to the array.
                $matchingFiles[] = $file;
            }
        }

        // Close the directory handle.
        closedir($handle);

        // Sort the matching file names alphabetically.
        sort($matchingFiles);

        // Display the sorted file names.
        echo "Matching Files:\n";
        foreach ($matchingFiles as $filename) {
            echo $filename . "\n";
        }
    } else {
        echo "Error opening directory: $directory\n";
    }
} else {
    echo "Directory not found or not readable: $directory\n";
}
