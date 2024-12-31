<? include('../header.php'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="row">
    <div class="col-sm-12 grid-margin">

    <style>
    .hidden {
        display: none;
    }
</style>

<form id="wizardForm" method="post" enctype="multipart/form-data">
    <!-- Part 1: Basic Info -->
    <div class="step">
        <h2>Part 1: Basic Info</h2>
        <label for="atmIdSnap">ATMID1 Snap:</label>
        <input type="file" id="atmIdSnap" name="atmIdSnap" required>
        <br>
        <label for="atmWorking">ATMID 1 Working:</label>
        <select id="atmWorking" name="atmWorking" required>
            <option value="">Select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        <br>
        <button onclick="nextStep()">Next</button>
    </div>

    <!-- Part 2: Network -->
    <div class="step hidden">
        <h2>Part 2: Network</h2>
        <!-- Add Network fields here -->
        <button onclick="prevStep()">Previous</button>
        <button onclick="nextStep()">Next</button>
    </div>

    <!-- Part 3: Back Room -->
    <div class="step hidden">
        <h2>Part 3: Back Room</h2>
        <!-- Add Back Room fields here -->
        <button onclick="prevStep()">Previous</button>
        <button onclick="nextStep()">Next</button>
    </div>

    <!-- Part 4: EM Lock -->
    <div class="step hidden">
        <h2>Part 4: EM Lock</h2>
        <!-- Add EM Lock fields here -->
        <button onclick="prevStep()">Previous</button>
        <button onclick="nextStep()">Next</button>
    </div>

    <!-- Part 5: Positioning -->
    <div class="step hidden">
        <h2>Part 5: Positioning</h2>
        <!-- Add Positioning fields here -->
        <button onclick="prevStep()">Previous</button>
        <button onclick="nextStep()">Next</button>
    </div>

    <!-- Part 6: UPS -->
    <div class="step hidden">
        <h2>Part 6: UPS</h2>
        <!-- Add UPS fields here -->
        <button onclick="prevStep()">Previous</button>
        <button onclick="nextStep()">Next</button>
    </div>

    <!-- Part 7: Power -->
    <div class="step hidden">
        <h2>Part 7: Power</h2>
        <!-- Add Power fields here -->
        <button onclick="prevStep()">Previous</button>
        <button onclick="nextStep()">Next</button>
    </div>

    <!-- Part 8: Finishing -->
    <div class="step hidden">
        <h2>Part 8: Finishing</h2>
        <!-- Add Finishing fields here -->
        <button onclick="prevStep()">Previous</button>
        <button type="submit">Submit</button>
    </div>
</form>

<script>
    let currentStep = 0;
    const steps = document.querySelectorAll('.step');

    function nextStep() {
        if (currentStep < steps.length - 1) {
            steps[currentStep].classList.add('hidden');
            currentStep++;
            steps[currentStep].classList.remove('hidden');
        }
    }

    function prevStep() {
        if (currentStep > 0) {
            steps[currentStep].classList.add('hidden');
            currentStep--;
            steps[currentStep].classList.remove('hidden');
        }
    }
</script>
    </div>
</div>


<? include('../footer.php'); ?>
