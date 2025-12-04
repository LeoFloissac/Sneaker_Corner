<?php

class Store {
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
     * Get all active stores
     */
    public function findAllActive() {
        $sql = "SELECT 
                    store_id,
                    name,
                    address,
                    city,
                    postal_code,
                    country,
                    phone,
                    email,
                    latitude,
                    longitude,
                    opening_hours
                FROM stores 
                WHERE is_active = 1
                ORDER BY city ASC";

        $stores = [];
        
        if ($this->pdo) {
            $stmt = $this->pdo->query($sql);
            $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($this->mysqliConn) {
            $res = $this->mysqliConn->query($sql);
            if ($res) {
                while ($r = $res->fetch_assoc()) {
                    $stores[] = $r;
                }
                $res->free();
            }
        }

        return $stores;
    }

    /**
     * Find a store by ID
     */
    public function findById($storeId) {
        $storeId = (int)$storeId;
        
        $sql = "SELECT 
                    store_id,
                    name,
                    address,
                    city,
                    postal_code,
                    country,
                    phone,
                    email,
                    latitude,
                    longitude,
                    opening_hours
                FROM stores 
                WHERE store_id = ? AND is_active = 1";

        $store = null;
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$storeId]);
            $store = $stmt->fetch(PDO::FETCH_ASSOC);
        } elseif ($this->mysqliConn) {
            $stmt = $this->mysqliConn->prepare($sql);
            $stmt->bind_param("i", $storeId);
            $stmt->execute();
            $result = $stmt->get_result();
            $store = $result->fetch_assoc();
            $stmt->close();
        }

        return $store;
    }

    /**
     * Get all unique cities with active stores
     */
    public function findAllCities() {
        $sql = "SELECT DISTINCT city FROM stores WHERE is_active = 1 ORDER BY city ASC";
        
        $cities = [];
        if ($this->pdo) {
            $stmt = $this->pdo->query($sql);
            $cities = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } elseif ($this->mysqliConn) {
            $res = $this->mysqliConn->query($sql);
            if ($res) {
                while ($r = $res->fetch_assoc()) {
                    $cities[] = $r['city'];
                }
                $res->free();
            }
        }
        return $cities;
    }
}
