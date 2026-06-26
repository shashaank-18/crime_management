<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
include 'db.php';

$msg = "";

if (isset($_POST['action']) && $_POST['action'] == 'insert') {
    $firid   = $conn->real_escape_string($_POST['firid']);
    $firdate = $conn->real_escape_string($_POST['firdate']);
    $caseid  = $conn->real_escape_string($_POST['caseid']);
    if ($conn->query("INSERT INTO FIR VALUES ('$firid','$firdate','$caseid')"))
        $msg = "<div class='msg success'>✅ FIR registered!</div>";
    else
        $msg = "<div class='msg error'>❌ ".$conn->error."</div>";
}

if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);
    $conn->query("DELETE FROM FIR WHERE FIRID='$id'");
    header("Location: manage_fir.php"); exit();
}

$cases = $conn->query("SELECT CaseID, CrimeType FROM CrimeCase");
$firs  = $conn->query("SELECT f.*, c.CrimeType, c.Location FROM FIR f LEFT JOIN CrimeCase c ON f.CaseID=c.CaseID ORDER BY f.FIRDate DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>FIR Management</title>
<link rel="stylesheet" href="style.css">
<style>
label { display:block; margin-top:10px; font-weight:bold; }
input, select { width:100%; padding:8px; margin-top:4px; border-radius:6px; border:1px solid #ccc; box-sizing:border-box; }
.msg { margin:10px 0; padding:10px; border-radius:6px; }
.success { background:#e8f5e9; color:#1b5e20; }
.error   { background:#ffebee; color:#b71c1c; }
.section { background:#f5f5f5; padding:15px; border-radius:8px; margin-bottom:20px; }
</style>
</head>
<body>
<div class="container">
<h2>📄 First Information Report (FIR)</h2>
<?php echo $msg; ?>

<div class="section">
<h3>Register New FIR</h3>
<form method="post">
<input type="hidden" name="action" value="insert">
<label>FIR ID</label>
<input type="text" name="firid" placeholder="e.g. FIR001" required>
<label>FIR Date</label>
<input type="date" name="firdate" required>
<label>Linked Case</label>
<select name="caseid" required>
    <option value="">-- Select Case --</option>
    <?php while($c = $cases->fetch_assoc()): ?>
        <option value="<?php echo $c['CaseID']; ?>"><?php echo $c['CaseID'].' - '.$c['CrimeType']; ?></option>
    <?php endwhile; ?>
</select>
<br><br>
<button type="submit">Register FIR</button>
</form>
</div>

<h3>All FIRs</h3>
<table>
<tr><th>FIR ID</th><th>FIR Date</th><th>Case ID</th><th>Crime Type</th><th>Location</th><th>Action</th></tr>
<?php while($row = $firs->fetch_assoc()): ?>
<tr>
    <td><?php echo $row['FIRID']; ?></td>
    <td><?php echo $row['FIRDate']; ?></td>
    <td><?php echo $row['CaseID']; ?></td>
    <td><?php echo $row['CrimeType']; ?></td>
    <td><?php echo $row['Location']; ?></td>
    <td><a class="btn delete" href="manage_fir.php?delete=<?php echo $row['FIRID']; ?>"
        onclick="return confirm('Delete this FIR?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>
</body>
</html>
