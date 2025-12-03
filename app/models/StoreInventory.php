<?php

class StoreInventory {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function findAll() {
        $stmt = $this->pdo->query("
            SELECT si.*, vs.size, pv.variant_name
            FROM store_inventory si
            JOIN variant_sizes vs ON si.size_id = vs.size_id
            JOIN product_variants pv ON si.variant_id = pv.variant_id
            ORDER BY si.last_updated DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $stmt = $this->pdo->prepare("
            SELECT si.*, vs.size, pv.variant_name
            FROM store_inventory si
            JOIN variant_sizes vs ON si.size_id = vs.size_id
            JOIN product_variants pv ON si.variant_id = pv.variant_id
            WHERE si.inventory_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByStoreId($storeId) {
        $stmt = $this->pdo->prepare("
            SELECT si.*, vs.size, pv.variant_name, p.name as product_name, p.brand
            FROM store_inventory si
            JOIN variant_sizes vs ON si.size_id = vs.size_id
            JOIN product_variants pv ON si.variant_id = pv.variant_id
            JOIN products p ON pv.product_id = p.product_id
            WHERE si.store_id = ?
            ORDER BY p.name, pv.variant_name, vs.size
        ");
        $stmt->execute([$storeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findByVariantId($variantId) {
        $stmt = $this->pdo->prepare("
            SELECT si.*, vs.size
            FROM store_inventory si
            JOIN variant_sizes vs ON si.size_id = vs.size_id
            WHERE si.variant_id = ?
            ORDER BY si.store_id, vs.size
        ");
        $stmt->execute([$variantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findByStoreAndVariant($storeId, $variantId) {
        $stmt = $this->pdo->prepare("
            SELECT si.*, vs.size
            FROM store_inventory si
            JOIN variant_sizes vs ON si.size_id = vs.size_id
            WHERE si.store_id = ? AND si.variant_id = ?
            ORDER BY CAST(vs.size AS UNSIGNED) ASC
        ");
        $stmt->execute([$storeId, $variantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function checkAvailability($storeId, $variantId, $sizeId) {
        $stmt = $this->pdo->prepare("
            SELECT quantity FROM store_inventory 
            WHERE store_id = ? AND variant_id = ? AND size_id = ?
        ");
        $stmt->execute([$storeId, $variantId, $sizeId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['quantity'] : 0;
    }
    
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO store_inventory (store_id, variant_id, size_id, quantity, last_updated) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['store_id'],
            $data['variant_id'],
            $data['size_id'],
            $data['quantity'] ?? 0
        ]);
        return $this->pdo->lastInsertId();
    }
    
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE store_inventory 
            SET store_id = ?, variant_id = ?, size_id = ?, quantity = ?, last_updated = NOW()
            WHERE inventory_id = ?
        ");
        return $stmt->execute([
            $data['store_id'],
            $data['variant_id'],
            $data['size_id'],
            $data['quantity'],
            $id
        ]);
    }
    
    public function updateQuantity($id, $quantity) {
        $stmt = $this->pdo->prepare("
            UPDATE store_inventory 
            SET quantity = ?, last_updated = NOW()
            WHERE inventory_id = ?
        ");
        return $stmt->execute([$quantity, $id]);
    }
    
    public function decrementQuantity($storeId, $variantId, $sizeId, $quantity = 1) {
        $stmt = $this->pdo->prepare("
            UPDATE store_inventory 
            SET quantity = quantity - ?, last_updated = NOW()
            WHERE store_id = ? AND variant_id = ? AND size_id = ? AND quantity >= ?
        ");
        return $stmt->execute([$quantity, $storeId, $variantId, $sizeId, $quantity]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM store_inventory WHERE inventory_id = ?");
        return $stmt->execute([$id]);
    }
}