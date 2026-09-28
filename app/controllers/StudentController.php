<?php
// =========================================================
// Pengawal Modul Pelajar (StudentController)
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

class StudentController extends Controller {
    private Student $studentModel;
    private Grade $gradeModel;

    public function __construct() {
        /** @var Student $studentModel */
        $this->studentModel = $this->model('Student');
        /** @var Grade $gradeModel */
        $this->gradeModel = $this->model('Grade');
    }

    // Part 1.1: Paparan Profil Pelajar (Berasaskan $_SESSION sahaja)
    public function profile(): void {
        require_login('student');

        $studentId = (int)$_SESSION['student_id'];
        $student = $this->studentModel->getById($studentId);

        if (!$student) {
            set_flash('danger', 'Profil pelajar tidak ditemui.');
            $this->redirect('index.php?page=logout');
        }

        // Ambil senarai gred pelajar daripada jadual grades
        $studentGrades = $this->gradeModel->getByStudentId($studentId);

        $this->view('student/profile', [
            'student'       => $student,
            'studentGrades' => $studentGrades
        ]);
    }

    // Part 1.2: Kemaskini Kata Laluan Pelajar
    public function updatePassword(): void {
        require_login('student');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=student-profile');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            set_flash('danger', 'Sesi borang tamat tempoh.');
            $this->redirect('index.php?page=student-profile');
        }

        $studentId = (int)$_SESSION['student_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            set_flash('danger', 'Sila lengkapkan semua ruangan kata laluan.');
            $this->redirect('index.php?page=student-profile');
        }

        if (strlen($newPassword) < 6) {
            set_flash('danger', 'Kata laluan baharu mestilah sekurang-kurangnya 6 aksara.');
            $this->redirect('index.php?page=student-profile');
        }

        if ($newPassword !== $confirmPassword) {
            set_flash('danger', 'Kata laluan baharu dan pengesahan tidak sepadan.');
            $this->redirect('index.php?page=student-profile');
        }

        if ($currentPassword === $newPassword) {
            set_flash('danger', 'Kata laluan baharu tidak boleh sama dengan kata laluan semasa.');
            $this->redirect('index.php?page=student-profile');
        }

        if (!$this->studentModel->verifyCurrentPassword($studentId, $currentPassword)) {
            set_flash('danger', 'Kata laluan semasa tidak tepat.');
            $this->redirect('index.php?page=student-profile');
        }

        if ($this->studentModel->updatePassword($studentId, $newPassword)) {
            set_flash('success', 'Kata laluan anda telah berjaya dikemaskini!');
        } else {
            set_flash('danger', 'Gagal mengemaskini kata laluan.');
        }

