<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != "teacher"){
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user']['id'];

// Fetch teacher classrooms
$classrooms = mysqli_query($conn,
    "SELECT * FROM classrooms WHERE teacher_id='$teacher_id'");

if(isset($_POST['create'])){
    $class_id = $_POST['class_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    mysqli_query($conn,
        "INSERT INTO assignments(classroom_id,title,description,due_date)
         VALUES('$class_id','$title','$description','$due_date')");

    $success = "Assignment Created Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Assignment</title>
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

<div class="container mt-5 col-md-7">
<div class="card p-4">

<h3>Create Assignment</h3>

<?php if(isset($success)){ ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<form method="POST">

<select name="class_id" class="form-control mb-3" required>
<option value="">Select Classroom</option>
<?php while($row = mysqli_fetch_assoc($classrooms)){ ?>
<option value="<?php echo $row['id']; ?>">
<?php echo $row['class_name']; ?>
</option>
<?php } ?>
</select>

<input type="text" name="title" class="form-control mb-3"
placeholder="Assignment Title" required>

<textarea name="description" class="form-control mb-3"
placeholder="Assignment Description" required></textarea>

<input type="date" name="due_date" class="form-control mb-3" required>

<button type="submit" name="create"
class="btn btn-success w-100">
Create Assignment
</button>

</form>

<a href="dashboard.php">← Back to Dashboard</a>

</div>
</div>

</body>
</html>