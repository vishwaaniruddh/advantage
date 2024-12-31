<div class="row previous_replies" style="padding: 15px;">
    <?php


    $history_sql = mysqli_query($con, "Select * from emails where message_id='" . $message_id . "' and message_id<>'' order by email_id asc");
    if ($history_sql && mysqli_num_rows($history_sql) > 0) {

        while ($history_sql_result = mysqli_fetch_assoc($history_sql)) {

            $emailId = $history_sql_result['email_id'];
            $historyRemarks = $history_sql_result['content_body'];
            $historyRemarks_with_line_breaks = nl2br($historyRemarks);
            $from_email = $history_sql_result['from_email'];
            if ($from_email != 'noc@advantagesb.com') {
                $nameemail = htmlspecialchars($from_email, ENT_QUOTES, 'UTF-8');
                $email_parts = explode('<', $from_email);
                $email_address = trim($email_parts[1], '>');
            } else {
                $nameemail = 'noc@advantagesb.com';
                $email_address = $nameemail;
            }
            ?>
            <div class="card col-sm-12" style="margin: 15px auto;padding: 15px;    font-weight: 400;">
                <p>
                    <b>
                        <a href="#">From :
                            <?php echo $nameemail; ?>
                        </a>
                    </b>
                </p>

                <?php
                // echo filterLines(extractContentAfterDate($historyRemarks_with_line_breaks));
                echo filterLines($historyRemarks_with_line_breaks);

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
                        <a href="../<?php echo $file_path; ?>" >
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






        $recipients_sql = mysqli_query($con, "SELECT * FROM recipients where email_id='" . $emailId . "' and recipient_email <>'noc@advantagesb.com'");
        if ($recipients_sql && mysqli_num_rows($recipients_sql) > 0) {

            while ($recipients_sql_result = mysqli_fetch_assoc($recipients_sql)) {
                $recipients[] = $recipients_sql_result['recipient_email'];
            }

            $recipients_string = implode(',', $recipients);

        }
    }else {
                $nameemail = 'noc@advantagesb.com';
                $email_address = $nameemail;
                $recipients_string = '';
            }





    ?>


</div>