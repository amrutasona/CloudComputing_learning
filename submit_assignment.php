<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != "student"){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user']['id'];

// Get assignments from joined classes
$assignments = mysqli_query($conn,
    "SELECT assignments.*, classrooms.class_name
     FROM assignments
     JOIN classrooms ON assignments.classroom_id = classrooms.id
     JOIN enrollments ON enrollments.classroom_id = classrooms.id
     WHERE enrollments.student_id = '$student_id'");

if(isset($_POST['submit'])){
    $assignment_id = $_POST['assignment_id'];
    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];

    move_uploaded_file($tmp, "uploads/".$file);

    mysqli_query($conn,
        "INSERT INTO submissions(assignment_id, student_id, file_name)
         VALUES('$assignment_id','$student_id','$file')");

    $success = "Assignment Submitted Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Submit Assignment</title>
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
<h3>Submit Assignment</h3>

<?php if(isset($success)){ ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<?php while($row = mysqli_fetch_assoc($assignments)){ ?>

<div class="card p-4 mb-4">
<h5><?php echo $row['title']; ?></h5>
<p><strong>Class:</strong> <?php echo $row['class_name']; ?></p>
<p><?php echo $row['description']; ?></p>
<p><strong>Due Date:</strong> <?php echo $row['due_date']; ?></p>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="assignment_id" value="<?php echo $row['id']; ?>">
    <input type="file" name="file" class="form-control mb-2" required>
    <button type="submit" name="submit" class="btn btn-primary">
        Submit Assignment
    </button>
</form>

</div>

<?php } ?>

<a href="dashboard.php">← Back to Dashboard</a>

</div>
</body>
</html>