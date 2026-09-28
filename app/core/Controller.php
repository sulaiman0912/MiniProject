<?php
// =========================================================
// Kelas Teras Pengawal Asas (Base Controller)
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

abstract class Controller {
    /**
     * Memuatkan dan menghasilkan instans Model
     */
    public function model(string $model): object {
        $modelClass = ucfirst($model);
        if (class_exists($modelClass)) {
            return new $modelClass();
        }

        $modelFile = APPROOT . '/models/' . $modelClass . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $modelClass();
        }

        die("Model [$modelClass] tidak ditemui.");
    }

    /**
     * Memaparkan paparan (View) dan menyalurkan data
     */
    public function view(string $view, array $data = []): void {
        // Ekstrak pembolehubah daripada tatasusunan $data untuk kegunaan di dalam fail paparan
        extract($data);

        $viewFile = APPROOT . '/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("Fail paparan [$viewFile] tidak ditemui.");
        }
    }

    /**
     * Lencongan URL yang selamat
     */
    public function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }
}
