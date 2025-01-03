<? include ('../header.php');

$isVendor = $_SESSION['isVendor'];
$islho = $_SESSION['islho'];
$ADVANTAGE_level = $_SESSION['ADVANTAGE_level'];

if ($islho == 0 && $isVendor == 0) {


    function getSitesInfo($siteid, $parameter)
    {
        global $con;

        $sql = mysqli_query($con, "select $parameter from sites where id='" . $siteid . "'");
        $sql_result = mysqli_fetch_assoc($sql);
        return $sql_result[$parameter];
    }

    ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <div class="row">
        <div class="card">
            <div class="card-block" style="overflow:auto;">
                <h5>Material Dispatch</h5>
                <hr />

                <table class="table table-hover table-styling table-xs">
                    <thead>
                        <tr class="table-primary">
                            <th>Srno</th>
                            <th>ATMID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Request Generated Date</th>
                            <th>Request Generated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $siteid = $_REQUEST['siteid'];
                        $statement = "select a.siteid,a.material_name,a.quantity,a.created_at,a.created_by,a.vendorId from 
                                    material_requests a 
                                    LEFT JOIN boq b 
                                    ON a.material_name = b.value 
                                    where siteid='" . $siteid . "' and a.status='pending' and a.material_name<>'' and b.status=1 and a.vendorId<>0 order by b.needSerialNumber desc";

                        $sql = mysqli_query($con, $statement);
                        while ($sql_result = mysqli_fetch_assoc($sql)) {

                            $siteid = $sql_result['siteid'];
                            $atmid = getSitesInfo($siteid, 'atmid');
                            $material_name = $sql_result['material_name'];
                            $quantity = $sql_result['quantity'];
                            $created_at = $sql_result['created_at'];
                            $created_by = $sql_result['created_by'];
                            $vendorId = $sql_result['vendorId'];
                            ?>
                            <tr>
                                <td>
                                    <?= $i; ?>
                                </td>
                                <td class="strong">
                                    <?= $atmid; ?>
                                </td>
                                <td>
                                    <?= $material_name; ?>
                                </td>
                                <td>
                                    <?= $quantity; ?>
                                </td>
                                <td>
                                    <?= $created_at; ?>
                                </td>
                                <td>
                                    <?= getUsername($created_by, 0); ?>
                                </td>
                            </tr>

                            <?php $i++;
                        }
                        ?>
                    </tbody>
                </table>
                <hr />


                <?

                $atmsql = mysqli_query($con, "select * from sites where atmid='" . $atmid . "'");
                $atmsql_result = mysqli_fetch_assoc($atmsql);
                $networkIP = $atmsql_result['networkIP'];
                $routerIP = $atmsql_result['routerIP'];
                $atmIP = $atmsql_result['atmIP'];
                $subnetIP = $atmsql_result['subnetIP'];


                echo '<div class="ipInfo" style="    display: flex;justify-content: space-between;">
                                    <p><strong>Network IP :</strong> ' . $networkIP . '</p>
                                    <p><strong>Router IP :</strong> ' . $routerIP . '</p>
                                    <p><strong>ATM IP :</strong> ' . $atmIP . '</p>
                                    <p><strong>Subnet :</strong> ' . $subnetIP . '</p>
                                </div>';
                ?>
                <hr>
                <form id="attributeForm" action="confirmSendMaterialRequest.php" method="POST">
                    <input type="hidden" name="atmid" value="<? echo $atmid; ?>" />
                    <input type="hidden" name="siteid" value="<? echo $siteid; ?>" />
                    <input type="hidden" name="vendorId" value="<? echo $vendorId; ?>" />

                    <div id="attributeFields">
                        <?php
                        // echo $statement;
                        $sql = mysqli_query($con, $statement);
                        while ($sql_result = mysqli_fetch_assoc($sql)) {
                            $material_name = $sql_result['material_name']; ?>
                            <div class="attribute-field">
                                <input type="text" name="attribute[]" value="<?= $material_name; ?>"
                                    style="background-color: #e9ecef;opacity: 1;width: 25%;" readonly>
                                <?
                                $serialSql = mysqli_query($con, "Select * from boq where value='" . $material_name . "' order by id desc");
                                $serialSqlResult = mysqli_fetch_assoc($serialSql);
                                $needSerialNumber = $serialSqlResult['needSerialNumber'];
                                if ($needSerialNumber == 1) {

                                    $getSerialSql = mysqli_query($con, "SELECT * FROM routerConfiguration where atmid='" . $atmid . "' order by id desc");
                                    $getSerialSqlResult = mysqli_fetch_assoc($getSerialSql);
                                    $serialNumber = $getSerialSqlResult['serialNumber'];
                                    $sealNumber = $getSerialSqlResult['sealNumber'];

                                    if (trim($material_name) == 'Router') {
                                        echo '<input type="text" name="value[]" placeholder="Search Serial Number ..." style="width: 15%;    background-color: #e9ecef;" value="' . $serialNumber . '" readonly required>
                                                         <select name="serialNumber[]" class="serial-number-list" style="width: 20%;    background-color: #e9ecef; " required>
                                                             <option value="' . $serialNumber . '">' . $serialNumber . '</option>
                                                         </select>';

                                    } else if (strpos(strtolower($material_name), 'sim') !== false) {

                                        // echo "select * from ccidconfiguration where serialNumber='" . $serialNumber . "' and operator='" . $material_name . "' and status=1 order by id desc" ; 
                                        $sim_sql = mysqli_query($con, "select * from ccidconfiguration where serialNumber='" . $serialNumber . "'  and operator='" . $material_name . "' and status=1 order by id desc");
                                        $sim_sql_result = mysqli_fetch_assoc($sim_sql);

                                        $ccid = $sim_sql_result['ccid'];

                                        echo '<input type="text" name="value[]" placeholder="Search Serial Number ..." style="width: 15%; background-color: #e9ecef;" value="' . $ccid . '" readonly required>
                                    <select name="serialNumber[]" class="serial-number-list" style="width: 20%;" required>
                                    <option value="' . $ccid . '">' . $ccid . '</option>

                                    </select>';
                                    }


                                    // else if(trim($material_name)=='Security seal'){
                                    //     echo '<input type="text" name="value[]" placeholder="Value" style="width: 15%;    background-color: #e9ecef;" value="' . $sealNumber . '" readonly required>
                                    //          <select name="serialNumber[]" class="serial-number-list" style="width: 20%;    background-color: #e9ecef;" required>
                                    //              <option value="'.$sealNumber.'">'.$sealNumber.'</option>
                                    //          </select>';
                        
                                    // }
                                    else {
                                        echo '<input type="text" name="value[]" placeholder="Search Serial Number ..." style="width: 15%;" required>
                                                         <select name="serialNumber[]" class="serial-number-list" style="width: 20%;" required>
                                                         </select>';

                                    }

                                    ?>
                                <? }
                                if (trim($material_name) == 'Router') {


                                } else if (strpos(strtolower($material_name), 'sim') !== false) {

                                } else {
                                    echo '<button class="remove-field" onclick="removeAttributeField(event)" style="background: red;color: white;">Remove</button>';
                                }
                                ?>


                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <button type="button" onclick="addAttributeField()">Add Attribute</button>

                    <?php
                    if ($vendorId > 0) {
                        ?>
                        <button type="submit" onclick="validateForm(event)">Submit</button>

                    <?
                    } else {
                        echo 'No Vendor Found !';
                    }
                    ?>
                </form>




                <script>
                    function areSerialNumbersUnique(serialNumberLists) {
                        var serialNumbersArray = [];
                        for (var i = 0; i < serialNumberLists.length; i++) {
                            var serialNumberList = serialNumberLists[i];
                            serialNumbersArray.push(serialNumberList.value);
                        }
                        const uniqueSet = new Set();
                        for (let i = 0; i < serialNumbersArray.length; i++) {
                            const serialNumber = serialNumbersArray[i];
                            if (uniqueSet.has(serialNumber)) {
                                return false;
                            }
                            uniqueSet.add(serialNumber);
                        }
                        return true;
                    }

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

                    //   <input type="text" name="value[]" placeholder="Value" style="width: 15%;" required>
                    //   <select name="serialNumber[]" class="serial-number-list" style="width: 20%;" required>
                    //           <option value="">-- select --</option>
                    //   </select>
                    function addAttributeField() {
                        var attributeFields = document.getElementById("attributeFields");
                        var attributeField = document.createElement("div");
                        attributeField.className = "attribute-field";
                        attributeField.innerHTML = `
                                          <input type="text" name="attribute[]" value="" style="width: 25%;">
                                          <button class="remove-field" onclick="removeAttributeField(event)">Remove</button>
                                        `;
                        attributeFields.appendChild(attributeField);
                    }

                    function removeAttributeField(event) {
                        var attributeField = event.target.parentNode;
                        attributeField.remove();
                    }

                    async function fetchSerialNumbers(materialInput, valueInput, serialNumberList) {
                        var material = materialInput.value;
                        var value = valueInput.value;

                        // Store the current selected value of the dropdown
                        var selectedValue = serialNumberList.value;

                        var xhr = new XMLHttpRequest();
                        xhr.open("GET", "search_serial_number.php?material=" + encodeURIComponent(material) + "&value=" + encodeURIComponent(value));
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

                    function validateForm(event) {
                        var attributeInputs = document.getElementsByName("attribute[]");
                        var valueInputs = document.getElementsByName("value[]");
                        var serialNumberLists = document.getElementsByClassName("serial-number-list");

                        for (var i = 0; i < valueInputs.length; i++) {
                            var valueInput = valueInputs[i];
                            var serialNumberList = serialNumberLists[i];
                            var materialInput = valueInput.previousElementSibling;

                            if (valueInput.value !== "") {
                                if (serialNumberList.value === "") {
                                    event.preventDefault();
                                    alert('Please select a serial number for attribute "' + attributeInputs[i].value + '".');
                                    return;
                                }
                            }
                        }
                        var serialNumberLists = document.getElementsByClassName("serial-number-list");
                        if (areSerialNumbersUnique(serialNumberLists)) {
                            return true;
                        }
                    }

                    function submitForm() {
                        var attributeInputs = document.getElementsByName("attribute[]");
                        var valueInputs = document.getElementsByName("value[]");
                        var serialNumberLists = document.getElementsByClassName("serial-number-list");

                        var promises = Array.from(valueInputs).map((valueInput, i) => {
                            var serialNumberList = serialNumberLists[i];
                            var materialInput = valueInput.previousElementSibling;
                            return fetchSerialNumbers(materialInput, valueInput, serialNumberList);
                        });

                        Promise.all(promises).then(function () {
                            console.log(validateForm(event));
                            if (validateForm(event)) {
                                document.getElementById("attributeForm").submit();
                            }
                        });
                    }

                    document.getElementById("attributeForm").addEventListener("submit", function (event) {
                        event.preventDefault();
                        submitForm();
                    });

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

                </script>
            </div>
        </div>

    </div>

<?php


} else {
    echo 'Your are not authorised to see this page !';
}

?>

<? include ('../footer.php'); ?>