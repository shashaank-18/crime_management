<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
include 'db.php';

$id = $conn->real_escape_string($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $crime     = $conn->real_escape_string($_POST['crime']);
    $caseno    = $conn->real_escape_string($_POST['caseno']);
    $date      = $conn->real_escape_string($_POST['date']);
    $location  = $conn->real_escape_string($_POST['location']);
    $status    = $conn->real_escape_string($_POST['status']);
    $stationid = $conn->real_escape_string($_POST['stationid']);

    $conn->query("UPDATE CrimeCase 
                  SET CrimeType='$crime', CaseNo='$caseno', Date='$date',
                      Location='$location', Status='$status', StationID='$stationid'
                  WHERE CaseID='$id'");

    echo "<script>alert('Case Updated Successfully'); window.location='view_cases.php?mode=all';</script>";
}

$row = $conn->query("SELECT * FROM CrimeCase WHERE CaseID='$id'")->fetch_assoc();
$stations = $conn->query("SELECT * FROM PoliceStation");
?>
<!DOCTYPE html>
<html>
<head>
<title>Update Case</title>
<link rel="stylesheet" href="style.css">
<style>
label { display:block; margin-top:12px; font-weight:bold; }
input, select { width:100%; padding:8px; margin-top:4px; border-radius:6px; border:1px solid #ccc; box-sizing:border-box; }
</style>
</head>
<body>
<div class="container">
<h2>✏️ Update Case Details</h2>

<p><b>Case ID:</b> <?php echo $row['CaseID']; ?></p>

<form method="post">

<label>Case Number</label>
<input type="text" name="caseno" value="<?php echo $row['CaseNo']; ?>" required>

<label>Crime Type</label>
<select name="crime" required>
    <?php foreach(['Murder','Kidnapping','Robbery','Assault','Theft','Fraud','Cybercrime','Other'] as $ct): ?>
        <option <?php if($row['CrimeType']==$ct) echo 'selected'; ?>><?php echo $ct; ?></option>
    <?php endforeach; ?>
</select>

<label>Date</label>
<input type="date" name="date" value="<?php echo $row['Date']; ?>" required>

<label>Location</label>
<input type="text" name="location" value="<?php echo $row['Location']; ?>" required>

<label>Status</label>
<select name="status">
    <?php foreach(['Open','Investigating','Closed'] as $s): ?>
        <option <?php if($row['Status']==$s) echo 'selected'; ?>><?php echo $s; ?></option>
    <?php endforeach; ?>
</select>

<label>Police Station</label>
<select name="stationid" required>
    <option value="">-- Select Station --</option>
    <?php while($st = $stations->fetch_assoc()): ?>
        <option value="<?php echo $st['StationID']; ?>" 
            <?php if($row['StationID']==$st['StationID']) echo 'selected'; ?>>
            <?php echo $st['StationID'].' - '.$st['Name']; ?>
        </option>
    <?php endwhile; ?>
</select>

<br><br>
<button type="submit">Update Case</button>
</form>

<br>
<a href="view_cases.php?mode=all" class="back-btn">⬅ Back to View Cases</a>
</div>
</body>
</html>
