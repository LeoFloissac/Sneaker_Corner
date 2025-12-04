<?php

class StoreInventory {
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
     * Find all inventory
     */
    public function findAll() {
        $sql = "SELECT si.*, vs.size, pv.variant_name
                FROM store_inventory si
                JOIN variant_sizes vs ON si.size_id = vs.size_id
                JOIN product_variants pv ON si.variant_id = pv.variant_id
                ORDER BY si.last_updated DESC";
        return $this->executeQuery($sql);
    }
    
    /**
     * Find inventory by ID
     */
    public function findById($id) {
        $sql = "SELECT si.*, vs.size, pv.variant_name
                FROM store_inventory si
                JOIN variant_sizes vs ON si.size_id = vs.size_id
                JOIN product_variants pv ON si.variant_id = pv.variant_id
                WHERE si.inventory_id = ?";
        return $this->executeQuerySingle($sql, [$id]);
    }
    
    /**
     * Find inventory by store ID
     */
    public function findByStoreId($storeId) {
        $sql = "SELECT si.*, vs.size, pv.variant_name, p.name as product_name, p.brand
                FROM store_inventory si
                JOIN variant_sizes vs ON si.size_id = vs.size_id
                JOIN product_variants pv ON si.variant_id = pv.variant_id
                JOIN products p ON pv.product_id = p.product_id
                WHERE si.store_id = ?
                ORDER BY p.name, pv.variant_name, vs.size";
        return $this->executeQuery($sql, [$storeId]);
    }
    
    /**
     * Find inventory by variant ID
     */
    public function findByVariantId($variantId) {
        $sql = "SELECT si.*, vs.size
                FROM store_inventory si
                JOIN variant_sizes vs ON si.size_id = vs.size_id
                WHERE si.variant_id = ?
                ORDER BY si.store_id, vs.size";
        return $this->executeQuery($sql, [$variantId]);
    }
    
    /**
     * Find inventory by store and variant
     */
    public function findByStoreAndVariant($storeId, $variantId) {
        $sql = "SELECT si.*, vs.size
                FROM store_inventory si
                JOIN variant_sizes vs ON si.size_id = vs.size_id
                WHERE si.store_id = ? AND si.variant_id = ?
                ORDER BY CAST(vs.size AS DECIMAL(5,1)) ASC";
        return $this->executeQuery($sql, [$storeId, $variantId]);
    }

    /**
     * Find stores with stock for a variant
     */
    public function findStoresWithStock($variantId) {
        $sql = "SELECT DISTINCT
                    s.store_id,
                    s.name,
                    s.address,
                    s.city,
                    s.postal_code,
                    s.phone,
                    s.latitude,
                    s.longitude,
                    s.opening_hours,
                    SUM(si.quantity) as total_stock
                FROM stores s
                JOIN store_inventory si ON s.store_id = si.store_id
                WHERE si.variant_id = ? AND si.quantity > 0 AND s.is_active = 1
                GROUP BY s.store_id
                ORDER BY s.city ASC";

        try {
            return $this->executeQuery($sql, [$variantId]);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Check availability
     */
    public function checkAvailability($storeId, $variantId, $sizeId) {
        $sql = "SELECT quantity FROM store_inventory 
                WHERE store_id = ? AND variant_id = ? AND size_id = ?";
        $result = $this->executeQuerySingle($sql, [$storeId, $variantId, $sizeId]);
        return $result ? $result['quantity'] : 0;
    }
    
    /**
     * Create inventory record
     */
    public function create($data) {
        $sql = "INSERT INTO store_inventory (store_id, variant_id, size_id, quantity, last_updated) 
                VALUES (?, ?, ?, ?, NOW())";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['store_id'],
                $data['variant_id'],
                $data['size_id'],
                $data['quantity'] ?? 0
            ]);
            return $this->pdo->lastInsertId();
        }
        return null;
    }
    
    /**
     * Update inventory record
     */
    public function update($id, $data) {
        $sql = "UPDATE store_inventory 
                SET store_id = ?, variant_id = ?, size_id = ?, quantity = ?, last_updated = NOW()
                WHERE inventory_id = ?";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['store_id'],
                $data['variant_id'],
                $data['size_id'],
                $data['quantity'],
                $id
            ]);
        }
        return false;
    }
    
    /**
     * Update quantity
     */
    public function updateQuantity($id, $quantity) {
        $sql = "UPDATE store_inventory 
                SET quantity = ?, last_updated = NOW()
                WHERE inventory_id = ?";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$quantity, $id]);
        }
        return false;
    }
    
    /**
     * Decrement quantity
     */
    public function decrementQuantity($storeId, $variantId, $sizeId, $quantity = 1) {
        $sql = "UPDATE store_inventory 
                SET quantity = quantity - ?, last_updated = NOW()
                WHERE store_id = ? AND variant_id = ? AND size_id = ? AND quantity >= ?";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$quantity, $storeId, $variantId, $sizeId, $quantity]);
        }
        return false;
    }
    
    /**
     * Delete inventory record
     */
    public function delete($id) {
        $sql = "DELETE FROM store_inventory WHERE inventory_id = ?";
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        }
        return false;
    }
}