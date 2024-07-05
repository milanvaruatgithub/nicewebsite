<?php
require("connection.php");


if (isset($_POST['email'])) {
    $email = $_POST['email'];
}

$eque = "SELECT * FROM register_user WHERE user_email='$email'";

$result = $kg_con->query($eque);

if ($result->num_rows > 0) {
    echo 'false';
    exit;
} else {
    echo 'true';
    exit;
}
