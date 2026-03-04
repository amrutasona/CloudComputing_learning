<?php
include("config/db.php");

if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $error = "Email already exists!";
    } else {
        mysqli_query($conn, "INSERT INTO users(name,email,password,role)
        VALUES('$name','$email','$password','$role')");
        header("Location: login.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register - CloudClass</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    height:100vh;
    background: linear-gradient(45deg,#0d6efd,#6610f2);
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

<h3 class="text-center mb-3">Create Account</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">

<input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>

<input type="email" name="email" class="form-control mb-3" placeholder="Email Address" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<select name="role" class="form-control mb-3" required>
<option value="">Select Role</option>
<option value="teacher">Teacher</option>
<option value="student">Student</option>
</select>

<button type="submit" name="register" class="btn btn-primary w-100">
Register
</button>

</form>

<div class="text-center mt-3">
Already have account? <a href="login.php">Login</a>
</div>

</div>
</div>

</body>
</html>