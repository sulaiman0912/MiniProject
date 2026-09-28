<?php
// =========================================================
// Kelas Teras Pangkalan Data (Singleton PDO)
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die("Gagal menyambung ke pangkalan data: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
