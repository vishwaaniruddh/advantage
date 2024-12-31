<?php
$host = 'localhost';
$username = 'advantage';
$password = 'qwerty121';
$database = 'sarmicrosystems_advantage';


$backupFolder = 'database_backup';
if (!is_dir($backupFolder)) {
    mkdir($backupFolder,0777, true);
}

$currentYear = date('Y');
$currentMonth = date('M');

$yearFolder = $backupFolder . '/' . $currentYear;
if (!is_dir($yearFolder)) {
    mkdir($yearFolder,0777, true);
}

$monthFolder = $yearFolder . '/' . $currentMonth;
if (!is_dir($monthFolder)) {
    mkdir($monthFolder,0777, true);
}

$currentDate = date('dMY');

$backupFileName = $currentDate . '_sarmicrosystems_advantage.sql';

$backupFilePath = $monthFolder . '/' . $backupFileName;

$command = "C:\\xampp\\mysql\\bin\\mysqldump.exe --host={$host} --user={$username} --password={$password} {$database} > {$backupFilePath}";

exec($command, $output, $returnValue);

if ($returnValue === 0) {
    echo "Backup created successfully and saved to {$backupFilePath}";


    // Change directory to the root of your Git repository (assuming htdocs is the root)
    chdir('C:/xampp/htdocs');

    // Git commands
    $gitAdd = "git add .";
    $gitCommit = 'git commit -m "auto backup (' . date('Y-m-d') . ')"';
    $gitPush = "git push -u origin main";

    // Execute Git commands
    exec($gitAdd, $outputAdd, $returnValueAdd);
    sleep(20);
    exec($gitCommit, $outputCommit, $returnValueCommit);
    sleep(20);
    exec($gitPush, $outputPush, $returnValuePush);

    // Check for success or failure
    if ($returnValueAdd === 0) {
        echo "'git add .' completed successfully.";
        if ($returnValueCommit === 0) {
            echo "'git commit' completed successfully.";
            if ($returnValuePush === 0) {
                echo "'git push' completed successfully.";
            } else {
                echo "Error pushing to Git: " . implode("\n", $outputPush) . " (Return value: {$returnValuePush})";
            }
        } else {
            echo "Error committing to Git: " . implode("\n", $outputCommit) . " (Return value: {$returnValueCommit})";
        }
    } else {
        echo "Error adding changes to Git: " . implode("\n", $outputAdd) . " (Return value: {$returnValueAdd})";
    }
} else {
    echo "Error creating backup: " . implode("\n", $output) . " (Return value: {$returnValue})";
}
