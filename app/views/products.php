<?php 
    $extraCSS = ["/sneaker_corner/css/products.css","/sneaker_corner/css/product.css"];
    include 'partials/header.php'; 
?>

<main>
    <h1>Products Page</h1>
    <div id="product-selection">
        <?php 
            // utiliser le contrôleur pour respecter MVC
            require_once __DIR__ . '/../../config/config.php';
            require_once __DIR__ . '/../controllers/ProductsController.php';

            $productsController = new ProductsController($conn ?? null);
            $products = $productsController->index();

            if (!empty($products)) {
                foreach ($products as $prod) {
                    // valeurs que le partial attend
                    $title = $prod['title'];
                    $subtitle = $prod['subtitle'];
                    $price = $prod['price'];
                    $img = $prod['img'];

                    include 'partials/product.php';
                }
            } else {
                echo "<p>Aucun produit trouvé.</p>";
            }
        ?>
    </div>
</main>

<?php include 'partials/footer.php'; ?>
