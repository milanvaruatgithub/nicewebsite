<?php
include("header.php");

require("connection_niceadmin.php");

$kg_get_product = "SELECT * FROM product ORDER BY p_id desc limit 4 ";
$kg_get_product_run = mysqli_query($kg_niceadmin_con, $kg_get_product);

$products = array();
while ($row_products = mysqli_fetch_assoc($kg_get_product_run)) {


    $products[] = $row_products;
}

?>
<html>

<head>
    <style>
        .table {
            width: 70%;
            align-items: center;

        }

        a:hover {
            color: whitesmoke;
        }
    </style>
</head>

<body>
    <div class="slideshow slideshow-wrapper pb-section sliderFull">
        <div class="home-slideshow">
            <div class="slide">
                <div class="blur-up lazyload bg-size">
                    <img class="blur-up lazyload bg-img" data-src="assets/images/slideshow-banners/home5-banner2.jpg" src="assets/images/slideshow-banners/home5-banner2.jpg" alt="Shop Our New Collection" title="Shop Our New Collection" />

                </div>
            </div>
        </div>
    </div>



    <div class="tab-slider-product section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="section-header text-center">
                        <h2 class="h2">Products</h2>

                    </div>

                    <div class="tabs-listing">
                        <div id="tab1" class="tab_content grid-products">

                            <div class="productSlider">
                                <?php foreach ($products as $product) : ?>
                                    <div class="col-12 item">
                                        <div class="product-image">
                                            <a href="">
                                                <?php
                                                $imageSql = "SELECT image_name FROM p_image WHERE p_id = " . $product["p_id"];
                                                $images = $kg_niceadmin_con->query($imageSql);
                                                if ($images->num_rows > 0) {
                                                    while ($image = $images->fetch_assoc()) {
                                                        $imagePath = '../NiceAdmin/images/' . $image["image_name"];
                                                        if (file_exists($imagePath)) {
                                                            echo "<img src='" . $imagePath . "' alt='Product Image' width='200' height='250'>&nbsp;";
                                                        } else {
                                                            echo "<p>Image not found: " . htmlspecialchars($image["image_name"]) . "</p>";
                                                        }
                                                    }
                                                } else {
                                                    echo "No image available";
                                                } ?>

                                            </a>

                                            <form class="variants add" action="" onclick="window.location.href=''" method="post">

                                                <button class="btn btn-addto-cart" type="button" tabindex="0">
                                                    <a href="view_product.php?p_id=<?php echo $product['p_id']; ?>">
                                                        Add To Cart
                                                    </a>

                                                </button>
                                            </form>
                                        </div>
                                        <ul class="swatches">
                                            <?php
                                            $imageSql = "SELECT image_name FROM p_image WHERE p_id = " . $product["p_id"];
                                            $images = $kg_niceadmin_con->query($imageSql);
                                            while ($image = $images->fetch_assoc()) {
                                                $imagePath = '../NiceAdmin/images/' . $image["image_name"];
                                                echo "<li class='swatch medium rounded'><img src='" . $imagePath . "' alt='Product Image' width='50' height='50'></li>";
                                            }
                                            ?>
                                        </ul>
                                        <br>

                                        <div class="product-details text-center">
                                            <div class="product-name">
                                                <span class="h3"><?php echo $product["p_name"]; ?></span>
                                            </div>
                                            <div class="product-price">
                                                <span class="warranty"> warranty : <?php echo $product["p_warranty"]; ?> year</span><br>
                                                <span class="price"> ₹ <?php echo $product["p_price"]; ?></span>
                                            </div>



                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>

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