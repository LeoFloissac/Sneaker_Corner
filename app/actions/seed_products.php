<?php
require __DIR__ . '/../../config/config.php';

// Exécutable CLI ou via navigateur avec ?run=1
if (PHP_SAPI !== 'cli') {
    if (!isset($_GET['run']) || $_GET['run'] !== '1') {
        header('Content-Type: text/plain; charset=utf-8');
        echo "Pour exécuter depuis le navigateur : add ?run=1\n";
        echo "Ex: http://localhost:8888/my_website/app/actions/seed_products.php?run=1\n";
        exit;
    }
    header('Content-Type: text/plain; charset=utf-8');
}

// Vérifier si table products vide
$res = $conn->query("SELECT COUNT(*) AS c FROM products");
if (!$res) {
    echo "Erreur lecture products: " . $conn->error . "\n";
    $conn->close();
    exit;
}
$row = $res->fetch_assoc();
if ((int)$row['c'] > 0) {
    echo "Table 'products' contient déjà " . (int)$row['c'] . " produit(s). Abandon.\n";
    $conn->close();
    exit;
}

// dossier où tu dois placer les fichiers réels (si besoin)
$imagesDir = __DIR__ . '/../../images/';

// données : chaque image peut porter la couleur associée (doit appartenir à product.colors)
$products = [
    [
        'title' => 'Nike P-6000',
        'category' => 'Lifestyle',
        'price' => 160.00,
        'images' => [
            ['file' => 'nike_p6000_silver_1.jpg', 'color' => 'Silver'],
            ['file' => 'nike_p6000_silver_2.jpg', 'color' => 'Silver'],
            ['file' => 'nike_p6000_silver_3.jpg', 'color' => 'Silver'],
            ['file' => 'nike_p6000_black_1.jpg', 'color' => 'Black'],
            ['file' => 'nike_p6000_black_2.jpg', 'color' => 'Black'],
            ['file' => 'nike_p6000_black_3.jpg', 'color' => 'Black'],
        ],
        'description' => "retro and comfortable sneaker",
        'colors' => ['Silver', 'Black'],
        'sizes'  => ['38','39','40','41','42','43','44','45']
    ],
    [
        'title' => 'Air Zoom Pegasus',
        'category' => 'Running',
        'price' => 119.99,
        'images' => [
            ['file' => 'pegasus_blue_1.jpg', 'color' => 'Blue'],
            ['file' => 'pegasus_blue_2.jpg', 'color' => 'Blue'],
            ['file' => 'pegasus_blue_3.jpg', 'color' => 'Blue'],
            ['file' => 'pegasus_gray_1.jpg', 'color' => 'Gray'],
            ['file' => 'pegasus_gray_2.jpg', 'color' => 'Gray'],
            ['file' => 'pegasus_gray_3.jpg', 'color' => 'Gray'],
        ],
        'description' => "Light running shoes",
        'colors' => ['Blue','Gray'],
        'sizes'  => ['41','42','43','44','45']
    ],
    [
        'title' => 'Nike P-6000',
        'category' => 'Lifestyle',
        'price' => 160.00,
        'images' => [
            ['file' => 'nike_p6000_silver_1.jpg', 'color' => 'Silver'],
            ['file' => 'nike_p6000_silver_2.jpg', 'color' => 'Silver'],
            ['file' => 'nike_p6000_silver_3.jpg', 'color' => 'Silver'],
            ['file' => 'nike_p6000_black_1.jpg', 'color' => 'Black'],
            ['file' => 'nike_p6000_black_2.jpg', 'color' => 'Black'],
            ['file' => 'nike_p6000_black_3.jpg', 'color' => 'Black'],
        ],
        'description' => "retro and comfortable sneaker",
        'colors' => ['Silver', 'Black'],
        'sizes'  => ['38','39','40','41','42','43','44','45']
    ],
    [
        'title' => 'Air Zoom Pegasus',
        'category' => 'Running',
        'price' => 119.99,
        'images' => [
            ['file' => 'pegasus_blue_1.jpg', 'color' => 'Blue'],
            ['file' => 'pegasus_blue_2.jpg', 'color' => 'Blue'],
            ['file' => 'pegasus_blue_3.jpg', 'color' => 'Blue'],
            ['file' => 'pegasus_gray_1.jpg', 'color' => 'Gray'],
            ['file' => 'pegasus_gray_2.jpg', 'color' => 'Gray'],
            ['file' => 'pegasus_gray_3.jpg', 'color' => 'Gray'],
        ],
        'description' => "Light running shoes",
        'colors' => ['Blue','Gray'],
        'sizes'  => ['41','42','43','44','45']
    ]
];

