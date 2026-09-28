<?php
// =========================================================
// Pengawal Autentikasi Pengguna (AuthController)
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

class AuthController extends Controller {
    private Student $studentModel;
    private Lecturer $lecturerModel;

    public function __construct() {
        /** @var Student $studentModel */
        $this->studentModel = $this->model('Student');
        /** @var Lecturer $lecturerModel */
        $this->lecturerModel = $this->model('Lecturer');
    }

    // Papar borang log masuk
    public function showLogin(): void {
        if (is_logged_in()) {
            $target = current_role() === 'student' ? 'student-profile' : 'lecturer-dashboard';
            $this->redirect('index.php?page=' . $target);
        }

        $activeRole = $_GET['role'] ?? 'student';
        $this->view('auth/login', [
            'activeRole' => $activeRole
        ]);
    }

    // Proses log masuk sistem
    public function login(): void {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            set_flash('danger', 'Sesi borang tamat tempoh. Sila cuba lagi.');
            $this->redirect('index.php?page=login');
        }

        $role = trim($_POST['role'] ?? 'student');
        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            set_flash('danger', 'Sila isi semua ruangan log masuk.');
            $this->redirect('index.php?page=login&role=' . urlencode($role));
        }

        if ($role === 'student') {
            $student = $this->studentModel->authenticate($identifier, $password);
            if ($student) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $student['student_id'];
                $_SESSION['student_id'] = $student['student_id'];
                $_SESSION['user_role'] = 'student';
                $_SESSION['user_name'] = $student['name'];
                $_SESSION['nric'] = $student['nric'];
                $_SESSION['profile_picture'] = $student['profile_picture'] ?? null;

                set_flash('success', 'Log masuk berjaya! Selamat datang ke Portal Pelajar PSP.');
                $this->redirect('index.php?page=student-profile');
            }
            set_flash('danger', 'No. Kad Pengenalan atau Kata Laluan salah.');
            $this->redirect('index.php?page=login&role=student');
        } elseif ($role === 'lecturer') {
            $lecturer = $this->lecturerModel->authenticate($identifier, $password);
            if ($lecturer) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $lecturer['lecturer_id'];
                $_SESSION['user_role'] = 'lecturer';
                $_SESSION['user_name'] = $lecturer['name'];
                $_SESSION['staff_id'] = $lecturer['staff_id'];

                set_flash('success', 'Log masuk berjaya! Selamat datang ke Portal Pensyarah PSP.');
                $this->redirect('index.php?page=lecturer-dashboard');
            }
            set_flash('danger', 'Staff ID / Emel atau Kata Laluan salah.');
            $this->redirect('index.php?page=login&role=lecturer');
        }

        $this->redirect('index.php?page=login');
    }

    // Log keluar pengguna & tamatkan sesi
    public function logout(): void {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();

        init_session();
        set_flash('info', 'Anda telah berjaya log keluar daripada portal.');
        $this->redirect('index.php?page=login');
    }
}
