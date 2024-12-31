<? include ('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">


        <h2>Add Dependency</h2>
        <form action="submit.php" method="post">
            <label for="existing_dependency">Select Existing Dependency:</label><br>
            <select id="existing_dependency" name="existing_dependency" class="form-control">
                <option value="">-- Select Dependency --</option>
                <?php
                // Fetch existing dependencies from the database and populate the dropdown
                $sql_fetch_dependencies = "SELECT * FROM dependency_master";
                $result = $con->query($sql_fetch_dependencies);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['dependency_name'] . "</option>";
                    }
                }
                ?>
                <option value="new_dependency">Create New Dependency</option>
            </select><br><br>
            <div id="details"></div>
            <input type="submit" value="Submit">
        </form>

        <script>
            document.getElementById("existing_dependency").addEventListener("change", function () {
                $("#details").html('');

                if (this.value === "new_dependency") {

                    var html = `<input type="hidden" name="dependencyType" value="new" /> <div id="new_dependency_fields" style="display: none;">
                <label for="new_dependency_name">New Dependency Name:</label><br>
                <input type="text" class="form-control" id="new_dependency_name" name="new_dependency_name"><br>
                <label for="dependency_type">Dependency Type:</label><br>
                <input type="text" class="form-control" id="dependency_type" name="dependency_type"><br><br>
            </div>`
                    $("#details").html(html);
                    document.getElementById("new_dependency_fields").style.display = "block";
                } else if (this.value !== "") {

                    var html = ` <input type="hidden" name="dependencyType" value="existing" /> 
                    
                    <div id="new_dependency_fields" style="display: none;">
                <input type="hidden" id="dependencyId" name="dependencyId" value="${this.value}"><br>
                <label for="dependency_type">Dependency Type:</label><br>
                <input type="text" class="form-control" id="dependency_type" name="dependency_type"><br><br>
            </div>`
                    $("#details").html(html);


                    document.getElementById("new_dependency_fields").style.display = "block";
                } else {
                    // Otherwise, hide the input fields
                    document.getElementById("new_dependency_fields").style.display = "none";
                }
            });
        </script>



    </div>
</div>

<div class="card">
                                    <div class="card-body" style="overflow:auto;">
                                        <table id="example" class="table table-bordered table-striped table-hover dataTable js-exportable no-footer" style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>#</th>
                                                    <th>Dependency Name</th>
                                                    <th>Dependency Type</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?
                                                $i= 1; 
                                                $sql = mysqli_query($con,"select a.dependency_name,b.dependency_value from dependency_master a LEFT JOIN dependency_details b 
                                                ON a.id = b.master_dependency_id
                                                where a.status='Active'");
                                                while($sql_result = mysqli_fetch_assoc($sql)){ 

                                                ?>
                                                    <tr>
                                                        <td><? echo $i; ?></td>
                                                        <td><? echo $sql_result['dependency_name']; ?></td>
                                                        <td><? echo $sql_result['dependency_value']; ?></td>


                                                    </tr>    
                                                <? $i++; }?>
                                                
                                            </tbody>
                                    </div>
                                </div>



<? include ('../footer.php'); ?>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="../datatable/jquery.dataTables.js"></script>
<script src="../datatable/dataTables.bootstrap.js"></script>
<script src="../datatable/dataTables.buttons.min.js"></script>
<script src="../datatable/buttons.flash.min.js"></script>
<script src="../datatable/jszip.min.js"></script>

<script src="../datatable/pdfmake.min.js"></script>
<script src="../datatable/vfs_fonts.js"></script>
<script src="../datatable/buttons.html5.min.js"></script>
<script src="../datatable/buttons.print.min.js"></script>
<script src="../datatable/jquery-datatable.js"></script>