<?php

class Product {
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
     * Find all products
     */
    public function findAll() {
        $sql = "SELECT * FROM products ORDER BY created_at DESC";
        return $this->executeQuery($sql);
    }
    
    /**
     * Find product by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM products WHERE product_id = ?";
        return $this->executeQuerySingle($sql, [$id]);
    }
    
    /**
     * Find product by slug
     */
    public function findBySlug($slug) {
        $sql = "SELECT * FROM products WHERE slug = ?";
        return $this->executeQuerySingle($sql, [$slug]);
    }
    
    /**
     * Find products by brand
     */
    public function findByBrand($brand) {
        $sql = "SELECT * FROM products WHERE brand = ? ORDER BY created_at DESC";
        return $this->executeQuery($sql, [$brand]);
    }
    
    /**
     * Find all available products
     */
    public function findAvailable() {
        $sql = "SELECT * FROM products WHERE is_available = 1 ORDER BY created_at DESC";
        return $this->executeQuery($sql);
    }

    /**
     * Get all available brands
     */
    public function findAllBrands() {
        $sql = "SELECT DISTINCT p.brand 
                FROM products p 
                JOIN product_variants pv ON p.product_id = pv.product_id
                WHERE p.is_available = 1 AND pv.is_available = 1
                ORDER BY p.brand ASC";
        
        $rows = $this->executeQuery($sql);
        return array_column($rows, 'brand');
    }
    
    /**
     * Create a new product
     */
    public function create($data) {
        $sql = "INSERT INTO products (name, slug, brand, model, release_date, is_available, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['name'],
                $data['slug'],
                $data['brand'],
                $data['model'],
                $data['release_date'] ?? null,
                $data['is_available'] ?? 1
            ]);
            return $this->pdo->lastInsertId();
        }
        return null;
    }
    
    /**
     * Update a product
     */
    public function update($id, $data) {
        $sql = "UPDATE products 
                SET name = ?, slug = ?, brand = ?, model = ?, release_date = ?, is_available = ?, updated_at = NOW()
                WHERE product_id = ?";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['name'],
                $data['slug'],
                $data['brand'],
                $data['model'],
                $data['release_date'] ?? null,
                $data['is_available'] ?? 1,
                $id
            ]);
        }
        return false;
    }
    
    /**
     * Delete a product
     */
    public function delete($id) {
        $sql = "DELETE FROM products WHERE product_id = ?";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        }
        return false;
    }
}