<?php
session_start();
include("config/db.php");

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login - CloudClass</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    height:100vh;
    background: linear-gradient(45deg,#6610f2,#0d6efd);
    display:flex;
    justify-content:center;
    align-items:center;
}
.card{
    border:none;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.2);
}
</style>
</head>

<body>

<div class="col-md-4">
<div class="card p-4">

<h3 class="text-center mb-3">Welcome Back</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">

<input type="email" name="email" class="form-control mb-3" placeholder="Email Address" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button type="submit" name="login" class="btn btn-primary w-100">
Login
</button>

</form>

<div class="text-center mt-3">
Don't have an account? <a href="register.php">Register</a>
</div>

</div>
</div>

</body>
</html>