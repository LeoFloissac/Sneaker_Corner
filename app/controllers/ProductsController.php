<?php

class ProductsController {
    private $pdo;
    private $mysqliConn;

    public function __construct($mysqliConn = null) {
        // On garde la connexion mysqli si fournie pour compatibilité
        $this->mysqliConn = $mysqliConn;

        // Charger les paramètres de config pour créer un PDO (si possible)
        if (file_exists(__DIR__ . '/../../config/config.php')) {
            require_once __DIR__ . '/../../config/config.php';
            // config.php définit $servername, $name, $password, $dbname
        }

        try {
            if (isset($servername) && isset($name)) {
                $dsn = "mysql:host={$servername};dbname={$dbname};charset=utf8mb4";
                $this->pdo = new PDO($dsn, $name, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            }
        } catch (Exception $e) {
            // Si PDO non disponible, on garde null et laissera le code appeler la BD via mysqli si nécessaire
            $this->pdo = null;
        }
    }

    /**
     * Récupère la liste de TOUTES les variantes de produits.
     * Chaque variante est affichée séparément avec son nom, couleur, prix et image.
     * Retourne un tableau associatif prêt à l'affichage.
     */
    public function index() {
        $products = [];

        $sql = "SELECT
                    pv.variant_id,
                    pv.variant_name,
                    pv.price,
                    p.product_id,
                    p.name AS product_name,
                    p.brand,
                    p.model,
                    c.name AS color_name,
                    (SELECT vi.image_url 
                        FROM variant_images vi
                        WHERE vi.variant_id = pv.variant_id
                        ORDER BY vi.is_primary DESC, vi.display_order ASC
                        LIMIT 1) AS image_url
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.product_id
                JOIN colors c ON pv.color_id = c.color_id
                WHERE pv.is_available = 1 AND p.is_available = 1
                ORDER BY p.product_id DESC, pv.variant_id ASC";

        // Prefer PDO if disponible
        if ($this->pdo) {
            $stmt = $this->pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Fall back sur mysqli si fourni
            $rows = [];
            if ($this->mysqliConn) {
                $res = $this->mysqliConn->query($sql);
                if ($res) {
                    while ($r = $res->fetch_assoc()) {
                        $rows[] = $r;
                    }
                    $res->free();
                }
            }
        }

        foreach ($rows as $r) {
            // Titre : nom de la variante ou nom du produit
            $title = !empty($r['variant_name']) ? $r['variant_name'] : ($r['product_name'] ?? '');

            // Sous-titre : marque - modèle - couleur
            $subtitleParts = [];
            if (!empty($r['brand'])) $subtitleParts[] = $r['brand'];
            if (!empty($r['model'])) $subtitleParts[] = $r['model'];
            if (!empty($r['color_name'])) $subtitleParts[] = $r['color_name'];
            $subtitle = implode(' - ', $subtitleParts);

            // Prix de la variante
            $price = ($r['price'] !== null) ? number_format((float)$r['price'], 2) . ' €' : 'N/A';

            // Image de la variante
            $img = '/sneaker_corner/images/placeholder.png';
            if (!empty($r['image_url'])) {
                $img = '/sneaker_corner/' . ltrim($r['image_url'], '/');
            }

            $products[] = [
                'variant_id' => $r['variant_id'],
                'product_id' => $r['product_id'],
                'title' => $title,
                'subtitle' => $subtitle,
                'price' => $price,
                'img' => $img,
            ];
        }

        return $products;
    }
}
