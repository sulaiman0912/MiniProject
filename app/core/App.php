<?php
// =========================================================
// Kelas Teras Aplikasi & Penghala (App / Router)
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

class App {
    protected string $controller = 'AuthController';
    protected string $method = 'showLogin';
    protected array $params = [];

    // Jadual pemetaan laluan berasaskan parameter ?page=
    private array $routeMap = [
        'login' => [
            'controller' => 'AuthController',
            'action_get'  => 'showLogin',
            'action_post' => 'login'
        ],
        'logout' => [
            'controller' => 'AuthController',
            'action'     => 'logout'
        ],
        'student-profile' => [
            'controller' => 'StudentController',
            'action'     => 'profile'
        ],
        'update-student-password' => [
            'controller' => 'StudentController',
            'action'     => 'updatePassword'
        ],
        'upload-profile-picture' => [
            'controller' => 'StudentController',
            'action'     => 'uploadProfilePicture'
        ],
        'delete-profile-picture' => [
            'controller' => 'StudentController',
            'action'     => 'deleteProfilePicture'
        ],
        'lecturer-dashboard' => [
            'controller' => 'LecturerController',
            'action'     => 'dashboard'
        ],
        'add-student-grade' => [
            'controller' => 'LecturerController',
            'action'     => 'addStudentGrade'
        ],
        'update-grade-marks' => [
            'controller' => 'LecturerController',
            'action'     => 'updateGradeMarks'
        ],
        'delete-grade' => [
            'controller' => 'LecturerController',
            'action'     => 'deleteGrade'
        ],
        'update-lecturer-password' => [
            'controller' => 'LecturerController',
            'action'     => 'updatePassword'
        ],
    ];

    public function __construct() {
        $page = $_GET['page'] ?? '';

        // Jika tiada halaman diminta, lencongkan mengikut status sesi pengguna
        if (empty($page)) {
            if (is_logged_in()) {
                $target = current_role() === 'student' ? 'student-profile' : 'lecturer-dashboard';
                header('Location: index.php?page=' . $target);
            } else {
                header('Location: index.php?page=login');
            }
            exit;
        }

        $this->dispatch($page);
    }

    /**
     * Menjalankan Controller dan Action yang sepadan
     */
    protected function dispatch(string $page): void {
        if (isset($this->routeMap[$page])) {
            $route = $this->routeMap[$page];
            $controllerName = $route['controller'];

            if (isset($route['action'])) {
                $methodName = $route['action'];
            } else {
                $methodName = ($_SERVER['REQUEST_METHOD'] === 'POST')
                    ? ($route['action_post'] ?? 'index')
                    : ($route['action_get'] ?? 'index');
            }

            if (class_exists($controllerName)) {
                $controllerInstance = new $controllerName();
                if (method_exists($controllerInstance, $methodName)) {
                    call_user_func_array([$controllerInstance, $methodName], $this->params);
                    return;
                }
            }
        }

        // Laluan kecemasan / lalai sekiranya tiada padanan
        header('Location: index.php');
        exit;
    }
}
