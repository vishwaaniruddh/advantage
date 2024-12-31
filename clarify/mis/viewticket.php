<? include ('../header.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

?>
<div class="misloader"></div>

<link type="text/css" href="../js/sample/css/sample.css" rel="stylesheet" media="screen" />

<script src="../js/ckeditor.js"></script>



<style>
    .ck-rounded-corners .ck.ck-editor__main>.ck-editor__editable,
    .ck.ck-editor__main>.ck-editor__editable.ck-rounded-corners {
        height: 300px;
    }

    .table tbody tr th {
        white-space: nowrap;
    }

    .table tbody tr td {
        white-space: normal;
    }

    .tag {
        display: inline-block;
    }

    .tag:empty {
        display: none;
    }

    .removeTag {
    margin: auto 5px;
    display: inline-block;
    width: 16px;
    height: 16px;
    line-height: 16px;
    text-align: center;
    color: #666;
    background: #e0e0e0;
    font-size: 10px;
    border-radius: 50%;
    border: 1px solid #ccc;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.3s, color 0.3s;
}

.removeTag:hover {
    background: #ccc;
    color: #333;
}

div.tagsinput span.tag {
    background: #e8f0fe;
    border: 1px solid #c3dafb;
    color: #3c4043;
    padding: 3px 8px;
    font-size: 12px;
    font-family: Arial, sans-serif;
    line-height: 1.5;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    margin: 2px;
}

div.tagsinput span.tag a {
    margin-left: 8px;
    text-decoration: none;
    color: #666;
}

div.tagsinput span.tag a:hover {
    color: #333;
}
    .removeTag:hover {
        background: red;
        color: white;
        border-color: red;
    }

    .previous_replies .card:hover {
        background: #b0f7b6;
        box-shadow: 3px 3px 15px;
    }

    .ck-editor__editable[role="textbox"] {
        min-height: 200px;
        max-height: 400px;
        overflow: scroll;
    }

    .ck-content .image {
        max-width: 80%;
        margin: 20px auto;
    }
</style>

<?php

$referece_code = $_REQUEST['ticket'];
$sql = mysqli_query($con, "select * from mis where reference_code = '" . $referece_code . "'");
if ($sql_result = mysqli_fetch_assoc($sql)) {
    $mis_id = $id = $sql_result['id'];
    $atmid = $sql_result['atmid'];
    $remarks = $sql_result['remarks'];
    $lho = $sql_result['lho'];
    $location = $sql_result['location'];
    // $remarks_with_line_breaks = preg_replace("/(\r?\n){2}/", "\n", $remarks);
    $remarks_with_line_breaks = preg_replace("/(\r?\n){2,}/", "\n", $remarks);

    // $remarks_with_line_breaks = nl2br($remarks);

    $created_by = $sql_result['created_by'];
    $serviceExecutive = $sql_result['serviceExecutive'];

    if (!$serviceExecutive) {
        $serviceExecutive = getUsername($created_by, $vendor = FALSE);
    }


    $message_id = $sql_result['message_id'];
    $encoded_message_id = htmlspecialchars($sql_result['message_id'], ENT_QUOTES, 'UTF-8');
    $subject = $sql_result['subject'];


    $fromEmail = ($sql_result['fromEmail'] ? $sql_result['fromEmail'] : 'noc@advantagesb.com');
    $toEmail = ($sql_result['toEmail'] ? $sql_result['toEmail'] : 'noc@advantagesb.com');
    $ccEmail = ($sql_result['ccEmail'] ? $sql_result['ccEmail'] : '');







    ?>




    <div class="card">
        <div class="card-body">

            <div class="flex" style="display: flex;justify-content: space-between;">
                <h5>CALL INFORMATION</h5>
                <small>
                    <a href="../../mis_details.php?id=<?= $id; ?>">View History(traditional View )</a>

                </small>

            </div>
            <hr>
            <?php

            // echo "select * from mis_details  where mis_id= '" . $id . "'" ; 
            $sql = mysqli_query($con, "select * from mis_details  where mis_id= '" . $id . "'");
            $detailresult = mysqli_fetch_assoc($sql);
            $detailId = $detailresult['mis_id'];
            $ticketid = $detailresult['ticket_id'];

            $mis_status = $detailresult['status'];
            $status_view = 0;
            if ($mis_status == 'material_in_process') {
                $status_view = 1;
            }



            // echo "select * from mis_history where mis_id='".$detailId."' order by id desc" ; 
            $historysql = mysqli_query($con, "select * from mis_history where mis_id='" . $detailId . "' and type='close' order by id desc");
            if ($history_sql_result = mysqli_fetch_assoc($historysql)) {

                $date1_real = $history_sql_result['created_at'];
                $date1 = date_create($date1_real);

            } else {
                $date1_real = date('Y-m-d H:i:s');
                $date1 = date_create($date1_real);
            }


            $date = date('Y-m-d H:i:s');

            $date2 = date_create($sql_result['created_at']);

            $diff = date_diff($date1, $date2);



            ?>
            <div class="view-info">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="general-info">
                            <div class="row">
                                <div class="col-lg-12 col-xl-6">
                                    <div class="table-responsive">
                                        <table class="table m-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Ticket ID </th>
                                                    <td>
                                                        <?php echo $ticketid; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">ATMID</th>
                                                    <td>
                                                        <?php echo $atmid; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Component</th>
                                                    <td>
                                                        <?php echo $detailresult['component']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Sub Component</th>
                                                    <td>
                                                        <?php echo $detailresult['subcomponent']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>LHO</th>
                                                    <td><?php echo $lho; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xl-6">
                                    <div class="table-responsive">
                                        <table class="table m-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Created On</th>
                                                    <td>
                                                        <span>
                                                            
                                                            <?php 
                                                            $recordCreatedAT = $sql_result['created_at'] ;
                                                            echo $recordCreatedAT ; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <th scope="row">Created By</th>
                                                    <td>
                                                        <?php echo $serviceExecutive; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th scope="row">Down Time </th>
                                                    <td>
                                                        <?php


                                                        $formattedDiff = $diff->format("%a days, %h hours, %i minutes, %s seconds");

                                                        echo $formattedDiff;
                                                        //echo $diff->format("%a days"); 
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Current Status</th>
                                                    <td>
                                                        <?php echo ucwords($sql_result['status']); ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>Location</th>
                                                    <td>
                                                        <?php 
                                                            $location = breakStringIntoLines($location);
                                                        echo $location; ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <br />















    <div class="row">
        <div class="col-sm-12 grid-margin">



            <div class="card email-card" style="font-size:12px;">


                <?

                if (isset($subject) && $subject != '') {

                } else {
                    $subject = 'Docket Number ' . $ticketid . ' ATM ID : ' . $atmid;

                }

                ?>



                <div style="padding-top: 15px;padding-left: 15px;">

                    <span style="color:black; width:60px;">Subject : </span>
                    <span style="color:red;"> <?php echo $subject; ?></span>

                    <br>

                    <span>From : <a href="#">
                            <?php echo $fromEmail; ?>
                        </a> </span>
                    <br>
                    <b>To :</b>

                    <span style="font-weight: 400;">
                        <?php echo $toEmail; ?>
                    </span>
                </div>
                <hr>
                <div style="padding-left: 15px;">
                    <b>Cc : </b>
                    <span style="font-weight: 400;">
                        <?php echo $ccEmail; ?>
                    </span>
                </div>
                <hr>
                <p style="font-weight: 400;">

                    <?php
                    echo '<pre>';
                    echo $remarks_with_line_breaks;
                    echo '</pre>';

                    ?>
                </p>
            </div>



            <div class="row previous_replies" style="padding: 15px;">
                <?php


                // echo "Select * from emails where mis_id='" . $mis_id . "' order by email_id asc" ;
                $history_sql = mysqli_query($con, "Select * from emails where mis_id='" . $mis_id . "' order by email_id asc");
                // if ($history_sql && mysqli_num_rows($history_sql) > 0) {
            
                while ($history_sql_result = mysqli_fetch_assoc($history_sql)) {
                    // $historyRemarks_with_line_breaks = nl2br($historyRemarks);
            

                    $emailId = $history_sql_result['email_id'];

                    $historyRemarks = $history_sql_result['content_body'];
                    $originalString = $historyRemarks_with_line_breaks = preg_replace("/(\r?\n){2,}/", "\r", $historyRemarks);

                    // $historyRemarks_with_line_breaks = preg_replace("/(\r?\n){2,}/", "\r", $historyRemarks_with_line_breaks);
            
                    $from_email = $history_sql_result['from_email'];
                    if ($from_email != 'noc@advantagesb.com') {
                        $nameemail = htmlspecialchars($from_email, ENT_QUOTES, 'UTF-8');
                        $email_parts = explode('<', $from_email);
                        $email_address = trim($email_parts[1], '>');
                    } else {
                        $nameemail = 'noc@advantagesb.com';
                        $email_address = $from_email;
                    }




                    $recipients_sql = mysqli_query($con, "SELECT * FROM recipients where email_id='" . $emailId . "' and recipient_email <>'noc@advantagesb.com'");
                    if ($recipients_sql && mysqli_num_rows($recipients_sql) > 0) {

                        while ($recipients_sql_result = mysqli_fetch_assoc($recipients_sql)) {

                            preg_match('/[\w\.-]+@[\w\.-]+/', $recipients_sql_result['recipient_email'], $matches);

                            $email = $matches[0];


                            $recipients[] = $email;

                        }

                        $unique_recipients = array_unique($recipients);

                        $recipients_string = implode(', ', $unique_recipients);


                    }




                    ?>
                    <div class="card col-sm-12" style="margin: 15px auto;padding: 15px;    font-weight: 400;font-size:11px;">
                        <p>
                            <b>
                                <a href="#">From :
                                    <?php echo $nameemail; ?>
                                </a>
                            </b>
                        </p>
                        <p>
                            <b>
                                <a href="#">
                                    To :
                                    <?= $recipients_string; ?>
                                </a>
                            </b>
                        </p>

                        <?php



                        // Replace encoded characters with proper characters
                        $originalString = str_replace("=E2=80=AF", "", $originalString);
                        $originalString = str_replace("=\n", "", $originalString);

                        // Split the string into lines
                        $lines = explode("\n", $originalString);

                        // Start building the formatted string
                        $formattedString = '';
                        foreach ($lines as $line) {
                            // Add line break before "On" if it's not the first line
                            if (strpos($line, "On") === 0 && $formattedString !== '') {
                                $formattedString .= "<br>";
                            }
                            $formattedString .= $line . "<br>";
                        }
                        // $formattedString = preg_replace('/\s+(?=[A-Z])/', '', $originalString);
                


                        ?>
                        <div class="content">
                            <?php
                            $contentWithLineBreaks = nl2br($historyRemarks);


                            echo $contentWithLineBreaks;
                            ?>
                        </div>
                        <style>
                            .content div {
                                margin: 0;
                                padding: 5px 0;
                                /* Adjust padding as needed */
                            }
                        </style>
                        <?
                        // echo filterLines(($historyRemarks_with_line_breaks));
                        // echo ($historyRemarks_with_line_breaks);
                
                        // echo "select * from attachments where email_id='" . $emailId . "'" ; 
                        $attachment_sql = mysqli_query($con, "select * from attachments where email_id='" . $emailId . "'");
                        if (mysqli_num_rows($attachment_sql) > 0) {

                            ?>
                            <hr>
                            <h3>Attachments</h3>
                            <?


                            while ($attachment_sql_result = mysqli_fetch_assoc($attachment_sql)) {

                                $file_name = $attachment_sql_result['file_name'];
                                $file_path = $attachment_sql_result['file_path'];

                                ?>
                                <a href="./<?php echo $file_path; ?>">
                                    <?php echo $file_name; ?>
                                </a>
                            <?
                            }
                        } else {

                        }
                        ?>
                    </div>
                    <?
                    $attachment_sql = '';
                }

                // }else {
                $nameemail = 'noc@advantagesb.com';
                $email_address = $from_email;

                // $recipients_string = '';
                // }
            




                ?>


            </div>



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
                                                    <option value="reassign"> Bank Dependency </option>
                                                    <option value="material_requirement">Material Requirement</option>
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
                                                style="cursor:pointer;">Add new contact</span>

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
                                    <div class="col-sm-12 grid-margin">
                                        <label for="emailBody">Email Body:</label>
                                        <textarea name="emailbody" id="editor1"></textarea>
                                    </div>
                                </div>




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
                                            $("#toemailtoSend").append('<span class="tag"><span>' + emailText + '&nbsp;&nbsp;</span><a href="#" class="removeTag" title="Removing tag" onclick="removeTag(this)">x</a></span>');

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
                                            $("#ccemailtoSend").append('<span class="tag"><span>' + emailText + '&nbsp;&nbsp;</span><a href="#" title="Removing tag" class="removeTag" onclick="removeTagCc(this)">x</a></span>');

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


            <? if ($mis_status != 'close') { ?>
                <a href="#" id="cancel" class="btn btn-primary"> <svg xmlns="http://www.w3.org/2000/svg" width="1em"
                        height="1em" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M12 2c5.5 0 10 4.5 10 10s-4.5 10-10 10S2 17.5 2 12S6.5 2 12 2m0 2c-1.9 0-3.6.6-4.9 1.7l11.2 11.2c1-1.4 1.7-3.1 1.7-4.9c0-4.4-3.6-8-8-8m4.9 14.3L5.7 7.1C4.6 8.4 4 10.1 4 12c0 4.4 3.6 8 8 8c1.9 0 3.6-.6 4.9-1.7" />
                    </svg> Cancel</a>
                <a href="#" id="reply" class="btn btn-primary"> <i class="mdi mdi-reply"></i> Reply</a>
            <? } ?>


        </div>


    </div>

    <script>
        $(document).on('click', '#cancel', function (event) {
            event.preventDefault();

            $(".replyform")[0].reset();
            $("#toemailtoSend").html('');
            $("#ccemailtoSend").html('');
            $('.replypart').hide();

        });

        $(document).on('click', '#reply', function (event) {

            event.preventDefault();
            $('.replypart').show();
            $("#toEmail").focus();
            let lho = '<?= $lho; ?>';

            populateToemails(lho);
            populateCcemails(lho);

            // Initialize TinyMCE
            // tinymce.init({
            //     selector: 'textarea.emailbody',
            //     plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
            //     toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            //     tinycomments_mode: 'embedded',
            //     tinycomments_author: 'Author name',
            //     mergetags_list: [
            //         { value: 'First.Name', title: 'First Name' },
            //         { value: 'Email', title: 'Email' },
            //     ],
            //     ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
            // });

        });

                                                                                            // Function to validate form before submission

    </script>
    <?php
}
?>





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


<?


$recordCreatedAtDateTime = new DateTime($recordCreatedAt);
$datetimeDateTime = new DateTime($datetime);


?>

<script>





    $(document).on('change', '#status', function () {
        var status = $(this).val();
        $("#status_col").html('');
        if (status == 'close') {
            var html = `<input type="hidden" name="status" value="close">
                    <div class="col-sm-12"><label>Snap</label><br /><input type="file" name="image" ></div>
                    <div class="col-sm-12"><br><label>Resolution</label>
                        <select name="closeRemark" class="form-control" required>
                        <?

                        $bankD = mysqli_query($con, "select * from dependency_details where  master_dependency_id = 2  and status='Active' order by dependency_value asc");
                        while ($bankDResult = mysqli_fetch_assoc($bankD)) {
                            $bankDId = $bankDResult['id'];
                            $dependency_value = $bankDResult['dependency_value'];
                            ?>
                                <option value="<?php echo $dependency_value; ?>"><?php echo $dependency_value; ?></option>
                            <?
                        }
                       
                        ?>



        </select>
        </div>


    <div class="col-sm-12" >
    <br/>
        <label>Choose Datetime</label>
        <input
    type="date"
    name="actionDate"
    value="<?= $datetimeDateTime->format('Y-m-d'); ?>"
    min="<?= $recordCreatedAtDateTime->format('Y-m-d'); ?>"
    max="<?= $datetimeDateTime->format('Y-m-d'); ?>" required />


    <input
    type="time"
    name="actionTime"
    value="<?= date('H:i:s', strtotime($datetime)); ?>"
    required
/>


    </div>

`;
        } else if (status == 'material_requirement') {
            var html = `
        <input type="hidden" name="status" value="material_requirement">
        <div class="col-sm-12">
            <label>Please Select Material </label>
            <br />
            <div class="border-checkbox-section" style="margin: auto 40px;">
            <?
            $matLoopCount = 1;
            $mat_sql = mysqli_query($con, "select * from boq where status=1");
            while ($mat_sqlResult = mysqli_fetch_assoc($mat_sql)) {
                $value = $mat_sqlResult['value']; ?>
                                                                                        <div class="border-checkbox-group border-checkbox-group-primary">
                                                                                            <input class="material_checkbox" name="requiredMaterial[]" type="checkbox" id="checkbox<?php echo $matLoopCount; ?>" value="<?php echo trim($value); ?>">

                                                                                            <label class="material_name" for="checkbox<?php echo $matLoopCount; ?>"><?php echo trim($value); ?></label>

                                                                                            <input class="material_qty" id="input_qty_<?php echo $matLoopCount; ?>" type="text" name="material_quantity[]" placeholder="QTY" />
                                                                                            <select class="material_reason" id="select_<?php echo $matLoopCount; ?>" name="material_condition[]">
                                                                                                <option value="">Select</option>
                                                                                                <option value="Missing">Missing</option>
                                                                                                <option value="Faulty">Faulty</option>
                                                                                                <option value="Not Installed">Not Installed</option>
                                                                                                <option value="Power Fluctuation">Power Fluctuation</option>
                                                                                            </select>

                                                                                            <input class="material_image" id="input_<?php echo $matLoopCount; ?>" type="file" name="material_requirement_images[]" />
                                                                                        </div>
                
                                                                                <? $matLoopCount++;
            } ?>
            </div>

            
        </div>
    `;
        } else if (status == 'reassign') {
            var html = `<input type="hidden" name="status" value="reassign">

            <div class="border-checkbox-section highlight" style="width:75%">
                    <?

                    $bankD = mysqli_query($con, "select * from dependency_details where  master_dependency_id = 1  and status='Active' order by dependency_value asc");
                    $counter = 1;
                    while ($bankDResult = mysqli_fetch_assoc($bankD)) {
                        $bankDId = $bankDResult['id'];
                        $dependency_value = $bankDResult['dependency_value'];

                        ?>

                        <div class="border-checkbox-group border-checkbox-group-primary">
                            <input class="border-checkbox" name="noProblemOccurs[]" type="checkbox"
                                id="checkbox<?= $counter; ?>" value="<?php echo $dependency_value; ?>">
                            <label class="border-checkbox-label" for="checkbox<?= $counter; ?>"><?php echo $dependency_value; ?></label>
                        </div>
                        <?
                        $counter++;
                    }
                    ?>
                    </div>

    <div class="col-sm-12">
    <hr>    
    <label>Snap</label><br />
        <input type="file" name="image" >
    </div>
    
    <div class="col-sm-12">
    <br />    
    <label>Choose Time</label>
        <input
    type="date"
    name="actionDate"
    value="<?= $datetimeDateTime->format('Y-m-d'); ?>"
    min="<?= $recordCreatedAtDateTime->format('Y-m-d'); ?>"
    max="<?= $datetimeDateTime->format('Y-m-d'); ?>" required />


    <input
    type="time"
    name="actionTime"
    value="<?= date('H:i:s', strtotime($datetime)); ?>"
    required
/>
    </div>


            
    `;
        } else if (status == 'schedule') {
            var html = `<input type="hidden" name="status" value="schedule">
    <div class="col-sm-4">
    <label>Engineer</label>
    <select name="engineer" class="form-control">
    <option value="">Select</option>
    <? $eng_sql = mysqli_query($con, "select * from vendorusers where level=3 order by name asc");
    while ($eng_sql_result = mysqli_fetch_assoc($eng_sql)) { ?> 
                                                                                                                                                                        <option value="<?php echo $eng_sql_result['id']; ?>">
                                                                                                                                                                        <?php echo ucwords(strtolower($eng_sql_result['name'])); ?>
                                                                                                                                                                        </option> <? } ?>
    
    </select>
    </div>
    <div class="col-sm-4"><label>Schedule Date</label><input type="date" name="schedule_date" class="form-control"></div>
`;
        } else if (status == 'material_dispatch') {
            var html = `
    <input type="hidden" name="status" value="material_dispatch">
    <div class="col-sm-3">
    <label>Courier Agency</label>
    <input type="text" name="courier" class="form-control">
    </div>
    <div class="col-sm-3">
    <label>POD</label>
    <input type="text" name="pod" class="form-control">
    </div>
    <div class="col-sm-3">
    <label>Dispatch Date</label>
    <input type="date" name="dispatch_date" class="form-control">
    </div>
    
`;
        } else if (status == 'replace_with_available') {
            var html = `
    <input type="hidden" name="status" value="replace_with_available">
    <div class="col-sm-6">
    <label> <h5>Material</h5> </label>
    </div>

    <div class="col-sm-6">
    <label> <h5>Serial Number</h5> </label>
    </div>
    <?

    $matLoopCount = 1;
    $faultySql = mysqli_query($con, "select * from generatefaultymaterialrequest where mis_id='" . $mis_id . "' and materialRequestType='clarify' and materialRequestLevel=3");
    if ($faultySqlResult = mysqli_fetch_assoc($faultySql)) {
        $generatefaultymaterialrequestID = $faultySqlResult['id'];
        $mat_sql = mysqli_query($con, "select * from generatefaultymaterialrequestdetails where requestId='" . $generatefaultymaterialrequestID . "'");
        while ($mat_sqlResult = mysqli_fetch_assoc($mat_sql)) {
            $value = $mat_sqlResult['MaterialName'];
            ?>                     
                                                                                                                                                                                                                                                                                                    <div class="col-sm-6">
                                                                                                                                                                                                                                                                                                        <input type="checkbox" name="materialToReplace[]" value="<?php echo $value; ?>" required>  <?php echo $value; ?>
                                                                                                                                                                                                                                                                                                    </div>  
                                                                                                                                                                                                                                                                                                    <div class="col-sm-6">
                                                                                                                                                                                                                                                                                                        <input class="form-control" type="text" name="serial_number[]" required>  
                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                    <br />
            
                                                                                             <?
        }
    }
    ?>
    



`;
        }


        $("#status_col").html(html);
    });



</script>


<script>
    let editor;

    ClassicEditor
        .create(document.querySelector('#editor1'))
        .then(newEditor => {
            editor = newEditor;
        })
        .catch(error => {
            console.error(error);
        });


</script>


<script>
    // CKEDITOR.replace('editor1');

    function populateToemails(lho) {
        $.ajax({
            type: "POST",
            url: './populateToemails.php',
            data: { lho: lho },
            success: function (msg) {
                var html = msg[0].html;
                var emails = msg[0].emails;
                $("#toemailtoSend").append(html);
                $("#toemailtoSendVal").val(emails);
            },
            error: function (xhr, status, error) {
                console.error("Ajax request failed:", error);
            }
        });
    }



    function populateCcemails(lho) {
        $.ajax({
            type: "POST",
            url: './populateCcemails.php',
            data: { lho: lho },
            success: function (msg) {
                var html = msg[0].html;
                var emails = msg[0].emails;
                $("#ccemailtoSend").append(html);
                $("#ccemailtoSendVal").val(emails);
            },
            error: function (xhr, status, error) {
                console.error("Ajax request failed:", error);
            }
        });
    }


    $(document).on('change', '.border-checkbox', function () {
        // Get the matLoopCount from the checkbox's ID
        var matLoopCount = this.id.replace('checkbox', '');
        handleCheckboxChange(this, matLoopCount);
    });

    function handleCheckboxChange(checkbox, matLoopCount) {
        // Get the corresponding select and input elements based on the matLoopCount
        var selectElement = document.getElementById("select_" + matLoopCount);
        var inputElement = document.getElementById("input_" + matLoopCount);
        var input_qty = document.getElementById("input_qty_" + matLoopCount);



        // Check if the select and input elements exist before setting the 'required' attribute
        if (selectElement && inputElement) {
            if (checkbox.checked) {
                selectElement.required = true;
                inputElement.required = true;
                input_qty.required = true;

            } else {
                selectElement.required = false;
                inputElement.required = false;
                input_qty.required = false;

            }
        }
    }
    function throttle(f, delay) {
        var timer = null;
        return function () {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = window.setTimeout(function () {
                f.apply(context, args);
            },
                delay || 1000);
        };
    }



    $("#saveButton").on('click', function (event) {
        event.preventDefault(); // Prevent default form submission

        // Get the CKEditor content
        // var editorData = CKEDITOR.instances.editor1.getData();

        const editorData = editor.getData();



        // Dynamic data gathering using FormData
        var formData = new FormData(document.querySelector('.replyform'));

        // Append the CKEditor content to FormData
        formData.append('emailbody', editorData);

        $(".misloader").html(` <div class="overlay"></div>
                <div class="loader-demo-box">
                    <div class="jumping-dots-loader">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>`);

        // AJAX request
        $.ajax({
            type: "POST",
            url: './process_reply.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // Handle the AJAX response
                handleAjaxResponse(response);
            },
            error: function (xhr, status, error) {
                // Handle AJAX error
                handleAjaxError();
            },
            complete: function () {
                // Cleanup or additional actions after AJAX request
                $(".misloader").html('');
            }
        });
    });


    // Function to perform form validation

    // Function to handle validation errors
    function handleValidationErrors(fieldsToHighlight) {
        fieldsToHighlight.forEach(function (fieldId) {
            $(fieldId).addClass("chighlight");
        });

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please fill in all required fields.'
        });
    }

    // Function to handle AJAX response
    function handleAjaxResponse(response) {
        console.log(response);

        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message
            }).then(function () {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message
            });
        }
    }

    // Function to handle AJAX error
    function handleAjaxError() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while saving the form data.'
        });
    }

