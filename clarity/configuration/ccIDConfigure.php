<? include ('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">


        <?
        $routerSerialNumber = $_REQUEST['serial_no'];
        $atmid = $_REQUEST['atmid'];

        $created_by_name = getUsername($userid);
        if (isset($_REQUEST['submit'])) {
            $attribute = $_REQUEST['attribute'];
            $serialNumber = $_REQUEST['serialNumber'];


            $i = 0;
            foreach ($attribute as $attribute_key => $attribute_value) {

                $sql = "INSERT into ccidconfiguration(serialNumber,atmid,operator,ccid,status,created_at,created_by,created_by_name) 
                        VALUES('" . $routerSerialNumber . "','" . $atmid . "','" . $attribute_value . "','" . $serialNumber[$i] . "',1,'" . $datetime . "','" . $userid . "','" . $created_by_name . "')";

                mysqli_query($con, $sql);


                $invUpdate = "update inventory set status=0 where serial_no like '" . $serialNumber[$i] . "'";
                // mysqli_query($con, $invUpdate);
                $i++;
            }

            ?>

<script>
    alert('CCID Assigned !');
    window.location.href='./ccidConfiguration.php'
</script>
            <?




        }


        ?>
        <form action="<? $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="hidden" name="atmid" value="<?php echo $atmid; ?>">
            <input type="hidden" name="routerSerialNumber" value="<?php echo $routerSerialNumber; ?>">


            <div id="attributeFields">


                <?

                $sql = mysqli_query($con, "select * from boq where needSerialNumber=1 and status=1 and attribute<>'router'");
                while ($sql_result = mysqli_fetch_assoc($sql)) {

                    $material_name = $sql_result['value'];

                    ?>

                    <div class="attribute-field" style="display: flex;">
                        <input type="text" class="form-control" name="attribute[]" value="<?= $material_name; ?>"
                            style="background-color: #e9ecef;opacity: 1;width: 25%;" readonly>

                        <?php 

                        echo '<input type="text" name="value[]" class="form-control" placeholder="Search Serial Number ..." style="width: 15%;" required>
                <select name="serialNumber[]" class="serial-number-list form-control" style="width: 50%;" required>
                </select>';
                        ?>
                    </div>
                <? }
                ?>




            </div>

            <br>
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>
</div>


<script>
    async function fetchSerialNumbers(materialInput, valueInput, serialNumberList) {
        var material = materialInput.value;
        var value = valueInput.value;

        // Store the current selected value of the dropdown
        var selectedValue = serialNumberList.value;

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "../inventory/search_serial_number.php?material=" + encodeURIComponent(material) + "&value=" + encodeURIComponent(value));
        xhr.onload = function () {
            if (xhr.status === 200) {
                var serialNumbers = JSON.parse(xhr.responseText);

                serialNumberList.innerHTML = "";

                // Restore the previously selected value of the dropdown
                var selectOption = document.createElement("option");
                selectOption.text = "-- select --";
                selectOption.value = "";
                serialNumberList.appendChild(selectOption);

                if (serialNumbers && serialNumbers.length > 0) {
                    for (var i = 0; i < serialNumbers.length; i++) {
                        var option = document.createElement("option");
                        option.value = serialNumbers[i];
                        option.text = serialNumbers[i];
                        serialNumberList.appendChild(option);
                    }
                    serialNumberList.disabled = false;
                } else {
                    // If no serial numbers received from the API, set the placeholder option as selected and disable the input.
                    serialNumberList.value = "";
                    serialNumberList.disabled = true;
                }

                // Set the selected value back to the dropdown
                serialNumberList.value = selectedValue;
            } else {
                console.error(xhr.responseText);
            }
        };
        xhr.send();
    }


    var attributeFields = document.getElementById("attributeFields");

    attributeFields.addEventListener(
        "input",
        throttle(function (event) {
            var target = event.target;
            if (target.tagName === "INPUT" && target.name === "value[]") {
                var materialInput = target.previousElementSibling;
                var serialNumberList = target.nextElementSibling;
                fetchSerialNumbers(materialInput, target, serialNumberList);
            }
        })
    );


    function throttle(f, delay) {
        var timer = null;
        return function () {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = window.setTimeout(function () {
                f.apply(context, args);
            }, delay || 500);
        };
    }
</script>

<? include ('../footer.php'); ?>