<? include('../header.php'); 

$pod = $_REQUEST['pod'];



?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">

    
<style>
    .swal2-popup {
        background: white !important;
    }
</style>

                    <div class="card">
                        <div class="card-block">
                            <h5 style="font-weight: 600;">Update Tracking Status: <span style="color:red; ">
                                    <? echo $pod; ?>
                                </span></h5>
                            <hr>
                            <form id="updateMaterialSentTracking" enctype="multipart/form-data">
                                <input type="hidden" name="pod" value="<? echo $pod; ?>" />
                                <input type="hidden" name="vendorid" value="<? echo $RailTailVendorID; ?>" />
                                
                                <div class="row">
                                    <div class="grid-margin col-sm-12 extra_highlight">
                                        <label>LR Copy</label><br />
                                        <input type="file" name="lrCopy" required />
                                    </div>

                                    <div class="grid-margin col-sm-12 extra_highlight">
                                        <label>Delivery Challan</label><br />
                                        <input type="file" name="deliveryChallan" required />
                                    </div>

                                    <div class="grid-margin col-sm-12">
                                        <label>Challan Number</label>
                                        <input type="text" name="challanNumber" class="form-control" required />
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="grid-margin col-sm-12">
                                        <label>Receivers Name</label>
                                        <input type="text" name="receiversName" value="<?php echo $_SESSION['ADVANTAGE_username']; ?>" class="form-control" required />
                                    </div>

                                    <div class="grid-margin col-sm-12">
                                        <label>Receivers Number</label>
                                        <input type="text" name="receiversNumber"  value="<?php echo $_SESSION['contact']; ?>" class="form-control" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Received Date</label>
                                        <input type="date" class="form-control esd-datetime-input"  name="receivedDate" \
                                         value="<?php echo date('Y-m-d'); ?>" required />

                                        <!-- <input type="date" name="receivedDate" class="form-control" required /> -->
                                    </div>

                                    <div class="col-sm-6">
                                        <label>Received Time</label>
                                        <input type="time" name="receivedTime" class="form-control" required />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="grid-margin col-sm-12">
                                        <br />
                                        <button id="submitFormButton" type="button"
                                            class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>
                
<script>
    function saveFormData() {
        // Get the form element
        var form = $('#updateMaterialSentTracking')[0];
        var formData = new FormData(form);

        // Perform an Ajax request to save the form data
        $.ajax({
            url: 'process_bulkupdateMaterialSentTracking.php', // Replace with your server-side script URL
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                var parsedResponse = JSON.parse(response);
                if (parsedResponse.status == 200) {
                    // Form data successfully saved
                    Swal.fire({
                        title: 'Success',
                        text: parsedResponse.response,
                        icon: 'success'
                    });
                    Swal.fire({
                        title: parsedResponse.response,
                        text: "Redirecting...",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500,
                        didClose: () => {
                            window.location.href = './materialRecived.php';
                        },
                    });
                } else {
                    // Error occurred while saving form data
                    Swal.fire('Error', parsedResponse.response, 'error');
                }
            },
            error: function () {
                // Error occurred in the Ajax request
                Swal.fire('Error', 'Error saving form data. Please try again.', 'error');
            }
        });
    }

    // Attach event listener to the submit button
    $('#submitFormButton').click(saveFormData);
</script>



    </div>
</div>


<? include('../footer.php'); ?>

