<?php

class Color {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM colors ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM colors WHERE color_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findBySlug($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM colors WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO colors (name, slug) VALUES (?, ?)");
        $stmt->execute([$data['name'], $data['slug']]);
        return $this->pdo->lastInsertId();
    }
    
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE colors SET name = ?, slug = ? WHERE color_id = ?");
        return $stmt->execute([$data['name'], $data['slug'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM colors WHERE color_id = ?");
        return $stmt->execute([$id]);
    }
}