<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>Insert Case</title>
<link rel="stylesheet" href="style.css">
<style>
label { display:block; margin-top:12px; font-weight:bold; }
input, select { width:100%; padding:9px; margin-top:4px; border-radius:6px; border:1px solid #ccc; box-sizing:border-box; }
.msg { margin-top:15px; padding:10px; border-radius:6px; }
.success { background:#e8f5e9; color:#1b5e20; }
.error   { background:#ffebee; color:#b71c1c; }
</style>
</head>
<body>
<div class="container">
<h2>➕ Add Crime Case</h2>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $caseid    = $conn->real_escape_string($_POST['caseid']);
    $caseno    = $conn->real_escape_string($_POST['caseno']);
    $crimetype = $conn->real_escape_string($_POST['crimetype']);
    $date      = $conn->real_escape_string($_POST['date']);
    $location  = $conn->real_escape_string($_POST['location']);
    $status    = $conn->real_escape_string($_POST['status']);
    $stationid = $conn->real_escape_string($_POST['stationid']);

    $sql = "INSERT INTO CrimeCase (CaseID, CaseNo, CrimeType, Date, Location, Status, StationID)
            VALUES ('$caseid','$caseno','$crimetype','$date','$location','$status','$stationid')";

    if ($conn->query($sql)) {
        echo "<div class='msg success'>✅ Case inserted successfully!</div>";
    } else {
        echo "<div class='msg error'>❌ Error: " . $conn->error . "</div>";
    }
}

$stations = $conn->query("SELECT StationID, Name FROM PoliceStation");
?>

<form method="post">

<label>Case ID</label>
<input type="text" name="caseid" placeholder="e.g. C001" required>

<label>Case Number</label>
<input type="text" name="caseno" placeholder="e.g. FIR/2024/001" required>

<label>Crime Type</label>
<select name="crimetype" required>
    <option value="">-- Select Crime Type --</option>
    <option>Murder</option>
    <option>Kidnapping</option>
    <option>Robbery</option>
    <option>Assault</option>
    <option>Theft</option>
    <option>Fraud</option>
    <option>Cybercrime</option>
    <option>Other</option>
</select>

<label>Date</label>
<input type="date" name="date" required>

<label>Location</label>
<input type="text" name="location" placeholder="Crime location" required>

<label>Status</label>
<select name="status" required>
    <option value="Open">Open</option>
    <option value="Investigating">Investigating</option>
    <option value="Closed">Closed</option>
</select>

<label>Police Station</label>
<select name="stationid" required>
    <option value="">-- Select Station --</option>
    <?php while($s = $stations->fetch_assoc()): ?>
        <option value="<?php echo $s['StationID']; ?>"><?php echo $s['StationID'].' - '.$s['Name']; ?></option>
    <?php endwhile; ?>
</select>

<br><br>
<button type="submit">Insert Case</button>
</form>

<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>
</body>
</html>
