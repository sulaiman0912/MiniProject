<?php
// =========================================================
// Fail Permulaan (Bootstrap) & Autoloader MVC
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

// Muat fail konfigurasi & fungsi pembantu
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/helpers.php';

// Daftarkan Autoloader Kelas Automatik (spl_autoload_register)
spl_autoload_register(function (string $className): void {
    $directories = [
        APPROOT . '/core/',
        APPROOT . '/controllers/',
        APPROOT . '/models/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Mulakan sesi selamat secara global
init_session();
