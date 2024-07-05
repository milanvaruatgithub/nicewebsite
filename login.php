<?php


require("connection.php");

$loginerror = '';
$login_user = '';

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $que = "SELECT user_first_name,user_last_name FROM register_user WHERE user_email='$email'  AND user_password='$password'";

    $result = $kg_con->query($que);
    $row = $result->fetch_assoc();

    if (mysqli_num_rows($result) == 1) {
        session_start();
        $_SESSION['login_user1'] = $row['user_first_name'];

        $_SESSION['login_user2'] = $row['user_last_name'];

        // $login_user = $_SESSION['login_user'];


        header("location:index.php");
        exit;
        // echo "success";
    } else {
        $loginerror = "Invalid user name or password";
    }
}
include("header.php");
?>
<html>

<head>
    <style>
        .invalid-user {
            color: red;
            font-size: 15px;
        }

        .errors {
            color: red;

        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#form").validate({
                errorClass: 'errors',
                rules: {

                    email: {
                        required: true,
                        email: true,

                    },
                    password: "required",

                },
                messages: {

                    email: {
                        required: "Please enter your email",

                    },
                    password: {
                        required: "Please enter your password",
                    }


                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>

</head>

<body>

    <?php
    echo $login_user;
    ?>

    <div id="page-content" bis_skin_checked="1">
        <!--Page Title-->
        <div class="page section-header text-center" bis_skin_checked="1">
            <div class="page-title" bis_skin_checked="1">
                <div class="wrapper" bis_skin_checked="1">
                    <h1 class="page-width">Login</h1>
                </div>
            </div>
        </div>
        <!--End Page Title-->

        <div class="container" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3" bis_skin_checked="1">
                    <div class="mb-4" bis_skin_checked="1">
                        <form method="post" action="#" id="form" accept-charset="UTF-8" class="contact-form">
                            <div class="row" bis_skin_checked="1">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12" bis_skin_checked="1">
                                    <span>
                                        <p class="invalid-user"><?php echo $loginerror; ?></p>
                                    </span>
                                    <div class="form-group" bis_skin_checked="1">
                                        <label for="CustomerEmail">Email</label>
                                        <input type="email" name="email" placeholder="" id="CustomerEmail" class="" autocorrect="off" autocapitalize="off" autofocus="">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label for="CustomerPassword">Password</label>
                                        <input type="password" value="" name="password" placeholder="" id="CustomerPassword" class="">
                                    </div>
                                </div>
                            </div>
                            <div class="row" bis_skin_checked="1">
                                <div class="text-center col-12 col-sm-12 col-md-12 col-lg-12" bis_skin_checked="1">
                                    <input type="submit" class="btn mb-3" name="login" value="Sign In">
                                    <p class="mb-4">

                                        <a href="register.php" id="customer_register_link">Create account</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>


<?php

include("footer.php");

?>