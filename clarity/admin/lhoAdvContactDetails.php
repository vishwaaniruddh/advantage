<? include('../header.php'); ?>
<link rel="stylesheet" type="text/css" href="../datatable/dataTables.bootstrap.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<link rel="stylesheet" type="text/css" href="../datatable/dataTables.bootstrap.css">
     
          <div class="row">
            <div class="col-12  grid-margin">
     
            <div class="card">
                                    <div class="card-body">
                                    <h4 class="card-title">Add new Record</h4>
                                    <hr>
                                        <form action="process_lhoADVContactDetails.php" method="POST">
                                            <div class="row">
                                                <div class="form-group col-sm-3">
                                                    <label>LHO </label>
                                                    <select name="lhoid" class="form-control" required>
                                                        <option value="">-- Select LHO --</option>
                                                        <?
                                                        $lhoSql = mysqli_query($con,"select * from lho where status=1");
                                                        while($lhoSqlResult = mysqli_fetch_assoc($lhoSql)){
                                                            $lhoid = $lhoSqlResult['id'];
                                                            $lhoName = $lhoSqlResult['lho'];
                                                            ?>
                                                            <option value="<? echo $lhoid; ?>">
                                                                <?= $lhoName; ?>
                                                            </option>
                                                            <?
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label>LHO ADV Contact Person</label>
                                                    <input type="text" name="contactPersonName" class="form-control">
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label>LHO ADV Contact Person No</label>
                                                    <input type="text" name="contactPersonNo" class="form-control">
                                                </div>
                                                <div class="form-group col-sm-3 ">
                                                    <label>LHO ADV Contact Person Email</label>
                                                    <input type="text" name="contactPersonEmail" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label>Designation</label>
                                                    <input type="text" name="designation" class="form-control" />
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>Exclamation Matrix</label>
                                                    <input type="text" name="esc_matrix" class="form-control" placeholder="Level 1 or Level 2 ..." />
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <br>
                                                    <input type="submit" name="submit" class="btn btn-primary">
                                                </div>                                                
                                            </div>

                                        </form>
                                        
                                        
                                    </div>
                                </div>
                                
            </div>
            <div class="col-12  grid-margin">
            <div class="card">
                                    <div class="card-body" style="overflow:auto;">
                                        <table id="example" class="table dataTable js-exportable" style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>#</th>
                                                    <th>LHO</th>
                                                    <th>LHO ADV Contact Person</th>
                                                    <th>LHO ADV Contact Person No</th>
                                                    <th>LHO ADV Contact Person Email</th>
                                                    <th>Designation</th>
                                                    <th>Escalation Matrix</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?
                                                $i= 1; 
                                                $sql = mysqli_query($con,"select a.contactPersonName,a.contactPersonNo,a.contactPersonEmail, 
                                                a.designation, a.esc_matrix,
                                                b.lho
                                                from lhoADVContactDetails a 
                                                INNER JOIN lho b ON a.lhoid = b.id where a.status=1 and b.status=1
                                                ");
                                                while($sql_result = mysqli_fetch_assoc($sql)){ 

                                                ?>
                                                    <tr>
                                                        <td><? echo $i; ?></td>
                                                        <td><?= $sql_result['lho']; ?></td>
                                                        <td><? echo $sql_result['contactPersonName']; ?></td>
                                                        <td><? echo $sql_result['contactPersonNo']; ?></td>
                                                        <td><? echo $sql_result['contactPersonEmail']; ?></td>
                                                        <td><? echo $sql_result['designation']; ?></td>
                                                        <td><? echo $sql_result['esc_matrix']; ?></td>
                                                        
                                                    </tr>    
                                                <? $i++; }?>
                                                
                                            </tbody>
                                    </div>
                                </div>
            </div>

          </div>
                                
                           
                                
                           
                                
                                
                     
                    
                    
    <? include('../footer.php'); ?>

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


