<?php

class ProductVariant {
    private $pdo;
    private $mysqliConn;
    
    public function __construct($mysqliConn = null) {
        $this->mysqliConn = $mysqliConn;

        if (file_exists(__DIR__ . '/../../config/config.php')) {
            require_once __DIR__ . '/../../config/config.php';
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
            $this->pdo = null;
        }
    }

    /**
     * Execute a query and return results
     */
    private function executeQuery($sql, $params = []) {
        $rows = [];
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($this->mysqliConn) {
            if (empty($params)) {
                $res = $this->mysqliConn->query($sql);
            } else {
                $stmt = $this->mysqliConn->prepare($sql);
                $types = str_repeat('s', count($params));
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $res = $stmt->get_result();
            }
            if ($res) {
                while ($r = $res->fetch_assoc()) {
                    $rows[] = $r;
                }
                if (method_exists($res, 'free')) $res->free();
            }
        }
        
        return $rows;
    }

    /**
     * Execute a query and return single result
     */
    private function executeQuerySingle($sql, $params = []) {
        $rows = $this->executeQuery($sql, $params);
        return !empty($rows) ? $rows[0] : null;
    }
    
    /**
     * Find all variants
     */
    public function findAll() {
        $sql = "SELECT pv.*, p.name as product_name, p.brand, c.name as color_name
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.product_id
                JOIN colors c ON pv.color_id = c.color_id
                ORDER BY pv.created_at DESC";
        return $this->executeQuery($sql);
    }
    
    /**
     * Find variant by ID
     */
    public function findById($id) {
        $sql = "SELECT
                    pv.variant_id,
                    pv.variant_name,
                    pv.price,
                    pv.product_id,
                    pv.color_id,
                    p.name AS product_name,
                    p.brand,
                    p.model,
                    p.slug,
                    c.name AS color_name,
                    c.slug AS color_slug
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.product_id
                JOIN colors c ON pv.color_id = c.color_id
                WHERE pv.variant_id = ?";
        return $this->executeQuerySingle($sql, [$id]);
    }
    
    /**
     * Find variants by product ID
     */
    public function findByProductId($productId) {
        $sql = "SELECT pv.*, c.name as color_name, c.slug as color_slug
                FROM product_variants pv
                JOIN colors c ON pv.color_id = c.color_id
                WHERE pv.product_id = ?
                ORDER BY pv.variant_name ASC";
        return $this->executeQuery($sql, [$productId]);
    }
    
    /**
     * Find available variants
     */
    public function findAvailable() {
        $sql = "SELECT pv.*, p.name as product_name, p.brand, c.name as color_name
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.product_id
                JOIN colors c ON pv.color_id = c.color_id
                WHERE pv.is_available = 1 AND p.is_available = 1
                ORDER BY pv.created_at DESC";
        return $this->executeQuery($sql);
    }

    /**
     * Find variant ID by product slug and color slug
     */
    public function findIdBySlugs($productSlug, $colorSlug) {
        $sql = "SELECT pv.variant_id 
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.product_id
                JOIN colors c ON pv.color_id = c.color_id
                WHERE p.slug = ? AND c.slug = ? AND pv.is_available = 1
                LIMIT 1";
        
        $result = $this->executeQuerySingle($sql, [$productSlug, $colorSlug]);
        return $result ? (int)$result['variant_id'] : null;
    }

    /**
     * Find all variants with filters (for product listing)
     */
    public function findAllWithFilters($filters = []) {
        $params = [];
        $whereConditions = ["pv.is_available = 1", "p.is_available = 1"];

        if (!empty($filters['brand'])) {
            $whereConditions[] = "p.brand = ?";
            $params[] = $filters['brand'];
        }

        if (!empty($filters['color'])) {
            $whereConditions[] = "c.slug = ?";
            $params[] = $filters['color'];
        }

        if (!empty($filters['size'])) {
            $whereConditions[] = "EXISTS (SELECT 1 FROM variant_sizes vs WHERE vs.variant_id = pv.variant_id AND vs.size = ? AND vs.stock_quantity > 0)";
            $params[] = $filters['size'];
        }

        $whereClause = implode(" AND ", $whereConditions);

        $sql = "SELECT
                    pv.variant_id,
                    pv.variant_name,
                    pv.price,
                    p.product_id,
                    p.name AS product_name,
                    p.brand,
                    p.model,
                    p.slug AS product_slug,
                    c.name AS color_name,
                    c.slug AS color_slug,
                    (SELECT vi.image_url 
                        FROM variant_images vi
                        WHERE vi.variant_id = pv.variant_id
                        ORDER BY vi.is_primary DESC, vi.display_order ASC
                        LIMIT 1) AS image_url
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.product_id
                JOIN colors c ON pv.color_id = c.color_id
                WHERE {$whereClause}
                ORDER BY p.product_id DESC, pv.variant_id ASC";

        return $this->executeQuery($sql, $params);
    }

