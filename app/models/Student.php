<?php
// =========================================================
// Model Pelajar (Student Model)
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

class Student extends Model {

    // Sahkan log masuk pelajar
    public function authenticate(string $nric, string $password): array|false {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE nric = :nric LIMIT 1");
        $stmt->execute([':nric' => trim($nric)]);
        $student = $stmt->fetch();

        if ($student && password_verify($password, $student['password'])) {
            return $student;
        }
        return false;
    }

    // Dapatkan data profil pelajar mengikut ID
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE student_id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Dapatkan semua rekod pelajar
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM students ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    // Semak jika No. Kad Pengenalan telah berdaftar
    public function nricExists(string $nric): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM students WHERE nric = :nric");
        $stmt->execute([':nric' => trim($nric)]);
        return ((int)$stmt->fetchColumn()) > 0;
    }

    // Daftar pelajar baharu ke pangkalan data
    public function create(string $name, string $nric, string $program, string $password = 'password123'): int {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("
            INSERT INTO students (name, nric, program, password) 
            VALUES (:name, :nric, :program, :password)
        ");
        $stmt->execute([
            ':name'     => trim($name),
            ':nric'     => trim($nric),
            ':program'  => trim($program),
            ':password' => $hashed
        ]);
        return (int)$this->db->lastInsertId();
    }

    // Semak kesahihan kata laluan semasa
    public function verifyCurrentPassword(int $student_id, string $password): bool {
        $stmt = $this->db->prepare("SELECT password FROM students WHERE student_id = :id LIMIT 1");
        $stmt->execute([':id' => $student_id]);
        $hash = $stmt->fetchColumn();
        return $hash ? password_verify($password, $hash) : false;
    }

    // Kemaskini kata laluan pelajar
    public function updatePassword(int $student_id, string $new_password): bool {
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE students SET password = :password WHERE student_id = :id");
        return $stmt->execute([
            ':password' => $hashed,
            ':id'       => $student_id
        ]);
    }

    // Kemaskini gambar profil pelajar
    public function updateProfilePicture(int $student_id, ?string $filename): bool {
        $stmt = $this->db->prepare("UPDATE students SET profile_picture = :picture WHERE student_id = :id");
        return $stmt->execute([
            ':picture' => $filename,
            ':id'      => $student_id
        ]);
    }
}
