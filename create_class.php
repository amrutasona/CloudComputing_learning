<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != "teacher"){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

if(isset($_POST['create'])){
    $class_name = $_POST['class_name'];
    $subject = $_POST['subject'];
    $teacher_id = $user['id'];

    mysqli_query($conn,"INSERT INTO classrooms(teacher_id,class_name,subject)
    VALUES('$teacher_id','$class_name','$subject')");

    $success = "Classroom Created Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Classroom</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
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

<div class="container mt-5 col-md-6">
<div class="card p-4">

<h3 class="mb-3">Create New Classroom</h3>

<?php if(isset($success)){ ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<form method="POST">

<input type="text" name="class_name" class="form-control mb-3"
placeholder="Class Name" required>

<input type="text" name="subject" class="form-control mb-3"
placeholder="Subject Name" required>

<button type="submit" name="create"
class="btn btn-success w-100">
Create Classroom
</button>

</form>

<div class="mt-3">
<a href="dashboard.php">← Back to Dashboard</a>
</div>

</div>
</div>

</body>
</html>