    /**
     * Search variants by query
     */
    public function search($query, $limit = 8) {
        $searchTerm = '%' . $query . '%';
        $exactMatch = $query . '%';

        $sql = "SELECT
                    pv.variant_id,
                    pv.variant_name,
                    pv.price,
                    p.product_id,
                    p.name AS product_name,
                    p.brand,
                    p.model,
                    p.slug AS product_slug,
                    c.name AS color_name,
                    c.slug AS color_slug,
                    (SELECT vi.image_url 
                        FROM variant_images vi
                        WHERE vi.variant_id = pv.variant_id
                        ORDER BY vi.is_primary DESC, vi.display_order ASC
                        LIMIT 1) AS image_url
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.product_id
                JOIN colors c ON pv.color_id = c.color_id
                WHERE pv.is_available = 1 
                  AND p.is_available = 1
                  AND (
                      pv.variant_name LIKE ?
                      OR p.name LIKE ?
                      OR p.brand LIKE ?
                      OR p.model LIKE ?
                      OR c.name LIKE ?
                  )
                ORDER BY 
                    CASE 
                        WHEN pv.variant_name LIKE ? THEN 1
                        WHEN p.name LIKE ? THEN 2
                        WHEN p.brand LIKE ? THEN 3
                        ELSE 4
                    END,
                    p.name ASC
                LIMIT ?";

        $rows = [];
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm,
                $exactMatch, $exactMatch, $exactMatch,
                $limit
            ]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($this->mysqliConn) {
            $stmt = $this->mysqliConn->prepare($sql);
            $stmt->bind_param(
                "ssssssssi",
                $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm,
                $exactMatch, $exactMatch, $exactMatch,
                $limit
            );
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $stmt->close();
        }

        return $rows;
    }

    /**
     * Find color variants for a product
     */
    public function findColorVariants($productId) {
        $sql = "SELECT 
                    pv.variant_id,
                    c.name AS color_name,
                    c.slug AS color_slug,
                    (SELECT vi.image_url FROM variant_images vi WHERE vi.variant_id = pv.variant_id ORDER BY vi.is_primary DESC LIMIT 1) AS image_url
                FROM product_variants pv
                JOIN colors c ON pv.color_id = c.color_id
                WHERE pv.product_id = ? AND pv.is_available = 1
                ORDER BY pv.variant_id ASC";
        
        return $this->executeQuery($sql, [$productId]);
    }
    
    /**
     * Create a new variant
     */
    public function create($data) {
        $sql = "INSERT INTO product_variants (product_id, color_id, variant_name, price, is_available, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['product_id'],
                $data['color_id'],
                $data['variant_name'],
                $data['price'],
                $data['is_available'] ?? 1
            ]);
            return $this->pdo->lastInsertId();
        }
        return null;
    }
    
    /**
     * Update a variant
     */
    public function update($id, $data) {
        $sql = "UPDATE product_variants 
                SET product_id = ?, color_id = ?, variant_name = ?, price = ?, is_available = ?
                WHERE variant_id = ?";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['product_id'],
                $data['color_id'],
                $data['variant_name'],
                $data['price'],
                $data['is_available'] ?? 1,
                $id
            ]);
        }
        return false;
    }
    
    /**
     * Delete a variant
     */
    public function delete($id) {
        $sql = "DELETE FROM product_variants WHERE variant_id = ?";
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        }
        return false;
    }
}