     <div class="row" style="padding-top:15px;padding-bottom:15px;">
                <div class="col-sm-12">
                    <div class="card replypart" style="display: none;">
                        <div class="card-block">





                            <?



                            $status_view = 0;
                            if ($mis_status == 'material_in_process') {
                                $status_view = 1;
                            }



                            ?>


                            <form class="replyform" method="POST" enctype="multipart/form-data">





                                <div id="statusChangePart"
                                    style="padding: 15px; background-color: coral;box-shadow: 10px 10px lightblue; margin: 50px auto 50px auto;">
                                    <h5>Change Status</h5>



                                    <div class="row">
                                        <div class="col-sm-12">

                                            <select class="form-control" name="status" id="status" required>

                                                <?php if ($mis_status == 'open' || $mis_status == 'Open') { ?>
                                                    <option value="">Select</option>
                                                    <option value="reassign"> Bank Dependency </option>
                                                    <option value="material_requirement">Material Requirement</option>
                                                    <option value="close">Close</option>
                                                <?php }

                                                if ($mis_status == 'material_requirement') { ?>
                                                    <option value="">Select</option>
                                                    <option value="schedule">Schedule</option>
                                                    <option value="material_dispatch">Material Dispatch</option>
                                                    <option value="material_in_process">Material in Process</option>
                                                    <option value="replace_with_available">Replace With Available Material
                                                    </option>
                                                    <option value="close">Close</option>
                                                <?php }

                                                if ($mis_status == 'replace_with_available') { ?>
                                                    <option value="">Select</option>
                                                    <option value="close">Close</option>
                                                <? }

                                                if ($mis_status == 'reassign') { ?>
                                                    <option value="">Select</option>
                                                    <option value="close">Close</option>
                                                <? }



                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row extra_highlight" id="status_col"></div>

                                </div>
                                <hr />
                                <input type="hidden" name="mis_id" value="<?php echo $mis_id; ?>">
                                <input type="hidden" name="atmid" value="<?php echo $atmid; ?>">
                                <input type="hidden" name="replyTo" value="noc@advantagesb.com">
                                <input type="hidden" name="message_id" value="<?php echo $message_id; ?>">
                                <input type="hidden" name="subject" value="<?php echo $subject; ?>">





                                <div class="row">
                                    <div class="tagsinput col-sm-12">

                                        <div id="toemailtoSend"></div>
                                        <input type="hidden" name="toemailtoSendVal" id="toemailtoSendVal">
                                        <input type="hidden" name="ccemailtoSendVal" id="ccemailtoSendVal">

                                    </div>

                                    <div class="col-sm-12">


                                        <label for="toEmail">To:</label>
                                        <input type="text" id="toEmail" data-role="tagsinput"
                                            placeholder="Enter email addresses..." oninput="searchEmails('to', this.value)"
                                            class="form-control">
                                        <div id="toSuggestions"></div>

                                        <div class="requester-and-cc-link">
                                            <span id="addContactLink" data-bs-toggle="modal" class="history-link"
                                                data-bs-target="#historyModal" class="requester-and-cc"
                                                style="cursor:pointer;">Add
                                                new contact</span>

                                        </div>


                                    </div>

                                </div>

                                <div class="row" id="ccfield">

                                    <div class="tagsinput col-sm-12">
                                        <div id="ccemailtoSend"></div>
                                    </div>



                                    <div class="col-sm-12 grid-margin">

                                        <label for="ccEmail">Cc:</label>
                                        <input type="text" id="ccEmail" data-role="tagsinput"
                                            placeholder="Enter email addresses..." oninput="searchEmails('cc', this.value)"
                                            class="form-control">
                                        <div id="ccSuggestions"></div>


                                    </div>
                                </div>


                                <textarea name="emailbody" id="editor1"></textarea>

                                <!-- <textarea class="emailbody" id="emailbody" name="emailbody" required></textarea> -->


                                <br />

                                <div class="actionsButtons">
                                    <button type="button" id="saveButton" class="btn btn-primary"> Send Reply </button>
                                </div>


                            </form>
                            
                        </div>


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
                            const selectedEmail = email.email;

                            if (field == 'to') {
                                const toemailtoSendVal = $("#toemailtoSendVal");
                                const currentValue = toemailtoSendVal.val();
                                if (currentValue.trim() !== "") {
                                    toemailtoSendVal.val(currentValue + ',' + selectedEmail);
                                } else {
                                    toemailtoSendVal.val(selectedEmail);
                                }
                                $("#toemailtoSend").append('<span class="tag"><span>' + emailText + '&nbsp;&nbsp;</span><a href="#" title="Removing tag">x</a></span>');

                            } else if (field == 'cc') {
                                const ccemailtoSendVal = $("#ccemailtoSendVal");

                                // Get the current value of ccemailtoSendVal
                                const currentValue = ccemailtoSendVal.val();

                                // Check if the input is not empty
                                if (currentValue.trim() !== "") {
                                    // Add a comma before appending the new email
                                    ccemailtoSendVal.val(currentValue + ',' + selectedEmail);
                                } else {
                                    ccemailtoSendVal.val(selectedEmail);
                                }

                                // Add the selected email to the ccemailtoSend container
                                $("#ccemailtoSend").append('<span class="tag"><span>' + emailText + '&nbsp;&nbsp;</span><a href="#" title="Removing tag">x</a></span>');

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
                // console.error("Error:", error);
                // Display error message to the user
            });
    }
</script>