
<?php
function updateSessionCart($p_id, $kg_niceadmin_con)
    {
        $query = "SELECT p_sku, p_price, p_quantity FROM product WHERE p_id = '$p_id'";
        $result = mysqli_query($kg_niceadmin_con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);

            if (isset($_SESSION['cart'][$p_id])) {
                $_SESSION['cart'][$p_id]['name'] = $product['p_sku'];
                $_SESSION['cart'][$p_id]['price'] = $product['p_price'];

                // Check if requested quantity exceeds available quantity
                $requestedQuantity = $_SESSION['cart'][$p_id]['quantity'];
                $availableQuantity = $product['p_quantity'];

                if ($requestedQuantity > $availableQuantity) {
                    // Handle error, e.g., set an error message or prevent adding to cart
                    // For example, you can redirect back with an error message
                    $_SESSION['cart'][$p_id]['quantity'] = $availableQuantity; // Set to available quantity
                    $_SESSION['error_message'] = "Requested quantity exceeds available stock!";
                    header("Location: index.php"); // Redirect to prevent resubmission
                    exit();
                }
            }
        }
    }

    if (!empty($_SESSION['error_message'])) {
        echo "<p class='error-message'>" . $_SESSION['error_message'] . "</p>";
        unset($_SESSION['error_message']);
    }