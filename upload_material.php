<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != "teacher"){
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user']['id'];

// DEBUG: Check teacher id
// echo "Teacher ID: " . $teacher_id;

// Fetch classrooms of logged-in teacher
$classrooms = mysqli_query($conn, 
    "SELECT * FROM classrooms WHERE teacher_id = '$teacher_id'");

if(isset($_POST['upload'])){
    $class_id = $_POST['class_id'];

    if(empty($class_id)){
        $error = "Please select classroom!";
    } else {
        $file = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];

        move_uploaded_file($tmp, "uploads/".$file);

        mysqli_query($conn,
            "INSERT INTO materials(classroom_id,file_name)
             VALUES('$class_id','$file')");

        $success = "Material Uploaded Successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Material</title>
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

<h3 class="mb-3">Upload Study Material</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<?php if(isset($success)){ ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<form method="POST" enctype="multipart/form-data">

<select name="class_id" class="form-control mb-3" required>
<option value="">Select Classroom</option>

<?php
if(mysqli_num_rows($classrooms) > 0){
    while($row = mysqli_fetch_assoc($classrooms)){
        echo "<option value='".$row['id']."'>".$row['class_name']."</option>";
    }
} else {
    echo "<option disabled>No Classroom Found</option>";
}
?>

</select>

<input type="file" name="file" class="form-control mb-3" required>

<button type="submit" name="upload" class="btn btn-primary w-100">
Upload
</button>

</form>

<div class="mt-3">
<a href="dashboard.php">← Back to Dashboard</a>
</div>

</div>
</div>

</body>
</html>