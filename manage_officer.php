<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
include 'db.php';

$msg = "";

// INSERT OFFICER
if (isset($_POST['action']) && $_POST['action'] == 'insert') {
    $id        = $conn->real_escape_string($_POST['officerid']);
    $name      = $conn->real_escape_string($_POST['name']);
    $rank      = $conn->real_escape_string($_POST['rank']);
    $phone     = $conn->real_escape_string($_POST['phone']);
    $stationid = $conn->real_escape_string($_POST['stationid']);
    if ($conn->query("INSERT INTO PoliceOfficer VALUES ('$id','$name','$rank','$phone','$stationid')"))
        $msg = "<div class='msg success'>✅ Officer added!</div>";
    else
        $msg = "<div class='msg error'>❌ ".$conn->error."</div>";
}

// ASSIGN TO CASE
if (isset($_POST['action']) && $_POST['action'] == 'assign') {
    $caseid    = $conn->real_escape_string($_POST['caseid']);
    $officerid = $conn->real_escape_string($_POST['officerid']);
    $role      = $conn->real_escape_string($_POST['role']);
    if ($conn->query("INSERT IGNORE INTO CaseOfficer VALUES ('$caseid','$officerid','$role')"))
        $msg = "<div class='msg success'>✅ Officer assigned to case!</div>";
    else
        $msg = "<div class='msg error'>❌ ".$conn->error."</div>";
}

// DELETE OFFICER
if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);
    $conn->query("DELETE FROM CaseOfficer WHERE OfficerID='$id'");
    $conn->query("DELETE FROM PoliceOfficer WHERE OfficerID='$id'");
    header("Location: manage_officer.php"); exit();
}

$stations = $conn->query("SELECT * FROM PoliceStation");
$cases    = $conn->query("SELECT CaseID, CrimeType FROM CrimeCase");
$officers = $conn->query("SELECT po.*, ps.Name AS StationName FROM PoliceOfficer po LEFT JOIN PoliceStation ps ON po.StationID=ps.StationID");
?>
<!DOCTYPE html>
<html>
<head>
<title>Police Officers</title>
<link rel="stylesheet" href="style.css">
<style>
label { display:block; margin-top:10px; font-weight:bold; }
input, select { width:100%; padding:8px; margin-top:4px; border-radius:6px; border:1px solid #ccc; box-sizing:border-box; }
.msg { margin:10px 0; padding:10px; border-radius:6px; }
.success { background:#e8f5e9; color:#1b5e20; }
.error   { background:#ffebee; color:#b71c1c; }
.section { background:#f5f5f5; padding:15px; border-radius:8px; margin-bottom:20px; }
.tabs { display:flex; gap:10px; margin-bottom:15px; }
.tab-btn { padding:8px 18px; border:none; border-radius:6px; cursor:pointer; font-size:14px; font-weight:bold; }
.tab-active { background:#0d47a1; color:white; }
.tab-inactive { background:#ddd; color:#333; }
.tab-content { display:none; }
.tab-content.active { display:block; }
</style>
</head>
<body>
<div class="container">
<h2>🚔 Police Officers</h2>
<?php echo $msg; ?>

<div class="tabs">
    <button class="tab-btn tab-active" onclick="showTab('add')">Add Officer</button>
    <button class="tab-btn tab-inactive" onclick="showTab('assign')">Assign to Case</button>
</div>

<div id="tab-add" class="tab-content active section">
<h3>Add New Officer</h3>
<form method="post">
<input type="hidden" name="action" value="insert">
<label>Officer ID</label>
<input type="text" name="officerid" placeholder="e.g. OFF001" required>
<label>Name</label>
<input type="text" name="name" placeholder="Full Name" required>
<label>Rank</label>
<select name="rank" required>
    <option value="">-- Select Rank --</option>
    <option>Inspector</option>
    <option>Sub-Inspector</option>
    <option>Constable</option>
    <option>DSP</option>
    <option>SP</option>
    <option>DCP</option>
    <option>Commissioner</option>
</select>
<label>Phone</label>
<input type="text" name="phone" placeholder="Phone number">
<label>Station</label>
<select name="stationid" required>
    <option value="">-- Select Station --</option>
    <?php
    $stations->data_seek(0);
    while($s = $stations->fetch_assoc()):?>
        <option value="<?php echo $s['StationID']; ?>"><?php echo $s['StationID'].' - '.$s['Name']; ?></option>
    <?php endwhile; ?>
</select>
<br><br>
<button type="submit">Add Officer</button>
</form>
</div>

<div id="tab-assign" class="tab-content section">
<h3>Assign Officer to Case (Case Officer)</h3>
<form method="post">
<input type="hidden" name="action" value="assign">
<label>Case</label>
<select name="caseid" required>
    <option value="">-- Select Case --</option>
    <?php while($c = $cases->fetch_assoc()): ?>
        <option value="<?php echo $c['CaseID']; ?>"><?php echo $c['CaseID'].' - '.$c['CrimeType']; ?></option>
    <?php endwhile; ?>
</select>
<label>Officer</label>
<select name="officerid" required>
    <option value="">-- Select Officer --</option>
    <?php
    $officers->data_seek(0);
    while($o = $officers->fetch_assoc()): ?>
        <option value="<?php echo $o['OfficerID']; ?>"><?php echo $o['OfficerID'].' - '.$o['Name'].' ('.$o['Rank'].')'; ?></option>
    <?php endwhile; ?>
</select>
<label>Role in Case</label>
<select name="role">
    <option>Investigating Officer</option>
    <option>Arresting Officer</option>
    <option>Supervising Officer</option>
    <option>Witness</option>
</select>
<br><br>
<button type="submit">Assign</button>
</form>
</div>

<h3>All Officers</h3>
<table>
<tr><th>Officer ID</th><th>Name</th><th>Rank</th><th>Phone</th><th>Station</th><th>Action</th></tr>
<?php
$officers->data_seek(0);
while($row = $officers->fetch_assoc()):
?>
<tr>
    <td><?php echo $row['OfficerID']; ?></td>
    <td><?php echo $row['Name']; ?></td>
    <td><?php echo $row['Rank']; ?></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><?php echo $row['StationName'] ?? $row['StationID']; ?></td>
    <td><a class="btn delete" href="manage_officer.php?delete=<?php echo $row['OfficerID']; ?>"
        onclick="return confirm('Delete this officer?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

<script>
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => { el.classList.remove('tab-active'); el.classList.add('tab-inactive'); });
    document.getElementById('tab-' + tab).classList.add('active');
    event.target.classList.add('tab-active');
    event.target.classList.remove('tab-inactive');
}
</script>
</body>
</html>
