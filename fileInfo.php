<?php
function getFolderSize($path) {
    $totalSize = 0;

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $file) {
        if ($file->isFile()) {
            $totalSize += $file->getSize();
        }
    }

    return $totalSize;
}

$directory = __DIR__.'/'; // This will set the directory to the current script's directory

$folders = array_filter(scandir($directory), function($item) use ($directory) {
    return is_dir($directory . '/' . $item) && $item != '.' && $item != '..';
});

foreach ($folders as $folder) {
    $folderPath = $directory . '/' . $folder;
    $size = getFolderSize($folderPath);

    echo "Folder: $folder, Size: " . formatBytes($size) . "<br>";
}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>
