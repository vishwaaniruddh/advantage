<?php include('./config.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM images WHERE status != 'Pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Status</title>
</head>
<body>
    <h1>Upload Status</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Status</th>
            <th>Remark</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"]. "</td>
                        <td><img src='" . $row["file_path"]. "' width='100'></td>
                        <td>" . $row["status"]. "</td>
                        <td>" . $row["remark"]. "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No images uploaded yet.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
