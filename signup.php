<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "INSERT INTO users (username, password) 
            VALUES ('$user', '$pass')";

    if ($conn->query($sql)) {
        echo "<script>alert('Signup Successful! Please Login'); window.location='index.php';</script>";
    } else {
        echo "<div style='color:red;'>Username already exists!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Signup</title>
<style>
body {
    margin: 0;
    font-family: Arial;
    background: linear-gradient(135deg, #1d2671, #c33764);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.box {
    background: white;
    padding: 30px;
    border-radius: 10px;
    width: 300px;
    text-align: center;
}

input, button {
    width: 90%;
    padding: 10px;
    margin: 10px;
}

button {
    background: #1d2671;
    color: white;
    border: none;
}
</style>
</head>

<body>

<div class="box">
<h2>Create Account</h2>

<form method="post">
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Signup</button>
</form>

<br>
<a href="index.php">← Back to Login</a>

</div>

</body>
</html>