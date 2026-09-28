<?php
$pageTitle = 'Profil Pelajar';
require APPROOT . '/views/layouts/header.php';

$customPicExists = !empty($student['profile_picture']) && file_exists(dirname(APPROOT) . '/uploads/profile_pictures/' . $student['profile_picture']);
$profilePicUrl = $customPicExists ? 'uploads/profile_pictures/' . htmlspecialchars($student['profile_picture'], ENT_QUOTES, 'UTF-8') : null;
?>

<div class="container my-5 flex-grow-1">

    <!-- Tajuk Halaman -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center pb-3 mb-4 border-bottom border-dark">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-person-circle me-2"></i>PROFIL PELAJAR
            </h2>
            <p class="text-secondary mb-0">Part 1: Pengurusan Maklumat Peribadi, Gambar Profil &amp; Keselamatan Pelajar</p>
        </div>
        <div class="mt-3 mt-md-0">
            <span class="badge badge-monochrome badge-dark-fill py-2 px-3">
                <i class="bi bi-shield-check me-1"></i> Sesi Aktif: Disahkan
            </span>
        </div>
    </div>

    <!-- Kotak Penerangan Keselamatan Sesi -->
    <div class="alert bg-white border border-dark border-2 mb-4">
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-shield-lock-fill fs-5 mt-1"></i>
            <div>
                <strong class="d-block mb-1">Jaminan Keselamatan Sesi &amp; Kawalan Akses:</strong>
                <p class="mb-0 small text-dark">
                    Maklumat di bawah dipaparkan secara ketat berdasarkan pembolehubah <code>$_SESSION['student_id']</code> pelajar yang sedang log masuk. Sebarang percubaan memanipulasi parameter URL disekat bagi melindungi privasi data pelajar lain.
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kad Butiran Pelajar & Tukar Gambar Profil (Part 1.1 & Part 1.3 - Ringkas & Padat) -->
        <div class="col-12 col-lg-7">
            <div class="card card-monochrome h-100">
                <div class="card-header card-header-monochrome d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="bi bi-card-heading me-2"></i>BUTIRAN PERIBADI PELAJAR</span>
                    <span class="badge badge-monochrome badge-white-fill">ID: <?= e($student['student_id']) ?></span>
                </div>
                <div class="card-body p-4">

                    <!-- Bahagian Ringkas & Kecil untuk Gambar Profil -->
                    <div class="p-3 mb-4 rounded border border-dark bg-light">
                        <div class="d-flex flex-column flex-sm-row align-items-center gap-3">
                            <!-- Avatar Preview Kecil (80x80) -->
                            <div class="position-relative flex-shrink-0">
                                <div class="rounded-circle border border-2 border-dark overflow-hidden bg-white d-flex align-items-center justify-content-center shadow-sm" 
                                     style="width: 78px; height: 78px;">
                                    <img id="avatarPreview" 
                                         src="<?= $customPicExists ? $profilePicUrl : '' ?>" 
                                         alt="Avatar" 
                                         class="w-100 h-100 object-fit-cover <?= $customPicExists ? '' : 'd-none' ?>">
                                    <div id="avatarFallback" class="text-secondary <?= $customPicExists ? 'd-none' : '' ?>">
                                        <i class="bi bi-person-fill text-dark" style="font-size: 2.8rem;"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Borang Tukar Gambar Mini -->
                            <div class="flex-grow-1 w-100">
                                <form action="index.php?page=upload-profile-picture" method="POST" enctype="multipart/form-data" id="quickUploadForm">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    
                                    <label for="profile_picture_input" class="form-label fw-bold small mb-1">
                                        <i class="bi bi-camera me-1"></i>Tukar Gambar Profil:
                                    </label>

                                    <div class="input-group input-group-sm">
                                        <input type="file" 
                                               class="form-control form-control-monochrome" 
                                               id="profile_picture_input" 
                                               name="profile_picture" 
                                               accept=".jpg,.jpeg,.png,image/jpeg,image/png" 
                                               required>
                                        <button type="submit" class="btn btn-monochrome-dark px-3" id="btnSubmitUpload" title="Muat Naik Gambar">
                                            <i class="bi bi-upload me-1"></i> Simpan
                                        </button>
                                        <?php if ($customPicExists): ?>
                                            <button type="submit" 
                                                    formaction="index.php?page=delete-profile-picture" 
                                                    formnovalidate 
                                                    class="btn btn-outline-danger px-2" 
                                                    onclick="return confirm('Adakah anda pasti ingin memadam gambar profil ini?');"
                                                    title="Padam Gambar">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <div class="text-secondary small mt-1" style="font-size: 0.75rem;">
                                        Format: <strong>.jpg, .jpeg, .png</strong> &bull; Maksimum <strong>2MB</strong>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Jadual Butiran Pelajar -->
                    <table class="table table-bordered border-dark table-striped mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 35%;" class="bg-light text-dark fw-bold">Nama Pelajar:</th>
                                <td class="fw-bold text-dark fs-5"><?= e($student['name']) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark fw-bold">No. Kad Pengenalan (NRIC):</th>
                                <td class="font-monospace fw-semibold"><?= e($student['nric']) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark fw-bold">Program Pengajian:</th>
                                <td><?= e($student['program']) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark fw-bold">Institusi:</th>
                                <td>Politeknik Seberang Perai (PSP)</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark fw-bold">Tarikh Pendaftaran:</th>
                                <td class="small text-secondary"><?= e(date('d/m/Y, h:i A', strtotime($student['created_at']))) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kad Kemaskini Kata Laluan (Part 1.2) -->
        <div class="col-12 col-lg-5">
            <div class="card card-monochrome h-100">
                <div class="card-header card-header-monochrome">
                    <i class="bi bi-key-fill me-2"></i>KEMASKINI KATA LALUAN
                </div>
                <div class="card-body p-4">
                    <form action="index.php?page=update-student-password" method="POST" autocomplete="off">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                        <!-- Kata Laluan Semasa -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-bold small">Kata Laluan Semasa:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-dark border-end-0">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control form-control-monochrome border-start-0" id="current_password" name="current_password" required placeholder="Kata laluan semasa">
                            </div>
                        </div>

                        <!-- Kata Laluan Baharu -->
                        <div class="mb-3">
                            <label for="new_password" class="form-label fw-bold small">Kata Laluan Baharu:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-dark border-end-0">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input type="password" class="form-control form-control-monochrome border-start-0" id="new_password" name="new_password" required minlength="6" placeholder="Min. 6 aksara">
                            </div>
                        </div>

                        <!-- Sahkan Kata Laluan Baharu -->
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label fw-bold small">Sahkan Kata Laluan Baharu:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-dark border-end-0">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                                <input type="password" class="form-control form-control-monochrome border-start-0" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Ulang kata laluan baharu">
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-monochrome-dark py-2">
                                <i class="bi bi-shield-check me-1"></i> KEMASKINI KATA LALUAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Rekod Kursus & Gred Pelajar -->
        <div class="col-12">
            <div class="card card-monochrome">
                <div class="card-header card-header-monochrome d-flex justify-content-between align-items-center">
                    <span class="fw-bold">
                        <i class="bi bi-journal-bookmark me-2"></i>REKOD KURSUS &amp; PENILAIAN PELAJAR
                    </span>
                    <span class="badge badge-monochrome badge-white-fill">Jumlah Kursus: <?= count($studentGrades ?? []) ?></span>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($studentGrades)): ?>
                        <div class="table-responsive">
                            <table class="table table-monochrome table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Kod Kursus</th>
                                        <th>Nama Kursus</th>
                                        <th class="text-center">Markah</th>
                                        <th class="text-center">Gred</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Tarikh Kemaskini</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($studentGrades as $grade): ?>
                                        <tr>
                                            <td class="fw-bold"><?= e($grade['course_code']) ?></td>
                                            <td><?= e($grade['course_name']) ?></td>
                                            <td class="text-center fw-semibold"><?= number_format((float)$grade['marks'], 2) ?></td>
                                            <td class="text-center">
                                                <span class="badge badge-monochrome badge-dark-fill px-2">
                                                    <?= e($grade['grade_letter']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-monochrome <?= $grade['status'] === 'Pass' ? 'badge-dark-fill' : 'badge-white-fill' ?>">
                                                    <?= e($grade['status']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center small text-secondary">
                                                <?= e(date('d/m/Y', strtotime($grade['updated_at']))) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-4 text-center text-secondary">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Tiada rekod kursus berdaftar ditemui.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Skrip JavaScript Ringkas: Pratonton & Validasi Serta-merta -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('profile_picture_input');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarFallback = document.getElementById('avatarFallback');

    if (fileInput) {
        fileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            // 1. Had Saiz: Maksimum 2MB
            const maxSizeBytes = 2 * 1024 * 1024;
            if (file.size > maxSizeBytes) {
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                alert('Ralat: Saiz fail (' + fileSizeMB + ' MB) melebihi had 2MB.');
                fileInput.value = '';
                return;
            }

            // 2. Format: .jpg, .jpeg, .png
            const allowedExtensions = ['jpg', 'jpeg', 'png'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                alert('Ralat: Sila pilih fail format .jpg, .jpeg, atau .png sahaja.');
                fileInput.value = '';
                return;
            }

            // 3. Live Preview
            const reader = new FileReader();
            reader.onload = function (event) {
                if (avatarPreview) {
                    avatarPreview.src = event.target.result;
                    avatarPreview.classList.remove('d-none');
                }
                if (avatarFallback) {
                    avatarFallback.classList.add('d-none');
                }
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
