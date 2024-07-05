<?php

if (!isset($_GET['p_id']) || !is_numeric($_GET['p_id'])) {
    header("location: index.php");
    exit;
}

require("connection_niceadmin.php");

$p_id = $_GET['p_id'];


$kg_get_product = "SELECT * FROM product WHERE p_id = '$p_id'";
$kg_get_product_run = mysqli_query($kg_niceadmin_con, $kg_get_product);
$row_products = mysqli_fetch_assoc($kg_get_product_run);

var_dump($kg_get_product);


if (!$row_products) {
    header("location: index.php");
    exit;
}

$quantity = $row_products['p_quantity'];

include("header.php");
?>

<html>

<head>
    <style>

    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {



            $('.qtyBtn.minus').click(function(e) {
                e.preventDefault();
                var currentValue = parseInt($('#Quantity').val());
                if (currentValue > 1) {
                    $('#Quantity').val(currentValue - 1);
                    $('#errorMessage').text('');
                }
            });

            $('.qtyBtn.plus').click(function(e) {
                e.preventDefault();
                var currentValue = parseInt($('#Quantity').val());
                if (currentValue < <?php echo $quantity; ?>) {
                    $('#Quantity').val(currentValue + 1);
                    $('#errorMessage').text('');
                } else {
                    $('#errorMessage').text('Quantity is not available');
                }
            });
        });
    </script>

</head>

<body>
    <div id="ProductSection-product-template" class="product-template__container prstyle1 container">


        <div class="product-single">
            <div class="row">

                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="product-details-img">
                        <div class="product-thumb">
                            <div id="gallery" class="product-dec-slider-2 product-tab-left slick-initialized slick-slider slick-vertical">
                                <div class="slick-list draggable" style="height: 489.062px;">
                                    <div class="slick-slide slick-cloned" data-slick-index="-5" aria-hidden="true" tabindex="-1" style="width: 71px; height: 200px;;">

                                        <?php
                                        $imageSql = "SELECT image_name FROM p_image WHERE p_id = " . $p_id;
                                        $images = $kg_niceadmin_con->query($imageSql);
                                        while ($image = $images->fetch_assoc()) {
                                            $imagePath = '../NiceAdmin/images/' . $image["image_name"];
                                            echo "<img src='" . $imagePath . "' alt='Product Image'>";
                                        }
                                        ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="zoompro-wrap product-zoom-right pl-20">
                            <div class="zoompro-span">
                                <?php
                                $imageSql = "SELECT image_name FROM p_image WHERE p_id = " . $p_id . " LIMIT 1"; // Limit to one image
                                $result = $kg_niceadmin_con->query($imageSql);

                                if ($result && $result->num_rows > 0) {
                                    $image = $result->fetch_assoc();
                                    $imagePath = '../NiceAdmin/images/' . $image["image_name"];
                                    echo "<img src='" . $imagePath . "' alt='Product Image'>";
                                } else {

                                    echo "<p>No image found</p>";
                                }
                                ?>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="product-single__meta">

                        <div>
                            <h1 class="product-title"><?php echo $row_products['p_name']; ?></h1>
                        </div>

                        <div class="prInfoRow">
                            <div class="product-stock"> <span class="instock ">In Stock</span><span class="outstock hide">Unavailable</span></div>
                            <div class="product-sku">SKU: <?php echo $row_products['p_sku']; ?></div>

                        </div>
                        <p class="product-single__price product-single__price-product-template">

                            <span class="product-price__price product-price__price-product-template product-price__sale product-price__sale--single">
                                <span id="ProductPrice-product-template"><span class="money">₹ <?php echo $row_products['p_price'] ?></span></span>

                            </span>
                            <span class="discount-badge"> <span class="devider">|</span>&nbsp;

                            </span>
                        </p>

                    </div>


                    <form method="post" action="cart.php" class="cart">
                        <input type="hidden" name="p_id" value="<?php echo $row_products['p_id']; ?>">
                        <div class="product-action clearfix">
                            <div class="product-form__item--quantity">
                                <div class="wrapQtyBtn">
                                    <div class="qtyField">
                                        <a class="qtyBtn minus" href="#"><i class="fa anm anm-minus-r" aria-hidden="true"></i></a>
                                        <input type="text" id="Quantity" name="quantity" value="1" class="product-form__input qty">
                                        <a class="qtyBtn plus" href="#"><i class="fa anm anm-plus-r" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="product-form__item--submit">
                                <button type="submit" name="add" id="add" class="btn product-form__cart-submit">
                                    <span>Add to cart</span>
                                </button>
                            </div>
                            <p id="freeShipMsg" class="freeShipMsg" data-price="199"><i class="fa fa-truck" aria-hidden="true"></i> GETTING CLOSER! ONLY <b class="freeShip"><span class="money" data-currency-usd="$199.00" data-currency="USD"><?php echo $row_products['p_price']; ?></span></b> AWAY FROM <b>FREE SHIPPING!</b></p>
                            <div id="errorMessage" style="color: red;"></div>
                        </div>
                    </form>



                </div>
            </div>
        </div>


    </div>

</body>

</html>

<?php include("footer.php"); ?>