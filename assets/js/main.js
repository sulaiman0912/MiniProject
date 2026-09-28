/**
 * PSP Student Portal - Client-Side Scripts
 */

document.addEventListener('DOMContentLoaded', function () {
    // Quick Demo Credentials Auto-Fill
    window.quickFill = function (role, identifier, password) {
        const roleInput = document.getElementById('roleInput');
        const idInput = document.getElementById('identifier');
        const passInput = document.getElementById('password');

        if (roleInput) roleInput.value = role;
        if (idInput) idInput.value = identifier;
        if (passInput) passInput.value = password;

        // Switch active tab visually
        const studentTab = document.getElementById('tab-student');
        const lecturerTab = document.getElementById('tab-lecturer');
        const idLabel = document.getElementById('idLabel');
        const idHelp = document.getElementById('idHelp');

        if (role === 'student') {
            if (studentTab) studentTab.classList.add('active');
            if (lecturerTab) lecturerTab.classList.remove('active');
            if (idLabel) idLabel.textContent = 'No. Kad Pengenalan (NRIC)';
            if (idHelp) idHelp.textContent = 'Format contoh: 040101-07-1234';
        } else {
            if (lecturerTab) lecturerTab.classList.add('active');
            if (studentTab) studentTab.classList.remove('active');
            if (idLabel) idLabel.textContent = 'Staff ID / Emel Pensyarah';
            if (idHelp) idHelp.textContent = 'Format contoh: L001 atau azman@psp.edu.my';
        }
    };

    // Role Switcher Click Handlers
    const studentTab = document.getElementById('tab-student');
    const lecturerTab = document.getElementById('tab-lecturer');

    if (studentTab && lecturerTab) {
        studentTab.addEventListener('click', function (e) {
            e.preventDefault();
            quickFill('student', '', '');
        });

        lecturerTab.addEventListener('click', function (e) {
            e.preventDefault();
            quickFill('lecturer', '', '');
        });
    }

    // Toggle Password Visibility
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            const icon = togglePasswordBtn.querySelector('i');
            if (icon) {
                icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
            }
        });
    }

    // Real-time Grade Calculation for Lecturer Add Student Form
    const marksInput = document.getElementById('student_marks');
    const gradePreviewBadge = document.getElementById('gradePreviewBadge');

    if (marksInput && gradePreviewBadge) {
        marksInput.addEventListener('input', function () {
            const val = parseFloat(marksInput.value);
            if (isNaN(val) || marksInput.value.trim() === '') {
                gradePreviewBadge.textContent = 'Gred: -';
                return;
            }
            let grade = 'F';
            let status = 'Fail';
            if (val >= 90) { grade = 'A+'; status = 'Pass'; }
            else if (val >= 80) { grade = 'A'; status = 'Pass'; }
            else if (val >= 75) { grade = 'A-'; status = 'Pass'; }
            else if (val >= 70) { grade = 'B+'; status = 'Pass'; }
            else if (val >= 65) { grade = 'B'; status = 'Pass'; }
            else if (val >= 60) { grade = 'B-'; status = 'Pass'; }
            else if (val >= 55) { grade = 'C+'; status = 'Pass'; }
            else if (val >= 50) { grade = 'C'; status = 'Pass'; }
            else if (val >= 40) { grade = 'D'; status = 'Fail'; }

            gradePreviewBadge.textContent = `Gred: ${grade} (${status})`;
        });
    }
});

