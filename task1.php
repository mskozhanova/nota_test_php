<?php
/**
 * Find and display files in the /datafiles folder with .ixt extension.
 * Files are filtered by names consisting of numbers and Latin alphabet letters.
 *
 * @param string $directory The directory to search for files.
 * @return array An array of file names that match the criteria.
 */
function findAndDisplayIxtFiles($directory) {
    // Create an array to store file names that match the criteria.
    $matchingFiles = [];

    // Open the directory and read its contents.
    if ($handle = opendir($directory)) {
        while (false !== ($file = readdir($handle)) ) {
            // Use a regular expression to match file names with numbers and Latin letters, ending with .ixt extension.
            if (preg_match('/^[a-zA-Z0-9]+\.ixt$/', $file)) {
                $matchingFiles[] = $file;
            }
        }
        closedir($handle);

        // Sort the matching files alphabetically.
        sort($matchingFiles);

        // Display the matching file names.
        foreach ($matchingFiles as $file) {
            echo $file . PHP_EOL;
        }

        return $matchingFiles;
    } else {
        return [];
    }
}

$directory = 'C:/nota_test_php/datafiles'; 
$matchingFiles = findAndDisplayIxtFiles($directory);

// Example: Display the count of matching files.
echo 'Total matching files: ' . count($matchingFiles);

