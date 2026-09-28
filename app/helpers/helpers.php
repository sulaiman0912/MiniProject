<?php
// =========================================================
// Fungsi Pembantu Global (Security, Session & Flash)
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

// Mulakan sesi yang selamat
function init_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);
        session_start();
    }
}

// Semak status log masuk
function is_logged_in(): bool {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
}

// Dapatkan peranan pengguna semasa
function current_role(): ?string {
    return $_SESSION['user_role'] ?? null;
}

// Sekatan akses pengguna berdasarkan peranan
function require_login(?string $role = null): void {
    if (!is_logged_in()) {
        set_flash('danger', 'Sila log masuk untuk mengakses halaman ini.');
        header('Location: index.php?page=login');
        exit;
    }

    if ($role !== null && current_role() !== $role) {
        set_flash('danger', 'Akses tidak dibenarkan bagi peranan anda.');
        header('Location: index.php?page=' . (current_role() === 'student' ? 'student-profile' : 'lecturer-dashboard'));
        exit;
    }
}

// Perlindungan serangan XSS (Sanitasi paparan)
function e(?string $str): string {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// Perlindungan CSRF: Hasilkan token unik
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Perlindungan CSRF: Pengesahan token
function verify_csrf(?string $token): bool {
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Tetapkan mesej notifikasi flash
function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

// Ambil & hapuskan mesej notifikasi flash selepas dipaparkan
function get_flash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
