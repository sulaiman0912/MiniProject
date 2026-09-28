<?php
// =========================================================
// Pengawal Modul Pensyarah (LecturerController)
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

class LecturerController extends Controller {
    private Lecturer $lecturerModel;
    private Student $studentModel;
    private Grade $gradeModel;

    public function __construct() {
        /** @var Lecturer $lecturerModel */
        $this->lecturerModel = $this->model('Lecturer');
        /** @var Student $studentModel */
        $this->studentModel = $this->model('Student');
        /** @var Grade $gradeModel */
        $this->gradeModel = $this->model('Grade');
    }

    // Dashboard utama pensyarah
    public function dashboard(): void {
        require_login('lecturer');

        $lecturerId = (int)$_SESSION['user_id'];
        $lecturer = $this->lecturerModel->getById($lecturerId);

        if (!$lecturer) {
            set_flash('danger', 'Maklumat pensyarah tidak ditemui.');
            $this->redirect('index.php?page=logout');
        }

        $allStudents = $this->studentModel->getAll();
        $allGrades = $this->gradeModel->getAllWithStudents();

        $this->view('lecturer/dashboard', [
            'lecturer'    => $lecturer,
            'allStudents' => $allStudents,
            'allGrades'   => $allGrades
        ]);
    }

    // Tambah pelajar baharu dan rekod markah kursus
    public function addStudentGrade(): void {
        require_login('lecturer');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf($_POST['csrf_token'] ?? '')) {
            set_flash('danger', 'Sesi borang tidak sah.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        $name = trim($_POST['name'] ?? '');
        $nric = trim($_POST['nric'] ?? '');
        $program = trim($_POST['program'] ?? '');
        $courseCode = trim($_POST['course_code'] ?? '');
        $courseName = trim($_POST['course_name'] ?? '');
        $marksRaw = $_POST['marks'] ?? '';

        if (empty($name) || empty($nric) || empty($program) || empty($courseCode) || empty($courseName) || $marksRaw === '') {
            set_flash('danger', 'Sila lengkapkan semua maklumat borang.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        $marks = (float)$marksRaw;
        if ($marks < 0 || $marks > 100) {
            set_flash('danger', 'Markah mestilah antara 0 hingga 100.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        if ($this->studentModel->nricExists($nric)) {
            set_flash('danger', "Pelajar dengan IC $nric telah pun berdaftar.");
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        try {
            $db = Database::getConnection();
            $db->beginTransaction();

            $studentId = $this->studentModel->create($name, $nric, $program, 'password123');
            $this->gradeModel->create($studentId, $courseCode, $courseName, $marks);

            $db->commit();
            set_flash('success', "Pelajar baharu ($name) dan markah bagi kursus $courseCode telah berjaya disimpan!");
        } catch (Exception $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            set_flash('danger', 'Ralat semasa menyimpan data: ' . $e->getMessage());
        }

        $this->redirect('index.php?page=lecturer-dashboard');
    }

    // Kemaskini markah pelajar
    public function updateGradeMarks(): void {
        require_login('lecturer');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf($_POST['csrf_token'] ?? '')) {
            set_flash('danger', 'Sesi borang tamat tempoh.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        $gradeId = (int)($_POST['grade_id'] ?? 0);
        $marksRaw = $_POST['marks'] ?? '';

        if ($gradeId <= 0 || $marksRaw === '') {
            set_flash('danger', 'Data markah tidak lengkap.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        $marks = (float)$marksRaw;
        if ($marks < 0 || $marks > 100) {
            set_flash('danger', 'Markah mestilah antara 0 hingga 100.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        $record = $this->gradeModel->getById($gradeId);
        if (!$record) {
            set_flash('danger', 'Rekod gred tidak ditemui.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        if ($this->gradeModel->updateMarks($gradeId, $marks)) {
            $gred = Grade::calculateGradeLetter($marks);
            set_flash('success', "Markah {$record['student_name']} ({$record['course_code']}) dikemaskini kepada $marks (Gred: $gred)!");
        } else {
            set_flash('danger', 'Gagal mengemaskini markah.');
        }

        $this->redirect('index.php?page=lecturer-dashboard');
    }

    // Padam rekod gred penilaian
    public function deleteGrade(): void {
        require_login('lecturer');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf($_POST['csrf_token'] ?? '')) {
            set_flash('danger', 'Sesi borang tamat tempoh.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        $gradeId = (int)($_POST['grade_id'] ?? 0);
        $record = $this->gradeModel->getById($gradeId);

        if (!$record) {
            set_flash('danger', 'Rekod gred tidak ditemui.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        if ($this->gradeModel->delete($gradeId)) {
            set_flash('info', "Rekod penilaian {$record['course_code']} bagi {$record['student_name']} telah dipadam.");
        } else {
            set_flash('danger', 'Gagal memadam rekod penilaian.');
        }

        $this->redirect('index.php?page=lecturer-dashboard');
    }

    // Kemaskini kata laluan pensyarah
    public function updatePassword(): void {
        require_login('lecturer');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf($_POST['csrf_token'] ?? '')) {
            set_flash('danger', 'Sesi borang tamat tempoh.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        $lecturerId = (int)$_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            set_flash('danger', 'Sila lengkapkan semua ruangan kata laluan.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        if (strlen($newPassword) < 6) {
            set_flash('danger', 'Kata laluan baharu mestilah sekurang-kurangnya 6 aksara.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        if ($newPassword !== $confirmPassword) {
            set_flash('danger', 'Kata laluan baharu dan pengesahan tidak sepadan.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        if ($currentPassword === $newPassword) {
            set_flash('danger', 'Kata laluan baharu tidak boleh sama dengan kata laluan semasa.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        if (!$this->lecturerModel->verifyCurrentPassword($lecturerId, $currentPassword)) {
            set_flash('danger', 'Kata laluan semasa tidak tepat.');
            $this->redirect('index.php?page=lecturer-dashboard');
        }

        if ($this->lecturerModel->updatePassword($lecturerId, $newPassword)) {
            set_flash('success', 'Kata laluan pensyarah anda telah berjaya dikemaskini!');
        } else {
            set_flash('danger', 'Gagal mengemaskini kata laluan.');
        }

        $this->redirect('index.php?page=lecturer-dashboard');
    }
}