// Prépare l'insertion dans products (avec colors et sizes en JSON)
$stmtProd = $conn->prepare("INSERT INTO products (title, category, price, description, colors, sizes) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmtProd) {
    echo "Erreur prepare products: " . $conn->error . "\n";
    $conn->close();
    exit;
}

// Prépare l'insertion dans product_images (ajout de la colonne color)
$stmtImg = $conn->prepare("INSERT INTO product_images (product_id, image, is_primary, color) VALUES (?, ?, ?, ?)");
if (!$stmtImg) {
    echo "Erreur prepare product_images: " . $conn->error . "\n";
    $stmtProd->close();
    $conn->close();
    exit;
}

$conn->begin_transaction();
$inserted = 0;
$insertedImages = 0;

foreach ($products as $p) {
    $title = $p['title'];
    $category = $p['category'];
    $price = $p['price'];
    $images = $p['images'] ?? [];
    $description = $p['description'] ?? '';
    $colors = $p['colors'] ?? [];
    $sizes = $p['sizes'] ?? [];

    // encode JSON pour couleurs et tailles
    $colors_json = json_encode($colors, JSON_UNESCAPED_UNICODE);
    $sizes_json  = json_encode($sizes, JSON_UNESCAPED_UNICODE);

    $stmtProd->bind_param("ssdsss", $title, $category, $price, $description, $colors_json, $sizes_json);
    if (!$stmtProd->execute()) {
        echo "Erreur insert produit $title : " . $stmtProd->error . "\n";
        $conn->rollback();
        $stmtProd->close();
        $stmtImg->close();
        $conn->close();
        exit;
    }
    $productId = $stmtProd->insert_id;
    $inserted++;

    $isPrimary = 1;
    foreach ($images as $imgEntry) {
        // supporte soit string (ancien format) soit tableau ['file'=>..., 'color'=>...]
        if (is_string($imgEntry)) {
            $fname = $imgEntry;
            $imgColor = null;
        } elseif (is_array($imgEntry)) {
            $fname = $imgEntry['file'] ?? null;
            $imgColor = $imgEntry['color'] ?? null;
        } else {
            continue;
        }

        if (!$fname) continue;

        // Vérifier que la couleur (si fournie) appartient bien aux couleurs du produit
        if ($imgColor !== null && !in_array($imgColor, $colors, true)) {
            // couleur non valide -> null ou tu peux remplacer par $colors[0]
            $imgColor = null;
        }

        $fullPath = $imagesDir . $fname;
        if (!file_exists($fullPath)) {
            echo "Fichier manquant: $fullPath (skip)\n";
            continue;
        }
        $relPath = 'images/' . $fname;
        $stmtImg->bind_param("isis", $productId, $relPath, $isPrimary, $imgColor);
        if ($stmtImg->execute()) {
            $insertedImages++;
        } else {
            echo "Erreur insert image $relPath : " . $stmtImg->error . "\n";
        }
        $isPrimary = 0;
    }
}

$conn->commit();
$stmtImg->close();
$stmtProd->close();
$conn->close();

echo "Terminé. Produits insérés: $inserted. Images insérées: $insertedImages\n";
?>