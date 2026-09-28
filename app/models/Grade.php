<?php
// =========================================================
// Model Penilaian & Gred (Grade Model)
// Portal Pelajar & Pensyarah PSP - Senibina MVC
// =========================================================

class Grade extends Model {

    // Pengiraan gred huruf mengikut julat markah standard
    public static function calculateGradeLetter(float $marks): string {
        if ($marks >= 90) return 'A+';
        if ($marks >= 80) return 'A';
        if ($marks >= 75) return 'A-';
        if ($marks >= 70) return 'B+';
        if ($marks >= 65) return 'B';
        if ($marks >= 60) return 'B-';
        if ($marks >= 55) return 'C+';
        if ($marks >= 50) return 'C';
        if ($marks >= 40) return 'D';
        return 'F';
    }

    // Penentuan status Lulus/Gagal (Pass/Fail)
    public static function calculateStatus(float $marks): string {
        return $marks >= 50 ? 'Pass' : 'Fail';
    }

    // Dapatkan senarai gred mengikut ID pelajar
    public function getByStudentId(int $student_id): array {
        $stmt = $this->db->prepare("
            SELECT grade_id, student_id, course_code, course_name, marks, grade_letter, status, updated_at 
            FROM grades 
            WHERE student_id = :student_id 
            ORDER BY course_code ASC
        ");
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll();
    }

    // Dapatkan semua rekod gred bersama butiran pelajar untuk pensyarah
    public function getAllWithStudents(): array {
        $stmt = $this->db->query("
            SELECT g.grade_id, g.student_id, g.course_code, g.course_name, g.marks, g.grade_letter, g.status,
                   s.name AS student_name, s.nric, s.program
            FROM grades g
            JOIN students s ON g.student_id = s.student_id
            ORDER BY s.name ASC, g.course_code ASC
        ");
        return $stmt->fetchAll();
    }

    // Tambah rekod gred kursus baharu
    public function create(int $student_id, string $course_code, string $course_name, float $marks): int {
        $grade_letter = self::calculateGradeLetter($marks);
        $status = self::calculateStatus($marks);

        $stmt = $this->db->prepare("
            INSERT INTO grades (student_id, course_code, course_name, marks, grade_letter, status)
            VALUES (:student_id, :course_code, :course_name, :marks, :grade_letter, :status)
        ");
        $stmt->execute([
            ':student_id'   => $student_id,
            ':course_code'  => trim($course_code),
            ':course_name'  => trim($course_name),
            ':marks'        => $marks,
            ':grade_letter' => $grade_letter,
            ':status'       => $status
        ]);
        return (int)$this->db->lastInsertId();
    }

    // Kemaskini markah dan kemaskini gred & status secara automatik
    public function updateMarks(int $grade_id, float $marks): bool {
        $grade_letter = self::calculateGradeLetter($marks);
        $status = self::calculateStatus($marks);

        $stmt = $this->db->prepare("
            UPDATE grades 
            SET marks = :marks, grade_letter = :grade_letter, status = :status 
            WHERE grade_id = :grade_id
        ");
        return $stmt->execute([
            ':marks'        => $marks,
            ':grade_letter' => $grade_letter,
            ':status'       => $status,
            ':grade_id'     => $grade_id
        ]);
    }

    // Padam rekod gred
    public function delete(int $grade_id): bool {
        $stmt = $this->db->prepare("DELETE FROM grades WHERE grade_id = :grade_id");
        return $stmt->execute([':grade_id' => $grade_id]);
    }

    // Dapatkan data rekod gred mengikut ID rekod
    public function getById(int $grade_id): array|false {
        $stmt = $this->db->prepare("
            SELECT g.*, s.name AS student_name, s.nric, s.program 
            FROM grades g
            JOIN students s ON g.student_id = s.student_id
            WHERE g.grade_id = :grade_id
            LIMIT 1
        ");
        $stmt->execute([':grade_id' => $grade_id]);
        return $stmt->fetch();
    }
}
