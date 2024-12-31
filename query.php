
<!DOCTYPE html>
<html>
<head>
    <title>SQL Query Executor</title>
</head>
<body>
<?
    // return ; 
    ?>    
<h1>SQL Query Executor</h1>
    
    <form method="post" action="">
        <label for="sqlQuery">Enter your SQL query:</label><br>
        <textarea id="sqlQuery" name="sqlQuery" rows="4" style="width:100%;"><?= $_REQUEST['sqlQuery']; ?></textarea><br><br>
        <input type="submit" name="execute" value="Execute">
    </form>

    <?php
    
    // Check if the form is submitted
    if (isset($_POST['execute'])) {
include('config.php');
        // Get the SQL query from the form
        $sql = $_POST['sqlQuery'];

echo $sql ; 
echo '<br>';
        // Replace this with your MySQL database credentials
       
        // Execute the SQL query
        $result = $con->query($sql);

        if ($result) {
            echo "<h2>Query Result:</h2>";
            echo "<table border='1'>";
            // Display column names as table headers
            echo "<tr>";
            while ($fieldInfo = $result->fetch_field()) {
                echo "<th>{$fieldInfo->name}</th>";
            }
            echo "</tr>";

            // Display data rows
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>{$value}</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Error executing the query: " . $con->error;
        }

        // Close the database connection
        $con->close();
    }
    ?>
</body>
</html>
