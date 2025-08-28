<?php
include("../config/db.php");
include("../Includes/header.php");
include("../Includes/navbar.php");
include("../Includes/sidebar.php");


$error = "";
$success = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role']; // admin / vendor / staff

    // Password match check
    if($password !== $confirm_password){
        $error = "❌ Password and Confirm Password do not match!";
    } else {
        // Check if username or email already exists
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
        if(mysqli_num_rows($check) > 0){
            $error = "❌ Username or Email already exists!";
        } else {
            $password_hashed = md5($password);
            $sql = "INSERT INTO users (fullname, username, email, phone, address, password, role) 
                    VALUES ('$fullname', '$username', '$email', '$phone', '$address', '$password_hashed', '$role')";
            if(mysqli_query($conn, $sql)){
                $success = "✅ Registration successful. <a href='login.php'>Login here</a>";
            } else {
                $error = "❌ Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<link rel="stylesheet" href="../../Asset/Css/Auth/register.css">
<div class="main-content">
  <div class="register-container">
    <h2>Register New User</h2>

    <?php 
        if(!empty($error)) echo "<p class='error'>$error</p>";
        if(!empty($success)) echo "<p class='success'>$success</p>";
    ?>

    <form method="POST" action="">
        <label>Full Name:</label>
        <input type="text" name="fullname" placeholder="Full Name" required>

        <label>Username:</label>
        <input type="text" name="username" placeholder="Username" required>

        <label>Email:</label>
        <input type="email" name="email" placeholder="Email" required>

        <label>Phone Number:</label>
        <input type="text" name="phone" placeholder="Phone Number" required>

        <label>Address:</label>
        <input type="text" name="address" placeholder="Address" required>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>

        <label>Role:</label>
        <select name="role" required>
            <option value="staff">Staff</option>
            <option value="vendor">Vendor</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit">Register</button>
    </form>
  </div>
</div>

<?php include("../Includes/footer.php"); ?>
