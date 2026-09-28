<?php
// =========================================================
// Model Pensyarah (Lecturer Model)
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

class Lecturer extends Model {

    // Sahkan log masuk pensyarah menggunakan Staff ID atau Emel
    public function authenticate(string $identifier, string $password): array|false {
        $stmt = $this->db->prepare("SELECT * FROM lecturers WHERE staff_id = :staff_id OR email = :email LIMIT 1");
        $id = trim($identifier);
        $stmt->execute([':staff_id' => $id, ':email' => $id]);
        $lecturer = $stmt->fetch();

        if ($lecturer && password_verify($password, $lecturer['password'])) {
            return $lecturer;
        }
        return false;
    }

    // Dapatkan maklumat profil pensyarah mengikut ID
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM lecturers WHERE lecturer_id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Dapatkan semua rekod pensyarah
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM lecturers ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    // Semak kesahihan kata laluan semasa
    public function verifyCurrentPassword(int $lecturer_id, string $password): bool {
        $stmt = $this->db->prepare("SELECT password FROM lecturers WHERE lecturer_id = :id LIMIT 1");
        $stmt->execute([':id' => $lecturer_id]);
        $hash = $stmt->fetchColumn();
        return $hash ? password_verify($password, $hash) : false;
    }

    // Kemaskini kata laluan pensyarah
    public function updatePassword(int $lecturer_id, string $new_password): bool {
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE lecturers SET password = :password WHERE lecturer_id = :id");
        return $stmt->execute([
            ':password' => $hashed,
            ':id'       => $lecturer_id
        ]);
    }
}
