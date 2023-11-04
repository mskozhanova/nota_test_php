<?php


// Define the folder path name

$folderPath = '/datafiles';

// Define the regex pattern for check file names 

$pattern = '/^[a-zA-Z0-9]+\.ixt$/';


// Initialize an array to store the matching file names

$matchingFiles = array();

// Open the directory

if ($handle = opendir($folderPath)) {

    // Iterate file in the directory

    while (false !== ($file = readdir($handle))) {

        // Check the file name matches or not in the pattern

        if (preg_match($pattern, $file)) {

            // Add the matching filename to the array

            $matchingFiles[] = $file;
        }
    }

    // Close the directory handle

    closedir($handle);
}

// Sort the array of matching files

sort($matchingFiles);

// Display the sorted filenames

foreach ($matchingFiles as $file) {

    echo $file . "\n";
}

?>

