    <style>
      .box {
            margin: 10px;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            white-space: nowrap;
            padding: 20px;
        }
        .box:hover, .box.active {
            background-color: #d0d0d0;
        }
        .boxGrid {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
    </style>

<div class="boxGrid">
    <div class="box" onclick="redirectTo('feasibilitycheck1.php')" id="box1">1: Basic</div>
    <div class="box" onclick="redirectTo('feasibilitycheck2.php')" id="box2">2: Network</div>
    <div class="box" onclick="redirectTo('feasibilitycheck3.php')" id="box3">3: BackRoom</div>
    <div class="box" onclick="redirectTo('feasibilitycheck4.php')" id="box4">4: EMLock</div>
    <div class="box" onclick="redirectTo('feasibilitycheck5.php')" id="box5">5: Positioning</div>
    <div class="box" onclick="redirectTo('feasibilitycheck6.php')" id="box6">6: UPS</div>
    <div class="box" onclick="redirectTo('feasibilitycheck7.php')" id="box7">7: Power</div>
    <div class="box" onclick="redirectTo('feasibilitycheck8.php')" id="box8">8: Finishing</div>
    
</div>

<hr />
    <script>
       function redirectTo(page) {
        const currentUrl = new URL(window.location.href);
        const params = currentUrl.search;
        window.location.href = page + params;
    }

    // Highlight the active box based on the current URL
    document.addEventListener('DOMContentLoaded', function() {
        const currentUrl = window.location.href;
        const boxes = document.querySelectorAll('.box');
        
        boxes.forEach(box => {
            if (currentUrl.includes(box.getAttribute('onclick').split("'")[1])) {
                box.classList.add('active');
            }
        });
    });
    </script>
