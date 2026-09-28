<?php
$isLoggedIn = is_logged_in();
$userRole = current_role();
$userName = $_SESSION['user_name'] ?? '';
$currentPage = $_GET['page'] ?? '';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' | ' : '' ?>Portal PSP</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Navigasi Utama -->
<nav class="navbar navbar-expand-lg navbar-monochrome py-2">
    <div class="container">
        <a class="navbar-brand navbar-brand-text d-flex align-items-center gap-2" href="index.php">
            <i class="bi bi-mortarboard-fill fs-4"></i>
            <span>PSP STUDENT PORTAL</span>
        </a>
        <button class="navbar-toggler border-dark" type="button" data-bs-target="#navbarContent" data-bs-toggle="collapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-2 mt-2 mt-lg-0">
                <?php if ($isLoggedIn): ?>
                    <?php if ($userRole === 'student'): ?>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold <?= $currentPage === 'student-profile' ? 'text-decoration-underline text-black' : 'text-dark' ?>" href="index.php?page=student-profile">
                                <i class="bi bi-person-badge me-1"></i> Profil Pelajar
                            </a>
                        </li>
                    <?php elseif ($userRole === 'lecturer'): ?>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold <?= $currentPage === 'lecturer-dashboard' ? 'text-decoration-underline text-black' : 'text-dark' ?>" href="index.php?page=lecturer-dashboard">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard Pensyarah
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item d-none d-lg-block">
                        <span class="badge badge-monochrome badge-dark-fill d-inline-flex align-items-center gap-2 py-1 px-2">
                            <?php if ($userRole === 'student' && !empty($_SESSION['profile_picture']) && file_exists(dirname(APPROOT) . '/uploads/profile_pictures/' . $_SESSION['profile_picture'])): ?>
                                <img src="uploads/profile_pictures/<?= e($_SESSION['profile_picture']) ?>" alt="Avatar" class="rounded-circle border border-white" style="width: 22px; height: 22px; object-fit: cover;">
                            <?php elseif ($userRole === 'student'): ?>
                                <i class="bi bi-person-circle"></i>
                            <?php else: ?>
                                <i class="bi bi-briefcase-fill"></i>
                            <?php endif; ?>
                            <span><?= $userRole === 'student' ? 'PELAJAR' : 'PENSYARAH' ?>: <?= e($userName) ?></span>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-sm btn-monochrome-outline" href="index.php?page=logout">
                            <i class="bi bi-box-arrow-right me-1"></i> Log Keluar
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-monochrome-dark" href="index.php?page=login">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Log Masuk
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Notifikasi Mesej Flash -->
<?php $flash = get_flash(); if ($flash): $isError = in_array($flash['type'], ['danger', 'error']); ?>
<div class="container mt-3">
    <div class="alert <?= $isError ? 'bg-dark text-white border-dark' : 'bg-white text-dark border-dark border-2' ?> alert-dismissible fade show my-2 shadow-sm" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi <?= $isError ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' ?> fs-5"></i>
            <div><?= e($flash['message']) ?></div>
        </div>
        <button type="button" class="btn-close <?= $isError ? 'btn-close-white' : '' ?>" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>
