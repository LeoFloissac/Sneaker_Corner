<?php

class VariantSize {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM variant_sizes ORDER BY size ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM variant_sizes WHERE size_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByVariantId($variantId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM variant_sizes 
            WHERE variant_id = ? 
            ORDER BY CAST(size AS UNSIGNED) ASC
        ");
        $stmt->execute([$variantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findAvailableSizes($variantId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM variant_sizes 
            WHERE variant_id = ? AND stock_quantity > 0
            ORDER BY CAST(size AS UNSIGNED) ASC
        ");
        $stmt->execute([$variantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO variant_sizes (variant_id, size, stock_quantity) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $data['variant_id'],
            $data['size'],
            $data['stock_quantity'] ?? 0
        ]);
        return $this->pdo->lastInsertId();
    }
    
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE variant_sizes 
            SET variant_id = ?, size = ?, stock_quantity = ?
            WHERE size_id = ?
        ");
        return $stmt->execute([
            $data['variant_id'],
            $data['size'],
            $data['stock_quantity'],
            $id
        ]);
    }
    
    public function updateStock($id, $quantity) {
        $stmt = $this->pdo->prepare("UPDATE variant_sizes SET stock_quantity = ? WHERE size_id = ?");
        return $stmt->execute([$quantity, $id]);
    }
    
    public function decrementStock($id, $quantity = 1) {
        $stmt = $this->pdo->prepare("
            UPDATE variant_sizes 
            SET stock_quantity = stock_quantity - ? 
            WHERE size_id = ? AND stock_quantity >= ?
        ");
        return $stmt->execute([$quantity, $id, $quantity]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM variant_sizes WHERE size_id = ?");
        return $stmt->execute([$id]);
    }
}