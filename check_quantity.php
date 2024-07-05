<?php
require("connection_niceadmin.php");



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['p_id']) && isset($_POST['quantity'])) {
    $p_id = $_POST['p_id'];
    $quantity = $_POST['quantity'];


    $getquantity = "SELECT p_quantity FROM product WHERE p_id = '$p_id'";
    $getquantity_run = mysqli_query($kg_niceadmin_con, $getquantity);
    $row_quantity = mysqli_fetch_assoc($getquantity_run);

    if ($row_quantity && $quantity >= $row_quantity['p_quantity']) {

        echo "1";
        exit;
    } else {
        echo "2";
        exit;
    }
}
