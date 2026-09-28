<?php
$pageTitle = 'Dashboard Pensyarah';
require APPROOT . '/views/layouts/header.php';
?>

<div class="container my-5 flex-grow-1">

    <!-- Tajuk Halaman -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center pb-3 mb-4 border-bottom border-dark">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-speedometer2 me-2"></i>DASHBOARD PENSYARAH
            </h2>
            <p class="text-secondary mb-0">Portal Pensyarah Politeknik Seberang Perai</p>
        </div>
        <div class="mt-3 mt-md-0">
            <span class="badge badge-monochrome badge-dark-fill py-2 px-3">
                <i class="bi bi-briefcase me-1"></i> Peranan: Pensyarah
            </span>
        </div>
    </div>

    <!-- Profil Pensyarah & Senarai Pelajar Ringkas -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card card-monochrome h-100">
                <div class="card-header card-header-monochrome">
                    <i class="bi bi-person-badge me-2"></i>PROFIL PENSYARAH
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-secondary fw-bold text-uppercase d-block">Nama Penuh:</small>
                        <span class="fs-5 fw-bold"><?= e($lecturer['name']) ?></span>
                    </div>
                    <div class="mb-3">
                        <small class="text-secondary fw-bold text-uppercase d-block">Staff ID:</small>
                        <span class="badge badge-monochrome badge-dark-fill"><?= e($lecturer['staff_id']) ?></span>
                    </div>
                    <div class="mb-3">
                        <small class="text-secondary fw-bold text-uppercase d-block">Jabatan:</small>
                        <span><?= e($lecturer['department']) ?></span>
                    </div>
                    <div>
                        <small class="text-secondary fw-bold text-uppercase d-block">Emel Rasmi:</small>
                        <span class="font-monospace"><?= e($lecturer['email']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card card-monochrome h-100">
                <div class="card-header card-header-monochrome d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people me-2"></i>SENARAI PELAJAR BERDAFTAR</span>
                    <span class="badge badge-monochrome badge-white-fill">Jumlah: <?= count($allStudents ?? []) ?></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-monochrome table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Pelajar</th>
                                    <th>No. Kad Pengenalan (NRIC)</th>
                                    <th>Program Pengajian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (($allStudents ?? []) as $idx => $st): ?>
                                    <tr>
                                        <td><?= $idx + 1 ?></td>
                                        <td class="fw-bold">
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if (!empty($st['profile_picture']) && file_exists(dirname(APPROOT) . '/uploads/profile_pictures/' . $st['profile_picture'])): ?>
                                                    <img src="uploads/profile_pictures/<?= e($st['profile_picture']) ?>" alt="Avatar" class="rounded-circle border border-dark" style="width: 28px; height: 28px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="rounded-circle border border-dark d-flex align-items-center justify-content-center bg-light" style="width: 28px; height: 28px; font-size: 0.85rem;">
                                                        <i class="bi bi-person-fill text-dark"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <span><?= e($st['name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="font-monospace"><?= e($st['nric']) ?></td>
                                        <td class="small"><?= e($st['program']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Pelajar & Kemaskini Kata Laluan (Bersebelahan) -->
    <div class="row g-4 mb-4">
        <!-- Borang Tambah Pelajar & Markah -->
        <div class="col-12 col-lg-7 col-xl-8">
            <div class="card card-monochrome h-100">
                <div class="card-header card-header-monochrome d-flex justify-content-between align-items-center">
                    <span class="fw-bold">
                        <i class="bi bi-person-plus-fill me-2"></i>TAMBAH PELAJAR &amp; REKOD MARKAH BAHARU
                    </span>
                    <span class="badge badge-monochrome badge-white-fill">Borang Pensyarah</span>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?page=add-student-grade" method="POST" autocomplete="off">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="student_name" class="form-label fw-bold small">Nama Penuh Pelajar:</label>
                                <input type="text" class="form-control form-control-monochrome" id="student_name" name="name" required placeholder="cth: Daniel Lee Jian Wei">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="student_nric" class="form-label fw-bold small">No. Kad Pengenalan (IC / NRIC):</label>
                                <input type="text" class="form-control form-control-monochrome font-monospace" id="student_nric" name="nric" required placeholder="cth: 040915-07-5566">
                                <div class="form-text text-secondary small">Format: YYMMDD-PB-###G</div>
                            </div>

                            <div class="col-12">
                                <label for="student_program" class="form-label fw-bold small">Program Pengajian:</label>
                                <select class="form-select form-control-monochrome" id="student_program" name="program" required>
                                    <option value="" disabled selected>Pilih Program Pelajar...</option>
                                    <option value="Diploma Teknologi Maklumat (Teknologi Digital)">Diploma Teknologi Maklumat (Teknologi Digital)</option>
                                    <option value="Diploma Teknologi Maklumat (Keselamatan Maklumat)">Diploma Teknologi Maklumat (Keselamatan Maklumat)</option>
                                    <option value="Diploma Kejuruteraan Elektronik (Komputer)">Diploma Kejuruteraan Elektronik (Komputer)</option>
                                    <option value="Diploma Kejuruteraan Elektrik">Diploma Kejuruteraan Elektrik</option>
                                    <option value="Diploma Kejuruteraan Mekanikal">Diploma Kejuruteraan Mekanikal</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="course_code" class="form-label fw-bold small">Kod Kursus:</label>
                                <input type="text" class="form-control form-control-monochrome font-monospace" id="course_code" name="course_code" required placeholder="cth: DFP50193" value="DFP50193">
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="course_name" class="form-label fw-bold small">Nama Kursus:</label>
                                <input type="text" class="form-control form-control-monochrome" id="course_name" name="course_name" required placeholder="cth: Web Application Development" value="Web Application Development">
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="student_marks" class="form-label fw-bold small">Markah (0 - 100):</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="100" class="form-control form-control-monochrome fw-bold" id="student_marks" name="marks" required placeholder="0.00">
                                    <span class="input-group-text bg-white border-dark border-start-0 small" id="gradePreviewBadge">
                                        Gred: -
                                    </span>
                                </div>
                            </div>

                            <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 pt-2">
                                <div class="small text-secondary">
                                    <i class="bi bi-info-circle me-1"></i>Kata laluan permulaan: <code>password123</code>.
                                </div>
                                <button type="submit" class="btn btn-monochrome-dark px-4 py-2">
                                    <i class="bi bi-plus-circle me-1"></i> DAFTAR PELAJAR &amp; REKOD MARKAH
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Borang Kemaskini Kata Laluan Pensyarah -->
        <div class="col-12 col-lg-5 col-xl-4">
            <div class="card card-monochrome h-100">
                <div class="card-header card-header-monochrome">
                    <i class="bi bi-key-fill me-2"></i>KEMASKINI KATA LALUAN
                </div>
                <div class="card-body p-4">
                    <form action="index.php?page=update-lecturer-password" method="POST" autocomplete="off">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                        <div class="mb-3">
                            <label for="lecturer_current_password" class="form-label fw-bold small">Kata Laluan Semasa:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-dark border-end-0">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control form-control-monochrome border-start-0" id="lecturer_current_password" name="current_password" required placeholder="Kata laluan semasa">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="lecturer_new_password" class="form-label fw-bold small">Kata Laluan Baharu:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-dark border-end-0">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input type="password" class="form-control form-control-monochrome border-start-0" id="lecturer_new_password" name="new_password" required minlength="6" placeholder="Min. 6 aksara">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="lecturer_confirm_password" class="form-label fw-bold small">Sahkan Kata Laluan Baharu:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-dark border-end-0">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                                <input type="password" class="form-control form-control-monochrome border-start-0" id="lecturer_confirm_password" name="confirm_password" required minlength="6" placeholder="Ulang kata laluan baharu">
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
    </div>

    <!-- Jadual Rekod Gred & Penilaian Pelajar -->
    <div class="card card-monochrome">
        <div class="card-header card-header-monochrome d-flex justify-content-between align-items-center">
            <span><i class="bi bi-file-earmark-bar-graph me-2"></i>REKOD GRED &amp; PENILAIAN PELAJAR</span>
            <span class="badge badge-monochrome badge-white-fill">Rekod: <?= count($allGrades ?? []) ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-monochrome table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama Pelajar</th>
                            <th>NRIC</th>
                            <th>Kod Kursus</th>
                            <th>Nama Kursus</th>
                            <th class="text-center">Markah</th>
                            <th class="text-center">Gred</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (($allGrades ?? []) as $grade): ?>
                            <tr>
                                <td class="fw-bold"><?= e($grade['student_name']) ?></td>
                                <td class="font-monospace small"><?= e($grade['nric']) ?></td>
                                <td class="fw-semibold"><?= e($grade['course_code']) ?></td>
                                <td><?= e($grade['course_name']) ?></td>
                                <td class="text-center fw-semibold"><?= number_format((float)$grade['marks'], 2) ?></td>
                                <td class="text-center">
                                    <span class="badge badge-monochrome badge-dark-fill">
                                        <?= e($grade['grade_letter']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-monochrome <?= $grade['status'] === 'Pass' ? 'badge-dark-fill' : 'badge-white-fill' ?>">
                                        <?= e($grade['status']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Butang Kemaskini (Modal) -->
                                        <button type="button" class="btn btn-sm btn-monochrome-dark py-1 px-2" data-bs-toggle="modal" data-bs-target="#editGradeModal<?= $grade['grade_id'] ?>">
                                            <i class="bi bi-pencil-square me-1"></i>Kemaskini
                                        </button>

                                        <!-- Butang Padam Rekod -->
                                        <form action="index.php?page=delete-grade" method="POST" class="d-inline" onsubmit="return confirm('Adakah anda pasti mahu memadam rekod penilaian bagi <?= e(addslashes($grade['student_name'])) ?> (<?= e($grade['course_code']) ?>)?');">
                                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                            <input type="hidden" name="grade_id" value="<?= $grade['grade_id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-monochrome-outline py-1 px-2">
                                                <i class="bi bi-trash"></i> Padam
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Kemaskini Markah -->
                                    <div class="modal fade" id="editGradeModal<?= $grade['grade_id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content card-monochrome text-start">
                                                <div class="modal-header card-header-monochrome">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="bi bi-pencil-square me-2"></i>KEMASKINI MARKAH PELAJAR
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="index.php?page=update-grade-marks" method="POST">
                                                    <div class="modal-body p-4">
                                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                                        <input type="hidden" name="grade_id" value="<?= $grade['grade_id'] ?>">

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-secondary">Nama Pelajar:</label>
                                                            <input type="text" class="form-control form-control-monochrome bg-light" value="<?= e($grade['student_name']) ?> (<?= e($grade['nric']) ?>)" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-secondary">Kursus:</label>
                                                            <input type="text" class="form-control form-control-monochrome bg-light" value="<?= e($grade['course_code']) ?> - <?= e($grade['course_name']) ?>" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="marks_<?= $grade['grade_id'] ?>" class="form-label fw-bold small">Markah Baharu (0 - 100):</label>
                                                            <input type="number" step="0.01" min="0" max="100" class="form-control form-control-monochrome fw-bold fs-5" id="marks_<?= $grade['grade_id'] ?>" name="marks" required value="<?= e($grade['marks']) ?>">
                                                            <div class="form-text text-secondary small">Gred huruf dan status Pass/Fail dikira semula secara automatik.</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-dark p-3">
                                                        <button type="button" class="btn btn-monochrome-outline" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-monochrome-dark">
                                                            <i class="bi bi-check-lg me-1"></i> SIMPAN PERUBAHAN
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
