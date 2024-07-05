<?php
session_start();
require("connection_niceadmin.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
        $cart_id = $_POST['cart_id'];
        $new_quantity = $_POST['new_quantity'];

        // Retrieve available quantity of the product from database
        $available_quantity = getAvailableQuantity($cart_id);

        // Update session cart if new quantity is valid
        if ($new_quantity >= 1 && $new_quantity <= $available_quantity) {
            if (isset($_SESSION['cart'][$cart_id])) {
                $_SESSION['cart'][$cart_id]['quantity'] = $new_quantity;
            }

            // Calculate total amount, quantity, and total product price
            $response = [
                'total_amount' => calculateTotalAmount($_SESSION['cart']),
                'total_quantity' => calculateTotalQuantity($_SESSION['cart']),
                'total_price' => calculateTotalPrice($cart_id, $new_quantity),
                'max_quantity_reached' => false // Include max_quantity_reached flag
            ];

            // Check if max quantity is reached
            if ($new_quantity >= $available_quantity) {
                $response['max_quantity_reached'] = true;
            }

            // Return JSON response
            echo json_encode($response);
        } else {
            // Return an error response if new quantity is invalid
            $response = [
                'error' => 'Invalid quantity. Please select a quantity between 1 and ' . $available_quantity,
                'max_quantity_reached' => true
            ];
            echo json_encode($response);
        }
    }
}

function calculateTotalAmount($cart)
{
    $total = 0;
    foreach ($cart as $p_id => $product) {
        require("connection_niceadmin.php");
        $product_price_query = mysqli_query($kg_niceadmin_con, "SELECT * FROM product WHERE p_id ='$p_id' ");
        $product_price_row = mysqli_fetch_assoc($product_price_query);

        $total += $product_price_row['p_price'] * $product['quantity'];
    }
    return $total;
}

function calculateTotalQuantity($cart)
{
    $total_quantity = 0;
    foreach ($cart as $product) {
        $total_quantity += $product['quantity'];
    }
    return $total_quantity;
}

function calculateTotalPrice($cart_id, $new_quantity)
{
    require("connection_niceadmin.php");
    $get_price_query = "SELECT * FROM product WHERE p_id='$cart_id'";
    $get_price_result = mysqli_query($kg_niceadmin_con, $get_price_query);
    $row = mysqli_fetch_assoc($get_price_result);

    $totalPrice = $row['p_price'] * $new_quantity;

    return $totalPrice;
}

function getAvailableQuantity($cart_id)
{
    require("connection_niceadmin.php");
    $get_quantity_query = "SELECT p_quantity FROM product WHERE p_id='$cart_id'";
    $get_quantity_result = mysqli_query($kg_niceadmin_con, $get_quantity_query);
    $row = mysqli_fetch_assoc($get_quantity_result);

    return $row['p_quantity'];
}
?>
