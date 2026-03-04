<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != "student"){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user']['id'];

// Get classrooms student joined
$classes = mysqli_query($conn,
    "SELECT classrooms.id, classrooms.class_name 
     FROM enrollments 
     JOIN classrooms ON enrollments.classroom_id = classrooms.id
     WHERE enrollments.student_id = '$student_id'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Study Materials</title>
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
<h3 class="mb-4">Available Study Materials</h3>

<?php while($class = mysqli_fetch_assoc($classes)){ ?>

<div class="card p-4 mb-4">
<h5><?php echo $class['class_name']; ?></h5>
<hr>

<?php
$class_id = $class['id'];

$materials = mysqli_query($conn,
    "SELECT * FROM materials WHERE classroom_id = '$class_id'");

if(mysqli_num_rows($materials) > 0){
    while($file = mysqli_fetch_assoc($materials)){
        echo "<a class='btn btn-outline-primary mb-2 me-2' 
              href='uploads/".$file['file_name']."' 
              download>
              ".$file['file_name']."
              </a>";
    }
} else {
    echo "<p class='text-muted'>No materials uploaded yet.</p>";
}
?>

</div>

<?php } ?>

<a href="dashboard.php">← Back to Dashboard</a>

</div>

</body>
</html>