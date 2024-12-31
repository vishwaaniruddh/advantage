<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Clarify | Sign Up</title>
    <link rel="stylesheet" href="../../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">

    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="icon" href="http://clarify.advantagesb.com/assets/images/adv_fav.png" type="image/png">
    <link rel="shortcut icon" href="http://clarify.advantagesb.com/assets/images/adv_fav.png" type="image/png">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>


<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper" style="margin-left: inherit !important;">
            <div class="row w-100 m-0">
                <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
                    <div class="card col-lg-4 mx-auto">
                        <div class="card-body px-5 py-5">


                            <h3 class="card-title text-left mb-3">Sign Up</h3>
                            <form id="registerForm" class="login100-form validate-form" method="POST">
                                <div class="row">
                                    <div class=" col-md-12 form-group">
                                        <label>Full Name *</label>
                                        <input type="text" name="full_name" class="form-control p_input" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label> Email *</label>
                                    <input type="text" name="username" class="form-control p_input" required>
                                </div>
                                
                                <div class="form-group">
                                    <label> Contact Number *</label>
                                    <input type="text" name="contact" class="form-control p_input" required>
                                </div>

                                <div class="form-group">
                                    <label>Password *</label>
                                    <input type="password" name="password" class="form-control p_input" required>
                                </div>
                                <div class="text-center">
                                    <a href="./login.php" class="btn btn-secondry">Login</a>

                                    <button type="submit" class="btn btn-primary ">Sign UP</button>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script>


        $(document).ready(function () {
            $("#registerForm").submit(function (e) {
                e.preventDefault(); // Prevent default form submission

                $.ajax({
                    type: "POST",
                    url: "process_register.php", // Change this to the actual URL of your login process script
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function (response) {

                        console.log(response);

                        if (response.success) {
                            Swal.fire({
                                title: "Register Successful",
                                text: "Redirecting...",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500,
                                didClose: () => {
                                    window.location.href = '../../' + response.redirect;
                                },
                            });
                        } else {
                            Swal.fire({
                                title: "Login Failed",
                                text: response.message,
                                icon: "error",
                                showConfirmButton: true, // You can use true or false based on your preference
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: "Error",
                            text: "An error occurred. Please try again later.",
                            icon: "error",
                            showConfirmButton: true, // You can use true or false based on your preference
                        });
                    }
                });
            });
        });

    </script>



    <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../../assets/js/off-canvas.js"></script>
    <script src="../../assets/js/hoverable-collapse.js"></script>
    <script src="../../assets/js/misc.js"></script>
    <script src="../../assets/js/settings.js"></script>
    <script src="../../assets/js/todolist.js"></script>
</body>

</html>