<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != "teacher"){
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user']['id'];

// Get submissions for teacher's classes
$submissions = mysqli_query($conn,
    "SELECT submissions.*, users.name as student_name,
            assignments.title as assignment_title,
            classrooms.class_name
     FROM submissions
     JOIN users ON submissions.student_id = users.id
     JOIN assignments ON submissions.assignment_id = assignments.id
     JOIN classrooms ON assignments.classroom_id = classrooms.id
     WHERE classrooms.teacher_id = '$teacher_id'");
?>

<!DOCTYPE html>
<html>
<head>
<title>View Submissions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f4f6f9;}
.card{
    border:none;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}
</style>
</head>
<body>

<div class="container mt-5">
<h3>Assignment Submissions</h3>

<?php while($row = mysqli_fetch_assoc($submissions)){ ?>

<div class="card p-4 mb-3">
    <h5><?php echo $row['assignment_title']; ?></h5>
    <p><strong>Class:</strong> <?php echo $row['class_name']; ?></p>
    <p><strong>Student:</strong> <?php echo $row['student_name']; ?></p>
    <a href="uploads/<?php echo $row['file_name']; ?>" 
       class="btn btn-primary" download>
       Download Submission
    </a>
</div>

<?php } ?>

<a href="dashboard.php">← Back to Dashboard</a>

</div>

</body>
</html>