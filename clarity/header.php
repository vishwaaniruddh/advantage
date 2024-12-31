<?php include('config.php');

// date_default_timezone_set('Asia/Calcutta');




$token = ($_SESSION['ADVANTAGE_advantagetoken'] ? $_SESSION['ADVANTAGE_advantagetoken'] : 'NA');


if (!function_exists('verifyToken')) {
  function verifyToken($token)
  {
    global $con;

    $sql = mysqli_query($con, "select * from user where token='" . $token . "' and user_status=1");
    if ($sql_result = mysqli_fetch_assoc($sql)) {
      return 1;
    } else {
      return 0;
    }
  }
}


if (verifyToken($token) != 1 || $token == 'NA') {

  ob_start();
  header('Location: /pages/auth/login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
  <title> Clarity </title>
  <link rel="stylesheet" href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/vendors/jvectormap/jquery-jvectormap.css">
  <link rel="stylesheet" href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/vendors/owl-carousel-2/owl.carousel.min.css">
  <link rel="stylesheet"
    href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/vendors/owl-carousel-2/owl.theme.default.min.css">


  <!-- End plugin css for this page -->

  <!-- inject:css -->
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/css/style.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/images/adv_fav.png" />

  <link rel="icon" href="http://clarity.advantagesb.com/assets/images/adv_fav.png" type="image/png">
  <link rel="shortcut icon" href="http://clarity.advantagesb.com/assets/images/adv_fav.png" type="image/png">


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" type="text/css" href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/datatable/dataTables.bootstrap.css">


  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    div:where(.swal2-icon).swal2-success [class^=swal2-success-line][class$=tip] {
    top: 3.275em;
    left: 2.0125em;
    width: 1.5625em;
    transform: rotate(45deg);
}
div:where(.swal2-icon).swal2-success [class^=swal2-success-line][class$=long] {
    top: 2.775em;
    right: 1em;
    width: 2.9375em;
    transform: rotate(-45deg);
}
    body {
      font-family: "Encode Sans Semi Expanded", sans-serif;
      font-weight: 700;
      font-style: normal;
    }
  </style>
</head>

<body style="zoom:100%;">
  <div class="container-scroller">
    <?php include('nav.php'); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar p-0 fixed-top d-flex flex-row">
        <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
          <a class="navbar-brand brand-logo-mini" href="<? $_SERVER["DOCUMENT_ROOT"]; ?>/index.php"><img
              src="<? $_SERVER["DOCUMENT_ROOT"]; ?>/assets/images/railtellogo.png" alt="logo"></a>
        </div>
        <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <ul class="navbar-nav w-100">

            <li class="nav-item w-100 only_web text-right" style="display:none;">
              <p class="mb-0 d-sm-block navbar-profile-name ">
                <strong>
                  <?= ucwords($username); ?>
                </strong>
              </p>
            </li>
            <li class="nav-item w-100 not_mob">


              <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search" id="searchForm">
                <input type="text" name="atmid" class="form-control" placeholder="Search ATMID" id="atmSearchInput"
                  style="width:100%;">
              </form>

            </li>

            <li class="nav-item">
              <a href="<?php $_SERVER['REQUEST_URI']; ?>" class="btn btn-primary">Refresh</a>
            </li>


            <li class="nav-item">
              <a href="#" onclick="goBack()" style="margin-left: 15px;" class="btn btn-danger">Back</a>
            </li>


          </ul>
          <ul class="navbar-nav navbar-nav-right">

            <li class="nav-item dropdown">
              <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                <div class="navbar-profile">
                  <p class="mb-0 d-none d-sm-block navbar-profile-name ">
                    <strong>
                      <?= ucwords($username); ?>
                    </strong>
                  </p>
                  <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                aria-labelledby="profileDropdown">
                <h6 class="p-3 mb-0">Profile</h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-settings text-success"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject mb-1">Settings</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item" href="<?= $base_url; ?>/logout.php">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-logout text-danger"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject mb-1">Log out</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <p class="p-3 mb-0 text-center">Advanced settings</p>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="main-panel" style="zoom:100%;">
        <div class="content-wrapper">



          <script>
            function unlockIPs() {
              $.ajax({
                type: 'GET',
                url: '<? $_SERVER["DOCUMENT_ROOT"]; ?>/unLockIPs.php',
                success: function (response) { },
                error: function (xhr, status, error) {
                  console.error(xhr.responseText);
                }
              });
            }

            // Call unlockIPs function every 10 seconds
            setInterval(unlockIPs, 10000); // 10,000 milliseconds = 10 seconds
          </script>




          <div class="modal fade" id="atmmodal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">New message</h5>
                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="modalClose">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div id="atmhistoryContent" style="overflow: scroll;max-height: 70vh;"></div>
                </div>
                <div class="modal-footer">

                  <button type="button" class="modalClose btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>


          <!-- At the end of your HTML file -->
          <script>


            document.addEventListener("DOMContentLoaded", function () {
              var searchForm = document.getElementById('searchForm');
              var searchInput = document.getElementById('atmSearchInput');
              var atmhistoryContent = document.getElementById('atmhistoryContent');
              var atmModal = new bootstrap.Modal(document.getElementById('atmmodal')); // Instantiate the modal

              searchForm.addEventListener('submit', function (event) {
                event.preventDefault();

                // Fetch data using AJAX
                $.ajax({
                  url: '<? $_SERVER["DOCUMENT_ROOT"]; ?>/getatmHistory.php',
                  type: 'GET',
                  data: { atmid: searchInput.value },
                  success: function (response) {
                    // Update #atmhistoryContent with the response
                    atmhistoryContent.innerHTML = response;

                    // Trigger modal on form submission
                    var myModal = new bootstrap.Modal(document.getElementById('atmmodal'));
                    myModal.show();
                  },
                  error: function () {
                    console.error('Error fetching ATM history data.');
                  }
                });
              });

              searchInput.addEventListener('keyup', function (event) {
                // Check if Enter key is pressed (keyCode 13)
                if (event.key === 'Enter') {
                  event.preventDefault();

                  // Fetch data using AJAX
                  $.ajax({
                    url: '<? $_SERVER["DOCUMENT_ROOT"]; ?>/getatmHistory.php',
                    type: 'GET',
                    data: { atmid: searchInput.value },
                    success: function (response) {
                      // Update #atmhistoryContent with the response
                      atmhistoryContent.innerHTML = response;

                      // Trigger modal on Enter key press
                      var myModal = new bootstrap.Modal(document.getElementById('atmmodal'));
                      myModal.show();
                    },
                    error: function () {
                      console.error('Error fetching ATM history data.');
                    }
                  });
                }
              });
            });

            function goBack() {
              if (window.history.length > 1) {
                window.history.back();
              } else {
                alert("Maximum back reached");
              }
            }

            $(document).ready(function() {
            $('.modalClose').click(function() {
                $('.modal-backdrop.fade.show').remove();
            });
        });
          </script>
          