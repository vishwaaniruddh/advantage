
<? include('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">


<?

    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $existing_dependency = $_POST['existing_dependency'];
    $new_dependency_name = $_POST['new_dependency_name'];
    $dependency_type = $_POST['dependency_type'];

    if ($existing_dependency == "new_dependency") {
        $sql_insert_master = "INSERT INTO dependency_master (dependency_name, status, created_at, created_by) 
                              VALUES ('$new_dependency_name', 'Active', NOW(), 'user')";
        $result_insert_master = $con->query($sql_insert_master);
        if (!$result_insert_master) {
            echo "Error inserting new dependency master: " . $con->error;
            exit;
        }

        // Retrieve the ID of the newly inserted dependency master
        $new_dependency_id = $con->insert_id;

        // Insert new dependency details
        $sql_insert_details = "INSERT INTO dependency_details (master_dependency_id, dependency_value, status) 
                               VALUES ('$new_dependency_id', '$dependency_type', 'Active')";
        $result_insert_details = $con->query($sql_insert_details);
        if (!$result_insert_details) {
            echo "Error inserting new dependency details: " . $con->error;
            exit;
        }

        echo "New dependency added successfully!";
    } else {

        $dependencyId = $_POST['dependencyId'];

        
        // Retrieve the ID of the newly inserted dependency master
        $new_dependency_id = $dependencyId ; 

        // Insert new dependency details
        $sql_insert_details = "INSERT INTO dependency_details (master_dependency_id, dependency_value, status) 
                               VALUES ('$new_dependency_id', '$dependency_type', 'Active')";
        $result_insert_details = $con->query($sql_insert_details);
        if (!$result_insert_details) {
            echo "Error inserting new dependency details: " . $con->error;
            exit;
        }

        echo "New dependency added successfully!";


    }


}

// Close database connection
$con->close();
?>

<a href="./dependency.php">Go Back</a>


    </div>
</div>


<? include('../footer.php'); ?>

