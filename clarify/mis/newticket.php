<?php include('../header.php'); ?>

<!-- jQuery -->

<!-- jQuery UI -->

<!-- jQuery UI CSS (you may need to adjust the path) -->
<script src="<? $_SERVER["DOCUMENT_ROOT"]; ?>/clarify/assets/js/jquery-3.7.min.js"></script>


<style>
    #emailToList {
        width: 100%;
        /* Set the width to 100% */
    }
    input,
    select {
        background: #fff;
        border: none !important;
        border: 1px solid #cfd7df !important;
        border-radius: 4px !important;
        font-size: 14px !important;
        font-weight: 400 !important;
    }

    .requester-and-cc-link {
        padding-top: 0;
        color: #2c5cc5;
    }

    .requester-and-cc-link {
        color: #2c5cc5;
        margin-top: 7px;
        text-align: right;
        width: 100%;
    }

    .tox-statusbar__branding {
        display: none;
    }

    div.tagsinput span.tag {
        background: #0090e7;
        border: 0;
        color: #ffffff;
        padding: 6px 14px;
        font-size: 0.8125rem;
        font-family: inherit;
        line-height: 1;
    }

    div.tagsinput span.tag {
        border: 1px solid #a5d24a;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        display: block;
        float: left;
        padding: 5px;
        text-decoration: none;
        background: #0090e7;
        color: white;
        margin-right: 5px;
        margin-bottom: 5px;
        font-family: helvetica;
        font-size: 16px;
    }
</style>

