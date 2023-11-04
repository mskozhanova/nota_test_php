<?php
/**
 * Find files in the /datafiles folder with specific criteria and display them.
 *
 * This script searches for files in the /datafiles folder with names consisting of numbers and letters of the Latin alphabet,
 * and having the .ixt extension. It then displays the names of these files ordered by name.
 */

// Directory where files are located
$directory = '/datafiles';

// Regular expression pattern to match filenames
$pattern = '/^[a-zA-Z0-9]+\.ixt$/';

// Initialize an empty array to store matching filenames
$matchingFiles = array();

// Check if the directory exists
if (is_dir($directory)) {
    // Open the directory
    if ($handle = opendir($directory)) {
        // Read through the files in the directory
        while (false !== ($file = readdir($handle)) ) {
            // Check if the file matches the pattern
            if (preg_match($pattern, $file)) {
                $matchingFiles[] = $file; // Add the matching file to the array
            }
        }
        closedir($handle); // Close the directory
    }

    // Sort the matching files alphabetically
    sort($matchingFiles);

    // Display the matching files
    echo "Matching files in $directory with .ixt extension:\n";
    foreach ($matchingFiles as $file) {
        echo "$file\n";
    }
} else {
    echo "Directory $directory does not exist.";
}
?>
