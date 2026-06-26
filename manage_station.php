<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
include 'db.php';

$msg = "";

// INSERT
if (isset($_POST['action']) && $_POST['action'] == 'insert') {
    $id      = $conn->real_escape_string($_POST['stationid']);
    $name    = $conn->real_escape_string($_POST['name']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone   = $conn->real_escape_string($_POST['phone']);
    if ($conn->query("INSERT INTO PoliceStation VALUES ('$id','$name','$address','$phone')"))
        $msg = "<div class='msg success'>✅ Station added!</div>";
    else
        $msg = "<div class='msg error'>❌ ".$conn->error."</div>";
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);
    $conn->query("DELETE FROM PoliceStation WHERE StationID='$id'");
    header("Location: manage_station.php"); exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Police Stations</title>
<link rel="stylesheet" href="style.css">
<style>
label { display:block; margin-top:10px; font-weight:bold; }
input { width:100%; padding:8px; margin-top:4px; border-radius:6px; border:1px solid #ccc; box-sizing:border-box; }
.msg { margin:10px 0; padding:10px; border-radius:6px; }
.success { background:#e8f5e9; color:#1b5e20; }
.error   { background:#ffebee; color:#b71c1c; }
.section { background:#f5f5f5; padding:15px; border-radius:8px; margin-bottom:20px; }
</style>
</head>
<body>
<div class="container">
<h2>🏢 Manage Police Stations</h2>
<?php echo $msg; ?>

<div class="section">
<h3>Add New Station</h3>
<form method="post">
<input type="hidden" name="action" value="insert">
<label>Station ID</label>
<input type="text" name="stationid" placeholder="e.g. ST001" required>
<label>Name</label>
<input type="text" name="name" placeholder="Station Name" required>
<label>Address</label>
<input type="text" name="address" placeholder="Address" required>
<label>Phone</label>
<input type="text" name="phone" placeholder="Phone number">
<br><br>
<button type="submit">Add Station</button>
</form>
</div>

<h3>All Stations</h3>
<table>
<tr><th>Station ID</th><th>Name</th><th>Address</th><th>Phone</th><th>Action</th></tr>
<?php
$res = $conn->query("SELECT * FROM PoliceStation");
while($row = $res->fetch_assoc()):
?>
<tr>
    <td><?php echo $row['StationID']; ?></td>
    <td><?php echo $row['Name']; ?></td>
    <td><?php echo $row['Address']; ?></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><a class="btn delete" href="manage_station.php?delete=<?php echo $row['StationID']; ?>"
        onclick="return confirm('Delete this station?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>
</body>
</html>
