<?php

include "../../includes/auth.php";
include "../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: attendance.php");
    exit();
}

if (
    !isset($_POST['teacher_id']) ||
    !isset($_POST['subject_id']) ||
    !isset($_POST['class_id']) ||
    !isset($_POST['attendance_date']) ||
    !isset($_POST['attendance'])
) {
    die("Missing form data.");
}

$teacher_id = (int)$_POST['teacher_id'];
$subject_id = (int)$_POST['subject_id'];
$class_id   = (int)$_POST['class_id'];
$date       = $_POST['attendance_date'];
$attendance = $_POST['attendance'];



// Check teacher teaches this subject

$checkTeacher = $conn->prepare("
    SELECT COUNT(*)
    FROM teacher_subjects
    WHERE user_id = ?
    AND subject_id = ?
");

$checkTeacher->execute([$teacher_id, $subject_id]);

if ($checkTeacher->fetchColumn() == 0) {
    die("This teacher is not assigned to the selected subject.");
}

try {

    $conn->beginTransaction();

    foreach ($attendance as $student_id => $status) {

        $student_id = (int)$student_id;

        // Verify student belongs to class

        $checkStudent = $conn->prepare("
            SELECT COUNT(*)
            FROM students
            WHERE user_id = ?
            AND class_id = ?
        ");

        $checkStudent->execute([$student_id, $class_id]);

        if ($checkStudent->fetchColumn() == 0) {
            throw new Exception("Student ID $student_id does not belong to class ID $class_id.");
        }

        // Check if attendance already exists

        $exists = $conn->prepare("
            SELECT id
            FROM attendance
            WHERE student_id = ?
            AND subject_id = ?
            AND attendance_date = ?
        ");

        $exists->execute([
            $student_id,
            $subject_id,
            $date
        ]);

        if ($exists->rowCount() > 0) {

            $attendanceId = $exists->fetch(PDO::FETCH_ASSOC)['id'];

            $update = $conn->prepare("
                UPDATE attendance
                SET
                    class_id = ?,
                    teacher_id = ?,
                    status = ?
                WHERE id = ?
            ");

            $update->execute([
                $class_id,
                $teacher_id,
                $status,
                $attendanceId
            ]);

        } else {

            $insert = $conn->prepare("
                INSERT INTO attendance
                (
                    student_id,
                    class_id,
                    subject_id,
                    teacher_id,
                    attendance_date,
                    status
                )
                VALUES
                (?,?,?,?,?,?)
            ");

            $insert->execute([
                $student_id,
                $class_id,
                $subject_id,
                $teacher_id,
                $date,
                $status
            ]);

        }

    }

    $conn->commit();

    header("Location: attendance.php?success=1");
    exit();

} catch (Exception $e) {

    $conn->rollBack();

    die("Error: " . $e->getMessage());

}