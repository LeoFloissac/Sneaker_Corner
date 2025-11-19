<?php 
    $extraCSS = ["/my_website/css/products.css","/my_website/css/product.css"];
    include 'partials/header.php'; 
?>

<main>
    <h1>Products Page</h1>
    <div id="product-selection">
        <?php 
            // connexion (utilise la même config que seed)
            require_once __DIR__ . '/../config/config.php';

            // récupérer tous les produits
            $res = $conn->query("SELECT id, title, description, price FROM products ORDER BY id DESC");
            if ($res && $res->num_rows > 0) {
                // préparer requête pour image primaire
                $stmtImg = $conn->prepare("SELECT image FROM product_images WHERE product_id = ? ORDER BY is_primary DESC LIMIT 1");
                while ($prod = $res->fetch_assoc()) {
                    // valeurs que ton partial attend
                    $title = $prod['title'];
                    $subtitle = $prod['description'] ?? '';
                    $price = number_format((float)$prod['price'], 2) . ' €';

                    // par défaut une image placeholder
                    $img = '/my_website/images/placeholder.png';

                    // chercher image primaire
                    if ($stmtImg) {
                        $stmtImg->bind_param("i", $prod['id']);
                        $stmtImg->execute();
                        $stmtImg->bind_result($imgDb);
                        if ($stmtImg->fetch()) {
                            // product_images stocke "images/nom.jpg" -> on préfixe le dossier du site
                            $img = '/my_website/' . ltrim($imgDb, '/');
                        }
                        // repositionner le curseur pour la prochaine itération
                        $stmtImg->free_result();
                    }

                    include 'partials/product.php';
                }
                if ($stmtImg) $stmtImg->close();
            } else {
                echo "<p>Aucun produit trouvé.</p>";
            }
            $res->free();
            // ne pas fermer $conn si header/footer s'en servent ; sinon $conn->close();
        ?>
    </div>
</main>

<?php include 'partials/footer.php'; ?>
