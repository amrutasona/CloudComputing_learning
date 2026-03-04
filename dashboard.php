<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// ================= COUNT LOGIC =================

if($user['role'] == "teacher"){

    $class_count_query = mysqli_query($conn,
        "SELECT COUNT(*) as total FROM classrooms WHERE teacher_id='$user_id'");
    $class_count = mysqli_fetch_assoc($class_count_query)['total'];

    $material_count_query = mysqli_query($conn,
        "SELECT COUNT(materials.id) as total
         FROM materials
         JOIN classrooms ON materials.classroom_id = classrooms.id
         WHERE classrooms.teacher_id='$user_id'");
    $material_count = mysqli_fetch_assoc($material_count_query)['total'];

}

if($user['role'] == "student"){

    $class_count_query = mysqli_query($conn,
        "SELECT COUNT(*) as total FROM enrollments WHERE student_id='$user_id'");
    $class_count = mysqli_fetch_assoc($class_count_query)['total'];

    $material_count_query = mysqli_query($conn,
        "SELECT COUNT(materials.id) as total
         FROM materials
         JOIN enrollments ON materials.classroom_id = enrollments.classroom_id
         WHERE enrollments.student_id='$user_id'");
    $material_count = mysqli_fetch_assoc($material_count_query)['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard - CloudClass</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
}
.sidebar{
    height:100vh;
    background: linear-gradient(45deg,#0d6efd,#6610f2);
    color:white;
    padding:20px;
}
.sidebar a{
    color:white;
    text-decoration:none;
    display:block;
    padding:10px;
    border-radius:8px;
    margin-bottom:5px;
}
.sidebar a:hover{
    background:rgba(255,255,255,0.2);
}
.card{
    border:none;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}
</style>
</head>

<body>

<div class="container-fluid">
<div class="row">

<!-- Sidebar -->
<div class="col-md-3 sidebar">
    <h3>CloudClass</h3>
    <hr>

    <a href="dashboard.php">Dashboard</a>

    <?php if($user['role']=="teacher"){ ?>
        <a href="create_class.php">Create Classroom</a>
        <a href="upload_material.php">Upload Material</a>
        <a href="create_assignment.php">Create Assignment</a>
        <a href="view_submissions.php">View Submissions</a>  <!-- ADDED HERE -->
    <?php } ?>

    <?php if($user['role']=="student"){ ?>
        <a href="join_class.php">Join Classroom</a>
        <a href="view_materials.php">View Materials</a>
        <a href="submit_assignment.php">Submit Assignment</a>
    <?php } ?>

    <a href="logout.php">Logout</a>
</div>

<!-- Main Content -->
<div class="col-md-9 p-4">

    <h2>Welcome, <?php echo $user['name']; ?> 👋</h2>
    <p class="text-muted">Role: <?php echo ucfirst($user['role']); ?></p>

    <div class="row mt-4">

        <div class="col-md-4 mb-4">
            <div class="card p-4">
                <h5>Total Classes</h5>
                <h3><?php echo $class_count; ?></h3>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card p-4">
                <h5>Materials</h5>
                <h3><?php echo $material_count; ?></h3>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card p-4">
                <h5>Activity</h5>
                <h3>Active</h3>
            </div>
        </div>

    </div>

</div>

</div>
</div>

</body>
</html>