        $this->redirect('index.php?page=student-profile');
    }

    // Part 1.3: Muat Naik Gambar Profil Pelajar
    public function uploadProfilePicture(): void {
        require_login('student');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=student-profile');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            set_flash('danger', 'Sesi borang tamat tempoh. Sila muat semula halaman.');
            $this->redirect('index.php?page=student-profile');
        }

        $studentId = (int)$_SESSION['student_id'];
        $student = $this->studentModel->getById($studentId);

        if (!$student) {
            set_flash('danger', 'Profil pelajar tidak ditemui.');
            $this->redirect('index.php?page=logout');
        }

        // 1. Semak kewujudan fail yang dihantar
        if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] === UPLOAD_ERR_NO_FILE) {
            set_flash('danger', 'Sila pilih fail gambar (.jpg, .jpeg, .png) untuk dimuat naik.');
            $this->redirect('index.php?page=student-profile');
        }

        $file = $_FILES['profile_picture'];

        // 2. Semak ralat sistem muat naik PHP
        if ($file['error'] !== UPLOAD_ERR_OK) {
            if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
                set_flash('danger', 'Saiz fail melebihi had yang dibenarkan oleh pelayan (Maksimum 2MB).');
            } else {
                set_flash('danger', 'Ralat semasa memuat naik fail. Kod ralat: ' . $file['error']);
            }
            $this->redirect('index.php?page=student-profile');
        }

        // 3. Had Saiz Fail: Maksimum 2MB (2 * 1024 * 1024 bait)
        $maxFileSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxFileSize) {
            set_flash('danger', 'Saiz fail gambar melebihi had maksimum 2MB. Sila kurangkan saiz gambar.');
            $this->redirect('index.php?page=student-profile');
        }

        if ($file['size'] <= 0) {
            set_flash('danger', 'Fail yang dimuat naik kosong atau tidak sah.');
            $this->redirect('index.php?page=student-profile');
        }

        // 4. Had Format / Ekstensi Fail: .jpg, .jpeg, .png sahaja
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            set_flash('danger', 'Hanya format fail .jpg, .jpeg, dan .png sahaja yang dibenarkan.');
            $this->redirect('index.php?page=student-profile');
        }

        // 5. Pengesahan Keselamatan Kandungan Fail (MIME Type & Valid Image)
        $tmpFilePath = $file['tmp_name'];
        if (!is_uploaded_file($tmpFilePath)) {
            set_flash('danger', 'Muat naik fail tidak sah atau mencurigakan.');
            $this->redirect('index.php?page=student-profile');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($tmpFilePath);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/pjpeg'];

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            set_flash('danger', 'Kandungan fail bukan imej yang sah (.jpg, .jpeg, atau .png sahaja).');
            $this->redirect('index.php?page=student-profile');
        }

        $imageDetails = @getimagesize($tmpFilePath);
        if ($imageDetails === false) {
            set_flash('danger', 'Fail imej yang dimuat naik rosak atau tidak sah.');
            $this->redirect('index.php?page=student-profile');
        }

        // 6. Penamaan Semula Selamat dengan time() dan uniqid() untuk mengelakkan overwriting
        $newFilename = 'student_' . $studentId . '_' . time() . '_' . uniqid('', true) . '.' . $extension;

        // Direktori penyimpanan gambar
        $uploadDirectory = dirname(APPROOT) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile_pictures' . DIRECTORY_SEPARATOR;
        if (!is_dir($uploadDirectory)) {
            if (!mkdir($uploadDirectory, 0755, true)) {
                set_flash('danger', 'Gagal mencipta direktori penyimpanan pada pelayan.');
                $this->redirect('index.php?page=student-profile');
            }
        }

        $targetFilePath = $uploadDirectory . $newFilename;

        // 7. Pindahkan fail ke direktori sasaran & kemaskini pangkalan data
        if (move_uploaded_file($tmpFilePath, $targetFilePath)) {
            // Padam gambar profil lama jika wujud
            if (!empty($student['profile_picture'])) {
                $oldFilePath = $uploadDirectory . $student['profile_picture'];
                if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }

            // Kemaskini rekod pangkalan data
            if ($this->studentModel->updateProfilePicture($studentId, $newFilename)) {
                $_SESSION['profile_picture'] = $newFilename;
                set_flash('success', 'Gambar profil berjaya dimuat naik dan disimpan ke dalam pangkalan data!');
            } else {
                @unlink($targetFilePath); // Batalkan fail jika rekod DB gagal
                set_flash('danger', 'Gagal merekodkan maklumat gambar ke dalam pangkalan data.');
            }
        } else {
            set_flash('danger', 'Gagal memindahkan fail ke direktori simpanan.');
        }

        $this->redirect('index.php?page=student-profile');
    }

    // Padam Gambar Profil Pelajar
    public function deleteProfilePicture(): void {
        require_login('student');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=student-profile');
        }

        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            set_flash('danger', 'Sesi borang tamat tempoh.');
            $this->redirect('index.php?page=student-profile');
        }

        $studentId = (int)$_SESSION['student_id'];
        $student = $this->studentModel->getById($studentId);

        if ($student && !empty($student['profile_picture'])) {
            $uploadDirectory = dirname(APPROOT) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile_pictures' . DIRECTORY_SEPARATOR;
            $oldFilePath = $uploadDirectory . $student['profile_picture'];
            if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                @unlink($oldFilePath);
            }

            $this->studentModel->updateProfilePicture($studentId, null);
            unset($_SESSION['profile_picture']);
            set_flash('info', 'Gambar profil telah berjaya dipadam.');
        }

        $this->redirect('index.php?page=student-profile');
    }
}
