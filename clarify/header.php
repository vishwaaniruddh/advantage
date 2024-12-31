<?php include ('config.php');

$base_url = "http://clarify.advantagesb.com/";
// date_default_timezone_set('Asia/Calcutta');




$token = ($_SESSION['ADVANTAGE_advantagetoken'] ? $_SESSION['ADVANTAGE_advantagetoken'] : 'NA');


if (!function_exists('verifyToken')) {
  function verifyToken($token)
  {
    global $con;

    $sql = mysqli_query($con, "select * from user where clarify_token='" . $token . "' and user_status=1");
    if ($sql_result = mysqli_fetch_assoc($sql)) {
      return 1;
    } else {
      return 0;
    }
  }
}

if (verifyToken($token) != 1 || $token == 'NA') {

  ob_start();
  header('Location: http://clarify.advantagesb.com/pages/auth/login.php');

  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> Clarify </title>
  <link rel="stylesheet" href="<?= $base_url; ?>/assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?= $base_url; ?>/assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="<?= $base_url; ?>/assets/vendors/jvectormap/jquery-jvectormap.css">
  <link rel="stylesheet" href="<?= $base_url; ?>/assets/vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="<?= $base_url; ?>/assets/vendors/owl-carousel-2/owl.carousel.min.css">
  <link rel="stylesheet" href="<?= $base_url; ?>/assets/vendors/owl-carousel-2/owl.theme.default.min.css">


  <!-- End plugin css for this page -->

  <!-- inject:css -->
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="<?= $base_url; ?>/assets/css/style.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="<?= $base_url; ?>/assets/images/adv_fav.png" />

  <link rel="icon" href="http://clarify.advantagesb.com/assets/images/adv_fav.png" type="image/png">
  <link rel="shortcut icon" href="http://clarify.advantagesb.com/assets/images/adv_fav.png" type="image/png">


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" type="text/css" href="<?= $base_url; ?>/datatable/dataTables.bootstrap.css">


  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <style>

.dataTables_length{
  width: 100px;
    float: right;
    margin: auto 20%;
}
.pagination>li>a, .pagination>li>span{
  border:none;
  background-color: transparent;
}
    .navbar-profile {
      font-size: 11px;
    }

    #globalfilter {
      display: none;
      transition: display 2.5s ease;
    }

    body {
      font-family: "Encode Sans Semi Expanded", sans-serif;
      font-weight: 700;
      font-style: normal;
    }

    /* .table tbody tr td {
      white-space: nowrap;
    } */
  </style>
</head>

