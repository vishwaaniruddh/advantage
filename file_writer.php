<?php
function writeToFile($filepath, $content) {
    // Open the file for writing (overwrite mode)
    $file = fopen($filepath, 'w');
    
    if ($file) {
        // Write the content to the file
        fwrite($file, $content);
        
        // Close the file
        fclose($file);

        return true; // Success
    } else {
        return false; // Unable to open the file
    }
}

?>
