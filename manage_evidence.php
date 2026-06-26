<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
include 'db.php';

$msg = "";

if (isset($_POST['action']) && $_POST['action'] == 'insert') {
    $evid   = $conn->real_escape_string($_POST['evidenceid']);
    $type   = $conn->real_escape_string($_POST['type']);
    $caseid = $conn->real_escape_string($_POST['caseid']);
    if ($conn->query("INSERT INTO Evidence VALUES ('$evid','$type','$caseid')"))
        $msg = "<div class='msg success'>✅ Evidence added!</div>";
    else
        $msg = "<div class='msg error'>❌ ".$conn->error."</div>";
}

if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);
    $conn->query("DELETE FROM Evidence WHERE EvidenceID='$id'");
    header("Location: manage_evidence.php"); exit();
}

$cases = $conn->query("SELECT CaseID, CrimeType FROM CrimeCase");
$evids = $conn->query("SELECT e.*, c.CrimeType FROM Evidence e LEFT JOIN CrimeCase c ON e.CaseID=c.CaseID ORDER BY e.CaseID");
?>
<!DOCTYPE html>
<html>
<head>
<title>Evidence</title>
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
<h2>🔬 Evidence Management</h2>
<?php echo $msg; ?>

<div class="section">
<h3>Add Evidence</h3>
<form method="post">
<input type="hidden" name="action" value="insert">
<label>Evidence ID</label>
<input type="text" name="evidenceid" placeholder="e.g. EV001" required>
<label>Type</label>
<select name="type" required>
    <option value="">-- Select Type --</option>
    <option>Physical</option>
    <option>Digital</option>
    <option>Documentary</option>
    <option>Testimonial</option>
    <option>Forensic</option>
    <option>CCTV Footage</option>
    <option>Weapon</option>
    <option>Other</option>
</select>
<label>Linked Case</label>
<select name="caseid" required>
    <option value="">-- Select Case --</option>
    <?php while($c = $cases->fetch_assoc()): ?>
        <option value="<?php echo $c['CaseID']; ?>"><?php echo $c['CaseID'].' - '.$c['CrimeType']; ?></option>
    <?php endwhile; ?>
</select>
<br><br>
<button type="submit">Add Evidence</button>
</form>
</div>

<h3>All Evidence</h3>
<table>
<tr><th>Evidence ID</th><th>Type</th><th>Case ID</th><th>Crime Type</th><th>Action</th></tr>
<?php while($row = $evids->fetch_assoc()): ?>
<tr>
    <td><?php echo $row['EvidenceID']; ?></td>
    <td><?php echo $row['Type']; ?></td>
    <td><?php echo $row['CaseID']; ?></td>
    <td><?php echo $row['CrimeType']; ?></td>
    <td><a class="btn delete" href="manage_evidence.php?delete=<?php echo $row['EvidenceID']; ?>"
        onclick="return confirm('Delete this evidence?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>
</body>
</html>
