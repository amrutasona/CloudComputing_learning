<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != "student"){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user']['id'];

if(isset($_GET['join'])){
    $class_id = $_GET['join'];

    // prevent duplicate join
    $check = mysqli_query($conn,
        "SELECT * FROM enrollments 
         WHERE student_id='$student_id' 
         AND classroom_id='$class_id'");

    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn,
            "INSERT INTO enrollments(student_id,classroom_id)
             VALUES('$student_id','$class_id')");
        $success = "Successfully Joined!";
    } else {
        $success = "Already Joined!";
    }
}

$classes = mysqli_query($conn,"SELECT * FROM classrooms");
?>

<!DOCTYPE html>
<html>
<head>
<title>Join Classroom</title>
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
<h3>Available Classrooms</h3>

<?php if(isset($success)){ ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<?php while($row = mysqli_fetch_assoc($classes)){ ?>
    <div class="card p-3 mb-3">
        <h5><?php echo $row['class_name']; ?></h5>
        <a href="?join=<?php echo $row['id']; ?>" class="btn btn-primary">
            Join
        </a>
    </div>
<?php } ?>

<a href="dashboard.php">← Back</a>

</div>
</body>
</html>