</script>


<script>


function removeTagCc(tag) {
        event.preventDefault();
        var tagText = $(tag).prev().text(); // Get the email address from the previous span element
        $(tag).parent().remove();

        // Remove the corresponding email address from the hidden input value
        var currentEmails = $('#ccemailtoSendVal').val().split(',');
        var updatedEmails = currentEmails.filter(function (email) {
            return email.trim() !== tagText.trim();
        });
        $('#ccemailtoSendVal').val(updatedEmails.join(','));
    }



    function removeTag(tag) {
        event.preventDefault();
        var tagText = $(tag).prev().text(); // Get the email address from the previous span element

        // Remove the tag from the visible area
        $(tag).parent().remove();

        // Remove the corresponding email address from the hidden input value
        var currentEmails = $('#toemailtoSendVal').val().split(',');
        var updatedEmails = currentEmails.filter(function (email) {
            return email.trim() !== tagText.trim();
        });
        $('#toemailtoSendVal').val(updatedEmails.join(','));
    }


    window.onload = function () {
        // Get all anchor tags with the "removeTag" onclick attribute
        var removeButtons = document.querySelectorAll('a[onclick="removeTag(this)"]');

        // Add event listener to each anchor tag to prevent default behavior
        removeButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the default behavior
                removeTag(this); // Call the removeTag function
            });
        });
    };



    $(document).ready(function () {
        // Triggered when the form is submitted
        $("#toemailtoSend").html('<span class="tag"><span>' + '<?php echo $email_address; ?>' + '&nbsp;&nbsp;</span><a href="#" class="removeTag" title="Removing tag" onclick="removeTag(this)">x</a></span>');

        var recipientsString = '<?php echo $recipients_string; ?>';

        var recipientsArray = recipientsString.split(',');

        for (var i = 0; i < recipientsArray.length; i++) {
            var email = recipientsArray[i].trim();

            $("#ccemailtoSend").append('<span class="tag"><span>' + email + '&nbsp;&nbsp;</span><a href="#" title="Removing tag" class="removeTag" onclick="removeTagCc(this)">x</a></span>');
        }



        $("#toemailtoSendVal").val('<?php echo $email_address; ?>')


        $("#ccemailtoSendVal").val(recipientsString);

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
                    // console.log(response)
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
                                document.getElementById('emailContactForm').reset();
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


    var brElements = document.querySelectorAll('br');

    // Iterate over the <br> elements
    for (var i = 0; i < brElements.length - 1; i++) {
        // Check if the current <br> is followed by another <br>
        if (brElements[i].nextSibling === brElements[i + 1]) {
            // Remove the current <br> tag
            brElements[i].parentNode.removeChild(brElements[i]);
            // Since we removed the current <br>, we need to decrement i
            i--;
        }
    }





</script>




<? include ('../footer.php'); ?>