<body style="" class="sidebar-mini">
  <div class="container-scroller">
    <?php include ('nav.php'); ?>
    <!-- partial -->
    
    <style>
              @media (min-width: 990px) {
              
                .container-fluid.page-body-wrapper{
                  width: calc(100vw - 244px);
                }
              }
    </style>

    <div class="container-fluid page-body-wrapper" >
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar p-0 fixed-top d-flex flex-row">
        <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
          <a class="navbar-brand brand-logo-mini" href="<?= $base_url; ?>"><img
              src="<?= $base_url; ?>/assets/images/railtellogo.png" alt="logo"></a>
        </div>
        <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch" style="position:relative;">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <ul class="navbar-nav w-100">

            <li class="nav-item w-100 not_mob text-right" style="display:none;">
              <p class="mb-0 d-sm-block navbar-profile-name ">
                <strong>
                  <?= ucwords($username); ?>
                </strong>
              </p>
            </li>
            <li class="nav-item w-100 not_mob">

              <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search" id="searchForm">
                <input type="text" name="atmid" class="form-control" placeholder="Search ATMID" id="atmSearchInput"
                  style="width:100%;" required>
                <div class="input-group-append ms-1">
                  <button type="submit" class="btn btn-primary" id="searchButton">Search</button>
                </div>
              </form>

            </li>

            <li class="nav-item">
              <a href="<?php $_SERVER['REQUEST_URI']; ?>" class="btn btn-primary">Refresh</a>
            </li>


            <li class="nav-item">
              <a href="#" onclick="goBack()" style="margin-left: 15px;" class="btn btn-danger">Back</a>
            </li>

            <li class="nav-item" style="margin-left: 15px;">
              <a class="btn btn-secondry" href="<?= 'http://clarify.advantagesb.com/mis/add_mis.php'; ?>"
                style="white-space: nowrap;">
                New Ticket
              </a>
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

              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
          </button>


          <div class="collapse-down" style="    position: absolute;
    bottom: -54%;
    right: 3%;">
            <i class="mdi mdi-menu-down d-none d-sm-block" onclick="toggleglobalFilter()"
              style="    font-size: 40px;"></i>
          </div>





        </div>


        <?

        if (isset($_REQUEST['globalfilterSubmit'])) {


          $_SESSION['_GLOBAL_LHO'] = $_REQUEST['lhoname'];
          $_SESSION['_GLOBAL_FROMDATE'] = $_REQUEST['fromdate'];
          $_SESSION['_GLOBAL_TODATE'] = $_REQUEST['todate'];



        }

        ?>

        <form action="" id="globalfilter" style="display:none;width:100%;padding:2%;border:2px solid;" method="POST">
          <div class="row">
            <div class="col-sm-4">
              <label for="">LHO</label>
              <select name="lhoname" id="" class="form-control">
                <option value=""> Reset </option>
                <?php
                $lho_sql = mysqli_query($con, "select * from lho where status=1");
                while ($lho_sql_result = mysqli_fetch_assoc($lho_sql)) {
                  $lhoName = $lho_sql_result['lho'];
                  ?>
                  <option value="<?php echo $lhoName; ?>" <? if ($_SESSION['_GLOBAL_LHO'] == $lhoName) {
                       echo 'selected';
                     } ?>>
                    <?php echo $lhoName; ?>
                  </option>
                <? }
                ?>
              </select>
            </div>
            <div class="col-sm-3">
              <label for="">From</label>
              <input type="date" name="fromdate" class="form-control">
            </div>
            <div class="col-sm-3">
              <label for="">To</label>
              <input type="date" name="todate" class="form-control">
            </div>
            <div class="col-sm-2">
              <br>
              <input type="submit" name="globalfilterSubmit" class="btn btn-primary" value="Filter" />
            </div>

          </div>


        </form>




      </nav>


      <script>
        function toggleglobalFilter() {
          var globalfilter = document.getElementById("globalfilter");
          if (globalfilter.style.display === "none") {
            globalfilter.style.display = "block";
          } else {
            globalfilter.style.display = "none";
          }
        }

      </script>


      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">

          <div class="modal fade" id="atmmodal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">History </h5>
                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div id="atmhistoryContent" style="overflow: scroll;max-height: 70vh;"></div>
                </div>
                <div class="modal-footer">

                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="loader-container"
            style="display:none; position:fixed; top:0; left:0; height: 100%; width: 100%; z-index: 99999999; background-color: rgba(0, 0, 0, 0.5);">
            <div class="loader" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
              <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs"
                type="module"></script>
              <dotlottie-player src="https://lottie.host/fb3a1b3b-fce1-444a-b50a-0a22979edbfc/7mq0kF7d5k.json"
                background="transparent" speed="1" loop autoplay></dotlottie-player>
            </div>
          </div>

          <!-- At the end of your HTML file -->
          <script>
            function goBack() {
              if (window.history.length > 1) {
                window.history.back();
              } else {
                alert("Maximum back reached");
              }
            }

            function initializeDataTables() {
              $('#historyTableContent').DataTable({
                dom: 'lBfrtip', // Add the necessary buttons
                buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdfHtml5'
                ]
              });
            }



            document.addEventListener("DOMContentLoaded", function () {
              var searchForm = document.getElementById('searchForm');
              var searchInput = document.getElementById('atmSearchInput');
              var atmhistoryContent = document.getElementById('atmhistoryContent');
              var atmModal = new bootstrap.Modal(document.getElementById('atmmodal')); // Instantiate the modal

              function fetchAtmHistory() {
                  
                  if(searchInput.value){
                        $.ajax({
                            url: '<?= $base_url; ?>/getatmHistory.php',
                            type: 'GET',
                            data: { atmid: searchInput.value },
                            success: function (response) {
                                atmhistoryContent.innerHTML = response;
                                initializeDataTables(); // Reinitialize DataTables after content loaded
                                $('#atmmodal').modal('show'); // Show the modal
                            },
                            error: function () {
                                console.error('Error fetching ATM history data.');
                            },
                            complete: function() {
                                $(".loader-container").css('display', 'none');
                            }
                        });                      
                  }else{
                      alert('Please Enter Valid ATMID ...');
                      $(".loader-container").css('display', 'none');
                  }
                  

}


              searchForm.addEventListener('submit', function (event) {
                event.preventDefault();
                $(".loader-container").css('display', 'block');
                fetchAtmHistory();
              });

              document.getElementById('searchButton').addEventListener('click', function (event) {
                event.preventDefault();
                $(".loader-container").css('display', 'block');
                fetchAtmHistory();
              });

              searchInput.addEventListener('keyup', function (event) {
                if (event.key === 'Enter') {
                  event.preventDefault();
                  $(".loader-container").css('display', 'block');
                  fetchAtmHistory();
                }
              });
            });


          </script>


          <?
          $sql = mysqli_query($con, "select * from projectinstallation where lho=''");
          while ($sql_result = mysqli_fetch_assoc($sql)) {
            $id = $sql_result['id'];
            $atmid = $sql_result['atmid'];

            $sitessql = mysqli_query($con, "select * from sites where atmid ='" . $atmid . "'");
            $sitessql_result = mysqli_fetch_assoc($sitessql);
            $lho = $sitessql_result['LHO'];

            mysqli_query($con, "update projectinstallation set lho ='" . $lho . "' where id='" . $id . "'");

          }

          ?>

          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>