<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login - Crime Management</title>
<style>

/* FULL PAGE */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #141e30, #243b55);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* LOGIN CARD */
.login-box {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 40px;
    border-radius: 15px;
    width: 320px;
    text-align: center;
    color: white;
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
}

/* TITLE */
.login-box h2 {
    margin-bottom: 20px;
}

/* INPUTS */
.input-box {
    margin: 15px 0;
}

.input-box input {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 8px;
    outline: none;
}

/* BUTTON */
button {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 8px;
    background: #00c6ff;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background: #0072ff;
}

/* ERROR */
.error {
    color: #ff6b6b;
    margin-top: 10px;
}

</style>
</head>

<body>

<div class="login-box">
    <h2>🔐 Crime System Login</h2>

    <form method="post">
        <div class="input-box">
            <input type="text" name="username" placeholder="👤 Username" required>
        </div>

        <div class="input-box">
            <input type="password" name="password" placeholder="🔑 Password" required>
        </div>

        <button type="submit">Login</button>
        <p style="margin-top:15px;">
Don't have an account? 
<a href="signup.php" style="color:#00c6ff;">Signup here</a>
</p>
    </form>

    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
</div>

</body>
</html>