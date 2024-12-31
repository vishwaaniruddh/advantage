<?php include('./config.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];
    $remark = $_POST['remark'];

    $sql = "UPDATE images SET status = '$action', remark = '$remark' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Image status updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$sql = "SELECT * FROM images WHERE status = 'Pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Action</th>
            <th>Remark</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"]. "</td>
                        <td><img src='" . $row["file_path"]. "' width='100'></td>
                        <td>
                            <form method='post'>
                                <input type='hidden' name='id' value='" . $row["id"] . "'>
                                <select name='action'>
                                    <option value='Approved'>Approve</option>
                                    <option value='Rejected'>Reject</option>
                                </select>
                                <textarea name='remark' placeholder='Enter remark'></textarea>
                                <input type='submit' value='Submit'>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No images pending approval.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
