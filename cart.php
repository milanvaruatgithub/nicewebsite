<?php
include('header.php');
require("connection_niceadmin.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $p_id = $_POST['p_id'];
        $p_quantity = $_POST['quantity'];
        require("connection_niceadmin.php");
        $query = "SELECT * FROM product WHERE p_id = '$p_id'";
        $result = mysqli_query($kg_niceadmin_con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$p_id])) {
                $_SESSION['cart'][$p_id]['quantity'] += $p_quantity;
            } else {
                $_SESSION['cart'][$p_id] = [
                    'quantity' => $p_quantity,
                ];
            }
        }
    }

    if (isset($_POST['remove'])) {
        $remove_id = $_POST['remove_id'];

        if (isset($_SESSION['cart'][$remove_id])) {
            unset($_SESSION['cart'][$remove_id]);
        }
    }
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}




foreach ($_SESSION['cart'] as $p_id => $product) {

    $quantity = $product['quantity'];
}

echo "<pre>";
print_r($_SESSION['cart']);
echo "</pre>";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Your existing CSS styles */
    </style>
    <script>
        $(document).ready(function() {
            $('.qtyBtn').on('click', function(e) {
                e.preventDefault();
                var operation = $(this).data('operation');
                var cartId = $(this).data('cart-id');
                var currentQuantity = parseInt($('#qty-' + cartId).val());

                var newQuantity = currentQuantity;
                var maxQuantity = parseInt($(this).data('max-quantity'));

                if (operation === 'minus') {
                    newQuantity = Math.max(currentQuantity - 1, 1); // Ensure quantity does not go below 1
                } else if (operation === 'plus') {
                    if (currentQuantity < maxQuantity) {
                        newQuantity = currentQuantity + 1;
                    } else {
                        return; // Do nothing if max quantity reached
                    }
                }

                updateCartQuantity(cartId, newQuantity);
            });

            function updateCartQuantity(cartId, newQuantity) {
                $.ajax({
                    url: 'update_cart.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'update_quantity',
                        cart_id: cartId,
                        new_quantity: newQuantity
                    },
                    success: function(response) {
                        if (response.error) {
                            // Display error message above the cart items
                            $('#error_message').text(response.error);
                            $('#error_message').show();
                        } else {
                            // Clear or hide the error message if no error
                            $('#error_message').text('');
                            $('#error_message').hide();

                            // Update total amount, quantity, etc. on your cart page
                            updateCartTotals(response.total_amount, response.total_quantity);

                            // Update quantity input field
                            $('#qty-' + cartId).val(newQuantity);
                            // Update total price for this item
                            $('#totalPrice-' + cartId).text('₹ ' + response.total_price);

                            // Check if max quantity is reached
                            if (response.max_quantity_reached) {
                                // Disable the plus button for this item
                                disablePlusButton(cartId);
                            } else {
                                // Enable the plus button if it was disabled
                                enablePlusButton(cartId);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + ' - ' + error);
                    }
                });
            }

            function updateCartTotals(totalAmount, totalQuantity) {
                // Update the UI with new totals
                $('#total').text('₹ ' + totalAmount.toFixed(2));
                $('#total_quantity').text(totalQuantity);
            }

            function disablePlusButton(cartId) {
                // Disable the plus button for this cart item
                $('#plus_button_' + cartId).prop('disabled', true);
            }

            function enablePlusButton(cartId) {
                // Enable the plus button for this cart item
                $('#plus_button_' + cartId).prop('disabled', false);
            }
        });
    </script>
</head>

