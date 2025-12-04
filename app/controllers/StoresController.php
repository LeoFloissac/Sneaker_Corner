<?php

require_once __DIR__ . '/../models/Store.php';

class StoresController {
    private $storeModel;

    public function __construct($mysqliConn = null) {
        $this->storeModel = new Store($mysqliConn);
    }

    /**
     * Get all active stores with formatted data
     */
    public function index() {
        $stores = $this->storeModel->findAllActive();

        // Add Google Maps URL and format address for each store
        foreach ($stores as &$store) {
            $store = $this->formatStoreData($store);
        }

        return $stores;
    }

    /**
     * Get a single store by ID with formatted data
     */
    public function show($storeId) {
        $store = $this->storeModel->findById($storeId);

        if ($store) {
            $store = $this->formatStoreData($store);
        }

        return $store;
    }

    /**
     * Get all unique cities with stores
     */
    public function getCities() {
        return $this->storeModel->findAllCities();
    }

    /**
     * Format store data with maps URLs and full address
     */
    private function formatStoreData($store) {
        if ($store['latitude'] && $store['longitude']) {
            $store['maps_url'] = "https://www.google.com/maps?q={$store['latitude']},{$store['longitude']}";
            $store['maps_embed_url'] = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3000!2d{$store['longitude']}!3d{$store['latitude']}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM!5e0!3m2!1sen!2s!4v1600000000000!5m2!1sen!2s";
        } else {
            $address = urlencode("{$store['address']}, {$store['postal_code']} {$store['city']}, {$store['country']}");
            $store['maps_url'] = "https://www.google.com/maps/search/?api=1&query={$address}";
            $store['maps_embed_url'] = null;
        }

        $store['full_address'] = "{$store['address']}, {$store['postal_code']} {$store['city']}, {$store['country']}";

        return $store;
    }
}
