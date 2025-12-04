<?php

class VariantSize {
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
     * Find all sizes
     */
    public function findAll() {
        $sql = "SELECT * FROM variant_sizes ORDER BY size ASC";
        return $this->executeQuery($sql);
    }
    
    /**
     * Find size by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM variant_sizes WHERE size_id = ?";
        return $this->executeQuerySingle($sql, [$id]);
    }
    
    /**
     * Find sizes by variant ID
     */
    public function findByVariantId($variantId) {
        $sql = "SELECT size_id, size, stock_quantity
                FROM variant_sizes 
                WHERE variant_id = ? 
                ORDER BY CAST(size AS DECIMAL(5,1)) ASC";
        return $this->executeQuery($sql, [$variantId]);
    }
    
    /**
     * Find available sizes for a variant (with stock)
     */
    public function findAvailableSizes($variantId) {
        $sql = "SELECT * FROM variant_sizes 
                WHERE variant_id = ? AND stock_quantity > 0
                ORDER BY CAST(size AS DECIMAL(5,1)) ASC";
        return $this->executeQuery($sql, [$variantId]);
    }

    /**
     * Find all distinct available sizes across all products
     */
    public function findAllDistinctAvailable() {
        $sql = "SELECT DISTINCT vs.size
                FROM variant_sizes vs
                JOIN product_variants pv ON vs.variant_id = pv.variant_id
                JOIN products p ON pv.product_id = p.product_id
                WHERE pv.is_available = 1 AND p.is_available = 1 AND vs.stock_quantity > 0
                ORDER BY CAST(vs.size AS DECIMAL(5,1)) ASC";
        
        $rows = $this->executeQuery($sql);
        return array_column($rows, 'size');
    }
    
    /**
     * Create a new size
     */
    public function create($data) {
        $sql = "INSERT INTO variant_sizes (variant_id, size, stock_quantity) VALUES (?, ?, ?)";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['variant_id'],
                $data['size'],
                $data['stock_quantity'] ?? 0
            ]);
            return $this->pdo->lastInsertId();
        }
        return null;
    }
    
    /**
     * Update a size
     */
    public function update($id, $data) {
        $sql = "UPDATE variant_sizes 
                SET variant_id = ?, size = ?, stock_quantity = ?
                WHERE size_id = ?";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['variant_id'],
                $data['size'],
                $data['stock_quantity'],
                $id
            ]);
        }
        return false;
    }
    
    /**
     * Update stock quantity
     */
    public function updateStock($id, $quantity) {
        $sql = "UPDATE variant_sizes SET stock_quantity = ? WHERE size_id = ?";
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$quantity, $id]);
        }
        return false;
    }
    
    /**
     * Decrement stock quantity
     */
    public function decrementStock($id, $quantity = 1) {
        $sql = "UPDATE variant_sizes 
                SET stock_quantity = stock_quantity - ? 
                WHERE size_id = ? AND stock_quantity >= ?";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$quantity, $id, $quantity]);
        }
        return false;
    }
    
    /**
     * Delete a size
     */
    public function delete($id) {
        $sql = "DELETE FROM variant_sizes WHERE size_id = ?";
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        }
        return false;
    }
}