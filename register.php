<?php


require('connection.php');

$emailerror = '';

$fname = isset($_POST['fname']) ? $_POST['fname'] : "";
$lname = isset($_POST['lname']) ? $_POST['lname'] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";

if (isset($_POST['create'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // $eque = "SELECT * FROM register_user WHERE email='$email'";

    // $result = $con->query($eque);

    // if ($result->num_rows > 0) {
    //     $emailerror = "this email id already exist";
    // }

    $que = "INSERT INTO register_user(user_first_name,user_last_name,user_email,user_password) VALUES('$fname','$lname','$email','$password')";

    if ($kg_con->query($que) == TRUE) {
        header("location:login.php");
        exit();
    }
}
include('header.php');

?>

<html>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <style>
        .errors {
            color: red;

        }
    </style>
    <script>
        $(document).ready(function() {
            $("#form").validate({
                errorClass: 'errors',
                rules: {
                    fname: "required",
                    lname: "required",
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "chk_user_email.php",
                            type: "post",

                        }
                    },
                    password: "required",

                },
                messages: {
                    fname: {
                        required: "Please enter your first name",
                    },
                    lname: {
                        required: "Please enter your last name",
                    },
                    email: {
                        required: "Please enter your email",
                        remote: "This email id already exist"
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
    <div id="page-content">
        <!--Page Title-->
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper">
                    <h1 class="page-width">Create an Account</h1>
                </div>
            </div>
        </div>
        <!--End Page Title-->

        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3">
                    <div class="mb-4">
                        <form method="post" action="#" id="form" accept-charset="UTF-8" class="contact-form">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="FirstName">First Name</label>
                                        <input type="text" name="fname" placeholder="" id="FirstName" autofocus="">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="LastName">Last Name</label>
                                        <input type="text" name="lname" placeholder="" id="LastName">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="CustomerEmail">Email</label>
                                        <input type="email" name="email" placeholder="" id="CustomerEmail" class="" autocorrect="off" autocapitalize="off" autofocus="">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="CustomerPassword">Password</label>
                                        <input type="password" value="" name="password" placeholder="" id="CustomerPassword" class="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="submit" class="btn mb-3" name="create" value="Create">
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