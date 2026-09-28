<?php
// =========================================================
// Portal Pelajar & Pensyarah PSP - MVC Front Controller
// =========================================================

// Muatkan fail bootstrap & autoloader senibina MVC
require_once __DIR__ . '/app/init.php';

// Jalankan enjin aplikasi & penghala (Router & Request Dispatcher)
$app = new App();