<form id="form" method="POST">
    <div class="row">
        <div class="col-sm-9 grid-margin">

            <input type="hidden" name="call_type" id="call_type" class="form-control" value="Service">

            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-12 grid-margin">
                            <label for="ATMID">ATMID</label>
                            <input type="text" id="atmid" class="form-control" list="atmidOptions" name="atmid">
                            <datalist id="atmidOptions"></datalist>

                        </div>
                        <div class="col-sm-6">
                            <label>Issue</label>
                            <select name="comp" id="comp" class="form-control" required>
                                <option value="">Select</option>
                                <option>Offline</option>
                                <option>Non-Offline</option>
                                <option>VPN-down</option>

                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label>Reason</label>
                            <select name="subcomp" id="subcomp" class="form-control" required>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="tagsinput col-sm-12">

                            <div id="toemailtoSend"></div>
                        </div>

                        <div class="col-sm-12">


                            <label for="toEmail">To:</label>
                            <input type="text" id="toEmail" data-role="tagsinput" placeholder="Enter email addresses..."
                                oninput="searchEmails('to', this.value)" class="form-control">
                            <div id="toSuggestions"></div>

                            <div class="requester-and-cc-link">
                                <span id="addContactLink" data-bs-toggle="modal" class="history-link"
                                    data-bs-target="#historyModal" class="requester-and-cc" style="cursor:pointer;">Add
                                    new contact</span>
                                <span class="separator-line pl-9 pr-9 " data-identifyelement="174">|</span>
                                <span data-test-id="showCcLink" class="requester-and-cc " data-identifyelement="175">
                                    Add Cc
                                </span>
                            </div>


                        </div>

                    </div>

                    <div class="row" id="ccfield">

                        <div class="tagsinput col-sm-12">

                            <div id="ccemailtoSend"></div>
                        </div>



                        <div class="col-sm-12 grid-margin">

                            <label for="ccEmail">Cc:</label>
                            <input type="text" id="ccEmail" data-role="tagsinput" placeholder="Enter email addresses..."
                                oninput="searchEmails('cc', this.value)" class="form-control">
                            <div id="ccSuggestions"></div>


                        </div>
                    </div>

                    <div class="row">



                        <div class="col-sm-12 grid-margin">
                            <label for="Subject">Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>



                        <div class="col-sm-12">
                            <label for="engineer">Engineer</label>
                            <select class="form-control" name="engineer" id="engineer" required>
                                <option>-- Select --</option>
                                <?
                                $eng_sql = mysqli_query($con, "select * from user where level=3 and user_status=1 order by name asc");
                                while ($eng_sql_result = mysqli_fetch_assoc($eng_sql)) {
                                    $engid = $eng_sql_result['userid'];
                                    $engname = $eng_sql_result['name'];
                                    ?>
                                    <option value="<? echo $engid; ?>">
                                        <? echo ucwords(strtolower($engname)); ?>
                                    </option>
                                <? }
                                ?>
                            </select>
                        </div>

                        <div class="col-sm-12 grid-margin">
                            <br>
                            <label for="emailBody">Email Body</label>
                            <script
                                src="https://cdn.tiny.cloud/1/rj1woehi6an37571ch4s8q5r54baswga07r6fc0kxa7w7wzb/tinymce/6/tinymce.min.js"
                                referrerpolicy="origin"></script>
                            <script>
                                tinymce.init({
                                    selector: 'textarea.emailbody',
                                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
                                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                                    tinycomments_mode: 'embedded',
                                    tinycomments_author: 'Author name',
                                    mergetags_list: [
                                        { value: 'First.Name', title: 'First Name' },
                                        { value: 'Email', title: 'Email' },
                                    ],
                                    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
                                });
                            </script>
                            <textarea class="emailbody" id="emailbody" name="emailbody" required>

                            </textarea>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="col-sm-3 grid-margin">
            <div class="card" id="atminfo" style="display:none;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Call Receive From</label>
                            <select class="form-control" name="call_receive" id="call_receive" reuqired>
                                <option value="">Select</option>
                                <option value="Customer / Bank">Customer / Bank</option>
                                <option value="Internal">Internal</option>
                            </select>
                        </div>

                        <div class="col-sm-12">
                            <label class="label_label">Bank</label>
                            <input type="text" name="bank" id="bank" class="form-control">
                        </div>

                        <div class="col-sm-12">
                            <label>Customer</label>
                            <select class="form-control" id="customer" name="customer" required>
                                <option value="">Select Customer</option>
                                <? $con_sql = mysqli_query($con, "select distinct(customer) as customer from sites where status=1");
                                while ($con_sql_result = mysqli_fetch_assoc($con_sql)) { ?>
                                    <option value="<? echo strtoupper($con_sql_result['customer']); ?>">
                                        <? echo strtoupper($con_sql_result['customer']); ?>
                                    </option>
                                <? } ?>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label>LHO</label>
                            <input type="text" name="lho" id="lho" class="form-control" value="" required>
                        </div>
                        <div class="col-sm-12">
                            <label class="label_label">Region</label>
                            <input class="form-control" type="text" name="region" id="region"
                                value="<? echo $region; ?>">
                        </div>

                        <div class="col-sm-12">
                            <label class="label_label">City</label>
                            <input class="form-control" type="text" name="city" id="city" value="<? echo $city; ?>"
                                required>
                        </div>

                        <div class="col-sm-12">
                            <label class="label_label">State</label>
                            <select name="state" id="state" class="form-control" required>
                                <option value="">Select State</option>
                                <?php
                                $state_sql = mysqli_query($con, "select distinct(state) as state from sites where status=1");
                                while ($state_sql_result = mysqli_fetch_assoc($state_sql)) { ?>
                                    <option value="<?php echo $state_sql_result['state']; ?>" <?php if ($state == $state_sql_result['state']) {
                                           echo 'selected';
                                       } ?>>
                                        <?php echo $state_sql_result['state']; ?>
                                    </option>
                                <?php } ?>
                            </select>

                        </div>
                        <div class="col-sm-12">
                            <label class="label_label">Locations</label>
                            <input class="form-control" type="text" name="location" id="location"
                                value="<? echo $location; ?>">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal__footer form-submission create-ticket-footer" data-identifyelement="422">

        <div class="form-submission__actions" data-identifyelement="431">
            <button name="button" class="modal__footer__btn btn btn--secondary" type="button">
                Cancel
            </button>
            <button id="send-and-set" class="btn btn--primary" type="button">
                Create
            </button>
            <button type="button" id="saveButton" class="btn btn-primary"> Create Call
            </button>
        </div>
    </div>