<body>
    <?php
    include('header.php');
    require("connection_niceadmin.php");

    if (empty($_SESSION['cart'])) {
        echo "<p class='text-center' style='font-size: 25px;'>Your cart is empty.</p>";
    } else {
    ?>
        <div id="page-content">
            <!-- Page Title -->
            <div class="page section-header text-center">
                <div class="page-title">
                    <div class="wrapper">
                        <h1 class="page-width">Your cart</h1>
                    </div>
                </div>
            </div>
            <!-- End Page Title -->

            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-8 col-lg-8 main-col">
                        <form action="" method="post" class="cart style2">
                            <table>
                                <thead class="cart__row cart__header">
                                    <tr>
                                        <th colspan="2" class="text-center">Product</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Total</th>
                                        <th class="action">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    $total_quantity = 0;
                                    foreach ($_SESSION['cart'] as $p_id => $product) {
                                        $quantity = $product['quantity'];

                                        $productaql = "SELECT p_name,p_price FROM product where p_id='$p_id'";
                                        $result = mysqli_query($kg_niceadmin_con, $productaql);
                                        $product_row = mysqli_fetch_assoc($result);

                                        $price = $product_row['p_price'];
                                        $totalPrice = $price * $quantity;

                                        $total += $totalPrice;
                                        $total_quantity += $quantity;

                                        $imageSql = "SELECT image_name FROM p_image WHERE p_id = '$p_id'";
                                        $images = $kg_niceadmin_con->query($imageSql);
                                        $imagePath = '';
                                        if ($images->num_rows > 0) {
                                            $image = $images->fetch_assoc();
                                            $imagePath = '../NiceAdmin/images/' . $image["image_name"];
                                        }
                                    ?>
                                        <tr class="cart__row border-bottom line1 cart-flex border-top">
                                            <td class="cart__image-wrapper cart-flex-item">
                                                <?php if ($imagePath != '') : ?>
                                                    <img class="cart__image" src="<?php echo $imagePath; ?>" alt="Product Image" width="150" height="150">
                                                <?php endif; ?>
                                            </td>
                                            <td class="cart__meta small--text-left cart-flex-item">
                                                <div class="list-view-item__title">
                                                    <a><?php echo $product_row['p_name']; ?></a>
                                                </div>
                                            </td>
                                            <td class="cart__price-wrapper cart-flex-item">
                                                <span class="money"><?php echo $product_row['p_price']; ?></span>
                                            </td>
                                            <td class="cart__update-wrapper cart-flex-item text-right">
                                                <div class="cart__qty text-center">
                                                    <div class="qtyField">
                                                        <a class="qtyBtn minus" href="javascript:void(0);" data-cart-id="<?php echo $p_id; ?>" data-operation="minus"><i class="icon icon-minus"></i></a>
                                                        <input class="cart__qty-input qty" type="text" name="updates[]" id="qty-<?php echo $p_id; ?>" value="<?php echo $quantity; ?>" pattern="[0-9]*">
                                                        <a class="qtyBtn plus" href="javascript:void(0);" data-cart-id="<?php echo $p_id; ?>" data-operation="plus" data-max-quantity="<?php echo $product_row['p_quantity']; ?>"><i class="icon icon-plus"></i></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-right small--hide cart-price">
                                                <div><span class="money" id="totalPrice-<?php echo $p_id; ?>">₹ <?php echo $totalPrice; ?></span></div>
                                            </td>
                                            <td class="text-center small--hide">
                                                <form action="#" method="post">
                                                    <input type="hidden" name="remove_id" value="<?php echo $p_id; ?>">
                                                    <button type="submit" name="remove" class="btn btn--secondary cart__remove" title="Remove item"><i class="icon icon anm anm-times-l"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-left"><a href="index.php" class="btn--link cart-continue"><i class="icon icon-arrow-circle-left"></i> Continue shopping</a></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 cart__footer">
                        <div class="solid-border">
                            <div class="row">
                                <span class="col-12 col-sm-6 cart__subtotal-title"><strong>Subtotal</strong></span>
                                <span class="col-12 col-sm-6 cart__subtotal-title cart__subtotal text-right"><span id="total">₹ <?php echo $total; ?></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 cart__footer">
                        <div class="solid-border">
                            <div class="row">
                                <span class="col-12 col-sm-6 cart__subtotal-title"><strong>Total quantities</strong></span>
                                <span class="col-12 col-sm-6 cart__subtotal-title cart__subtotal text-right"><span id="total_quantity"><?php echo $total_quantity; ?></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    include("footer.php");
    ?>
</body>

</html>
