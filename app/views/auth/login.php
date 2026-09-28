<?php
$pageTitle = 'Log Masuk Portal';
require APPROOT . '/views/layouts/header.php';
?>

<div class="container my-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">

            <!-- Kad Log Masuk (Hitam Putih) -->
            <div class="card card-monochrome">
                <div class="card-header card-header-monochrome text-center py-3">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-shield-lock me-2"></i>LOG MASUK PORTAL PSP
                    </h4>
                    <small class="text-white-50">Sistem Pengurusan Pelajar &amp; Pensyarah</small>
                </div>

                <div class="card-body p-4">

                    <!-- Tab Pilihan Peranan -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-secondary mb-2">Pilih Peranan Anda:</label>
                        <div class="d-flex gap-2 nav-pills-monochrome">
                            <button type="button" id="tab-student" class="btn flex-fill <?= ($activeRole ?? 'student') === 'student' ? 'btn-monochrome-dark active' : 'btn-monochrome-outline' ?>" onclick="quickFill('student', '', '')">
                                <i class="bi bi-person me-1"></i> Pelajar
                            </button>
                            <button type="button" id="tab-lecturer" class="btn flex-fill <?= ($activeRole ?? '') === 'lecturer' ? 'btn-monochrome-dark active' : 'btn-monochrome-outline' ?>" onclick="quickFill('lecturer', '', '')">
                                <i class="bi bi-briefcase me-1"></i> Pensyarah
                            </button>
                        </div>
                    </div>

                    <!-- Borang Log Masuk -->
                    <form action="index.php?page=login" method="POST" id="loginForm" autocomplete="off">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="role" id="roleInput" value="<?= e($activeRole ?? 'student') ?>">

                        <!-- Input ID / NRIC -->
                        <div class="mb-3">
                            <label for="identifier" id="idLabel" class="form-label fw-bold">
                                <?= ($activeRole ?? 'student') === 'student' ? 'No. Kad Pengenalan (NRIC)' : 'Staff ID / Emel Pensyarah' ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-dark border-end-0">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <input type="text" 
                                       class="form-control form-control-monochrome border-start-0" 
                                       id="identifier" 
                                       name="identifier" 
                                       required 
                                       placeholder="<?= ($activeRole ?? 'student') === 'student' ? 'cth: 040101-07-1234' : 'cth: L001 atau azman@psp.edu.my' ?>">
                            </div>
                            <div class="form-text text-secondary" id="idHelp">
                                <?= ($activeRole ?? 'student') === 'student' ? 'Format: 040101-07-1234' : 'Format: L001 atau azman@psp.edu.my' ?>
                            </div>
                        </div>

                        <!-- Kata Laluan -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Kata Laluan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-dark border-end-0">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input type="password" 
                                       class="form-control form-control-monochrome border-start-0 border-end-0" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       placeholder="Masukkan kata laluan">
                                <button class="btn btn-outline-dark border-dark border-start-0" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Butang Hantar -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-monochrome-dark py-2">
                                <i class="bi bi-box-arrow-in-right me-1"></i> LOG MASUK
                            </button>
                        </div>
                    </form>

                    <hr class="border-dark my-4">

                    <!-- Kotak Akaun Demo -->
                    <div class="demo-box">
                        <div class="fw-bold small text-uppercase mb-2">
                            <i class="bi bi-info-circle me-1"></i>Akaun Contoh / Demo (Klik untuk auto-isi):
                        </div>

                        <div class="mb-2">
                            <small class="fw-bold d-block text-secondary mb-1">Akaun Pelajar:</small>
                            <div class="d-flex flex-wrap gap-1">
                                <button type="button" class="btn btn-sm btn-monochrome-outline py-1 px-2" style="font-size: 0.78rem;" onclick="quickFill('student', '040101-07-1234', 'password123')">
                                    <i class="bi bi-person-fill"></i> Ahmad (040101-07-1234)
                                </button>
                                <button type="button" class="btn btn-sm btn-monochrome-outline py-1 px-2" style="font-size: 0.78rem;" onclick="quickFill('student', '040512-08-5678', 'password123')">
                                    <i class="bi bi-person-fill"></i> Siti (040512-08-5678)
                                </button>
                            </div>
                        </div>

                        <div>
                            <small class="fw-bold d-block text-secondary mb-1">Akaun Pensyarah:</small>
                            <div class="d-flex flex-wrap gap-1">
                                <button type="button" class="btn btn-sm btn-monochrome-outline py-1 px-2" style="font-size: 0.78rem;" onclick="quickFill('lecturer', 'L001', 'password123')">
                                    <i class="bi bi-briefcase-fill"></i> Dr. Azman (L001)
                                </button>
                                <button type="button" class="btn btn-sm btn-monochrome-outline py-1 px-2" style="font-size: 0.78rem;" onclick="quickFill('lecturer', 'L002', 'password123')">
                                    <i class="bi bi-briefcase-fill"></i> Pn. Noraini (L002)
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