</form>


<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="ModalLabel" style="display: none;"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form action="" id="emailContactForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add new contact</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <div class="col-sm-12">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-sm-12">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-sm-12">
                            <label for="">Company</label>
                            <input type="text" name="company" class="form-control" required>
                        </div>
                        <div class="col-sm-12">
                            <label for="">Contact</label>
                            <input type="number" name="contact" class="form-control" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary">
                </div>

            </form>

        </div>
    </div>
</div>


<script>

    function searchEmails(field, email) {
        fetch("get_emailTosuggestions.php?field=" + field + "&email=" + email)
            .then(response => response.json())
            .then(data => {
                const suggestionsContainer = document.getElementById(field + 'Suggestions');
                suggestionsContainer.innerHTML = ""; // Clear previous suggestions

                if (data.length > 0) {
                    for (const email of data) {
                        const suggestionItem = document.createElement("li");
                        const emailText = email.name ? `${email.name} ${email.email}` : email.email;
                        suggestionItem.textContent = emailText;
                        suggestionItem.addEventListener("click", () => {

                            if (field == 'to') {
                                $("#toemailtoSend").append('<span class="tag"><span>' + emailText + '&nbsp;&nbsp;</span><a href="#"title="Removing tag">x</a></span>')
                            } else if (field == 'cc') {
                                $("#ccemailtoSend").append(`<span class="tag"><span>${emailText}&nbsp;&nbsp;</span><a href="#"
                                    title="Removing tag">x</a></span>`)
                            }
                            const inputField = document.getElementById(field + 'Email');
                            $("#toSuggestions").html('');
                            $("#ccSuggestions").html('');

                            // Use the tag input library's addTag API to add the email as a tag
                            inputField.value = ''; // Clear input field for next selection
                        });
                        suggestionsContainer.appendChild(suggestionItem);
                    }
                } else {
                    suggestionsContainer.textContent = "No email suggestions found.";
                }
            })
            .catch(error => {
                console.error("Error:", error);
                // Display error message to the user
            });
    }


    $(document).on('change', '#comp', function () {
        var comp = $(this).val();
        if (comp == 'Offline') {
            option = '<option>Router Offline</option>';
        } else if (comp == 'Non-Offline') {
            option = `<option>Weak Signal</option>
                    <option>Fluctuation</option>
                    <option>SIM Slot Not Working</option>

        `;
        } else if (comp == 'VPN-down') {
            option = `<option>VPN-down</option>`;

        }
        else {
            option = `<option></option>`;
        }
        $("#subcomp").html(option);
    })

    $(document).ready(function () {
        $("#atmid").focus();


        function checkOpenCall(atmid) {
            $.ajax({
                type: "POST",
                url: 'check_open_call.php', // Replace with the PHP script to check open calls
                data: { atmid: atmid },
                success: function (response) {
                    if (response.openCall) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Open Call Exists',
                            text: 'An open call already exists for this ATM ID.',
                            confirmButtonText: 'OK'
                        }).then(function () {
                            // Reset the form
                            // $("#form")[0].reset();
                        });
                    }
                }
            });
        }

        $("#atmid").on('blur', function () {
            var atmid = $(this).val();
            if (atmid !== "") {
                checkOpenCall(atmid);
            }
        });


    });


    $(document).ready(function () {
        function populateATMData(atmid) {
            $.ajax({
                type: "POST",
                url: 'get_atm_data.php',
                data: 'atmid=' + atmid,
                success: function (msg) {
                    console.log(msg);

                    if (msg != 0) {
                        var obj = JSON.parse(msg);
                        var fields = ['customer', 'bank', 'engineer', 'location', 'region', 'state', 'city', 'branch', 'bm', 'lho'];

                        fields.forEach(function (field) {
                            if (!obj[field]) {
                                $("#" + field).focus();
                            } else {
                                $("#" + field).val(obj[field]);
                                $('#' + field).attr('readonly', true);
                            }
                        });

                        if (obj.customer && obj.bank && obj.location && obj.region && obj.state && obj.city && obj.branch && obj.bm && obj.lho) {
                            $("#call_receive").focus();
                        }

                        $("#call_type").focus();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'No Info With This ATM',
                        }).then(function () {
                            // Reset the form
                            // $("#form")[0].reset();
                        });

                    }
                }
            });

            $.ajax({
                type: "POST",
                url: 'show_history.php',
                data: 'atmid=' + atmid,
                success: function (msg) {
                    $("#show_history").html(msg);
                }
            });
        }

        $("#atmid").on('input', function () {
            var input = $(this).val();

            $.ajax({
                type: "POST",
                url: 'get_suggestions.php',
                data: { input: input },
                success: function (response) {
                    console.log(response)
                    var datalist = $("#atmidOptions");
                    datalist.empty();

                    var suggestions = JSON.parse(response);

                    suggestions.forEach(function (suggestion) {
                        datalist.append($("<option>").attr('value', suggestion).text(suggestion));
                    });
                }
            });
        });

        $("#atmid").on('change', function () {
            var atmid = $(this).val();
            populateATMData(atmid);
            $("#atminfo").css('display', 'block')
        });
    });

    $("#saveButton").on('click', function () {
        var atmid = $("#atmid").val();
        var emailbody = $("#emailbody").val();
        var engineer = $("#engineer").val();

        var fieldsToHighlight = [];

        if (atmid == "") {
            fieldsToHighlight.push("#atmid");
        } else {
            // Remove the element from the array if it was previously added
            var index = fieldsToHighlight.indexOf("#atmid");
            if (index !== -1) {
                fieldsToHighlight.splice(index, 1);
            }
        }

        if (emailbody == "") {
            fieldsToHighlight.push("#emailbody");
        } else {
            // Remove the element from the array if it was previously added
            var index = fieldsToHighlight.indexOf("#emailbody");
            if (index !== -1) {
                fieldsToHighlight.splice(index, 1);
            }
        }

        
        if (engineer == "") {
            fieldsToHighlight.push("#engineer");
        } else {
            // Remove the element from the array if it was previously added
            var index = fieldsToHighlight.indexOf("#engineer");
            if (index !== -1) {
                fieldsToHighlight.splice(index, 1);
            }
        }

        

        if (fieldsToHighlight.length > 0) {
            fieldsToHighlight.forEach(function (fieldId) {
                console.log(fieldId);
                $(fieldId).addClass("chighlight");
            });

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fill in all required fields.'
            });

            return false; // Prevent form submission
        }

        // Remove highlighting from fields
        $(".chighlight").removeClass("chighlight");

        var formData = $("#form").serialize();
        $.ajax({
            type: "POST",
            url: 'process_addMis.php', // Replace with your PHP script to save form data
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then(function () {
                        // Reset the form
                        // $("#form")[0].reset();
                    });
                } else {
                    // Show error message using SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function (xhr, status, error) {
                // Show error message using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while saving the form data.'
                });
            }
        });
    });










    $(document).ready(function () {
        // Triggered when the form is submitted
        $('#emailContactForm').submit(function (e) {
            e.preventDefault(); // Prevent the form from submitting in the traditional way

            // Get form data
            var formData = {
                name: $('input[name="name"]').val(),
                email: $('input[name="email"]').val(),
                company: $('input[name="company"]').val(),
                contact: $('input[name="contact"]').val()
            };

            // Make AJAX request
            $.ajax({
                type: 'POST',
                url: './saveEmailContact.php',
                data: formData,
                success: function (response) {
                    console.log(response)
                    if (response == 1) {
                        $('#historyModal').modal('hide');

                        // Success alert
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Contact added successfully!',
                            onClose: function () {
                                // Close the modal
                                $('#historyModal').modal('hide');
                            }
                        });
                    } else {
                        // Error alert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to add contact. Please try again.'
                        });
                    }
                },
                error: function () {
                    // Error alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to communicate with the server. Please try again later.'
                    });
                }
            });
        });
    });



</script>

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



<? include('../footer.php'); ?>