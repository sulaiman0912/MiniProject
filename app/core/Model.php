<?php
// =========================================================
// Kelas Teras Model Asas (Base Model)
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

abstract class Model {
    protected PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }
}
