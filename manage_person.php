<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
include 'db.php';

$msg = "";

// INSERT PERSON
if (isset($_POST['action']) && $_POST['action'] == 'insert') {
    $id      = $conn->real_escape_string($_POST['personid']);
    $name    = $conn->real_escape_string($_POST['name']);
    $dob     = $conn->real_escape_string($_POST['dob']);
    $gender  = $conn->real_escape_string($_POST['gender']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone   = $conn->real_escape_string($_POST['phone']);
    $role    = $conn->real_escape_string($_POST['role']);
    if ($conn->query("INSERT INTO Person VALUES ('$id','$name','$dob','$gender','$address','$phone','$role')"))
        $msg = "<div class='msg success'>✅ Person added!</div>";
    else
        $msg = "<div class='msg error'>❌ ".$conn->error."</div>";
}

// LINK PERSON TO CASE
if (isset($_POST['action']) && $_POST['action'] == 'link') {
    $caseid   = $conn->real_escape_string($_POST['caseid']);
    $personid = $conn->real_escape_string($_POST['personid']);
    $type     = $conn->real_escape_string($_POST['type']);
    if ($conn->query("INSERT IGNORE INTO CasePerson VALUES ('$caseid','$personid','$type')"))
        $msg = "<div class='msg success'>✅ Person linked to case!</div>";
    else
        $msg = "<div class='msg error'>❌ ".$conn->error."</div>";
}

// DELETE PERSON
if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);
    $conn->query("DELETE FROM CasePerson WHERE PersonID='$id'");
    $conn->query("DELETE FROM Person WHERE PersonID='$id'");
    header("Location: manage_person.php"); exit();
}

$persons = $conn->query("SELECT * FROM Person");
$cases   = $conn->query("SELECT CaseID, CrimeType FROM CrimeCase");
?>
<!DOCTYPE html>
<html>
<head>
<title>Persons</title>
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
.tab-active { background:#880e4f; color:white; }
.tab-inactive { background:#ddd; color:#333; }
.tab-content { display:none; }
.tab-content.active { display:block; }
.badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:12px; font-weight:bold; color:white; }
.Victim   { background:#c62828; }
.Suspect  { background:#e65100; }
.Witness  { background:#1565c0; }
</style>
</head>
<body>
<div class="container">
<h2>👤 Persons — Victims / Suspects / Witnesses</h2>
<?php echo $msg; ?>

<div class="tabs">
    <button class="tab-btn tab-active" onclick="showTab('add')">Add Person</button>
    <button class="tab-btn tab-inactive" onclick="showTab('link')">Link to Case</button>
</div>

<div id="tab-add" class="tab-content active section">
<h3>Add New Person</h3>
<form method="post">
<input type="hidden" name="action" value="insert">
<label>Person ID</label>
<input type="text" name="personid" placeholder="e.g. P001" required>
<label>Name</label>
<input type="text" name="name" placeholder="Full Name" required>
<label>Date of Birth</label>
<input type="date" name="dob">
<label>Gender</label>
<select name="gender">
    <option>Male</option>
    <option>Female</option>
    <option>Other</option>
</select>
<label>Address</label>
<input type="text" name="address" placeholder="Address">
<label>Phone</label>
<input type="text" name="phone" placeholder="Phone number">
<label>Role</label>
<select name="role" required>
    <option>Victim</option>
    <option>Suspect</option>
    <option>Witness</option>
    <option>Accused</option>
</select>
<br><br>
<button type="submit">Add Person</button>
</form>
</div>

<div id="tab-link" class="tab-content section">
<h3>Link Person to Case (Case Person)</h3>
<form method="post">
<input type="hidden" name="action" value="link">
<label>Case</label>
<select name="caseid" required>
    <option value="">-- Select Case --</option>
    <?php while($c = $cases->fetch_assoc()): ?>
        <option value="<?php echo $c['CaseID']; ?>"><?php echo $c['CaseID'].' - '.$c['CrimeType']; ?></option>
    <?php endwhile; ?>
</select>
<label>Person</label>
<select name="personid" required>
    <option value="">-- Select Person --</option>
    <?php
    $persons->data_seek(0);
    while($p = $persons->fetch_assoc()): ?>
        <option value="<?php echo $p['PersonID']; ?>"><?php echo $p['PersonID'].' - '.$p['Name'].' ('.$p['Role'].')'; ?></option>
    <?php endwhile; ?>
</select>
<label>Type in this Case</label>
<select name="type">
    <option>Victim</option>
    <option>Suspect</option>
    <option>Witness</option>
    <option>Accused</option>
</select>
<br><br>
<button type="submit">Link Person</button>
</form>
</div>

<h3>All Persons</h3>
<table>
<tr><th>Person ID</th><th>Name</th><th>DOB</th><th>Gender</th><th>Phone</th><th>Role</th><th>Action</th></tr>
<?php
$persons->data_seek(0);
while($row = $persons->fetch_assoc()):
?>
<tr>
    <td><?php echo $row['PersonID']; ?></td>
    <td><?php echo $row['Name']; ?></td>
    <td><?php echo $row['DOB']; ?></td>
    <td><?php echo $row['Gender']; ?></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><span class="badge <?php echo $row['Role']; ?>"><?php echo $row['Role']; ?></span></td>
    <td><a class="btn delete" href="manage_person.php?delete=<?php echo $row['PersonID']; ?>"
        onclick="return confirm('Delete this person?')">Delete</a></td>
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
