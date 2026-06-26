<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
include 'db.php';

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'single';

$result = $conn->query("SELECT cc.*, ps.Name AS StationName 
                        FROM CrimeCase cc 
                        LEFT JOIN PoliceStation ps ON cc.StationID=ps.StationID");
$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$total = count($data);
$pos = isset($_GET['pos']) ? $_GET['pos'] : 0;
if ($pos == "last") $pos = $total - 1;
if ($pos < 0) $pos = 0;
if ($pos >= $total && $total > 0) $pos = $total - 1;

$current = ($total > 0) ? $data[$pos] : null;
?>
<!DOCTYPE html>
<html>
<head>
<title>View Cases</title>
<link rel="stylesheet" href="style.css">
<style>
.nav-btn { padding:8px 12px; margin:4px; background:#2a5298; color:white; text-decoration:none; border-radius:5px; display:inline-block; }
.toggle-btn { background:#4CAF50; }
.detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:15px; margin:15px 0; }
.detail-box { background:#f9f9f9; padding:12px 15px; border-radius:8px; border-left:4px solid #2a5298; }
.detail-box h4 { margin:0 0 8px 0; color:#2a5298; font-size:13px; text-transform:uppercase; letter-spacing:1px; }
.detail-box p { margin:4px 0; font-size:14px; }
.badge { display:inline-block; padding:3px 10px; border-radius:12px; font-size:12px; font-weight:bold; color:white; }
.Open          { background:#e65100; }
.Closed        { background:#2e7d32; }
.Investigating { background:#1565c0; }
.priority-high   { color:#c62828; font-weight:bold; }
.priority-medium { color:#e65100; font-weight:bold; }
.priority-low    { color:#2e7d32; font-weight:bold; }
.sub-table { width:100%; border-collapse:collapse; font-size:13px; margin-top:5px; }
.sub-table td, .sub-table th { padding:5px 8px; border:1px solid #ddd; text-align:left; }
.sub-table th { background:#2a5298; color:white; }
</style>
</head>
<body>

<h2>Crime Cases</h2>

<div class="container">

<a class="nav-btn toggle-btn" href="view_cases.php?mode=all">📊 View All</a>
<a class="nav-btn toggle-btn" href="view_cases.php">🔄 Single View</a>
<br><br>

<?php

function getPriority($type) {
    if (in_array($type, ['Murder','Kidnapping'])) return ['🔴 High','priority-high'];
    if (in_array($type, ['Robbery','Assault']))   return ['🟠 Medium','priority-medium'];
    if (in_array($type, ['Theft','Fraud']))        return ['🟢 Low','priority-low'];
    return ['⚪ Normal',''];
}

// ===== ALL VIEW =====
if ($mode == "all") {
    echo "<table>
    <tr>
        <th>Case ID</th><th>Case No</th><th>Crime Type</th><th>Date</th>
        <th>Location</th><th>Status</th><th>Priority</th><th>Station</th><th>Action</th>
    </tr>";

    foreach ($data as $row) {
        [$pri, $cls] = getPriority($row['CrimeType']);
        echo "<tr>
            <td>{$row['CaseID']}</td>
            <td>{$row['CaseNo']}</td>
            <td>{$row['CrimeType']}</td>
            <td>{$row['Date']}</td>
            <td>{$row['Location']}</td>
            <td><span class='badge {$row['Status']}'>{$row['Status']}</span></td>
            <td class='{$cls}'>{$pri}</td>
            <td>{$row['StationName']}</td>
            <td>
                <a class='btn update' href='update_case.php?id={$row['CaseID']}'>Update</a>
                <a class='btn delete' href='delete_case.php?id={$row['CaseID']}'
                   onclick=\"return confirm('Delete case {$row['CaseID']}?')\">Delete</a>
            </td>
        </tr>";
    }
    echo "</table>";
}

// ===== SINGLE VIEW =====
else {
    if ($current != null) {
        [$pri, $cls] = getPriority($current['CrimeType']);

        // Case Officers
        $officers = $conn->query("SELECT co.Role, po.Name, po.Rank 
                                  FROM CaseOfficer co 
                                  JOIN PoliceOfficer po ON co.OfficerID=po.OfficerID 
                                  WHERE co.CaseID='{$current['CaseID']}'");

        // Case Persons
        $persons = $conn->query("SELECT cp.Type, p.Name, p.Gender, p.Phone 
                                 FROM CasePerson cp 
                                 JOIN Person p ON cp.PersonID=p.PersonID 
                                 WHERE cp.CaseID='{$current['CaseID']}'");

        // FIR
        $fir = $conn->query("SELECT * FROM FIR WHERE CaseID='{$current['CaseID']}'")->fetch_assoc();

        // Evidence
        $evidences = $conn->query("SELECT * FROM Evidence WHERE CaseID='{$current['CaseID']}'");

        echo "<div class='detail-grid'>";

        echo "<div class='detail-box'>
            <h4>📋 Case Details</h4>
            <p><b>Case ID:</b> {$current['CaseID']}</p>
            <p><b>Case No:</b> {$current['CaseNo']}</p>
            <p><b>Crime Type:</b> {$current['CrimeType']}</p>
            <p><b>Date:</b> {$current['Date']}</p>
            <p><b>Location:</b> {$current['Location']}</p>
            <p><b>Status:</b> <span class='badge {$current['Status']}'>{$current['Status']}</span></p>
            <p><b>Priority:</b> <span class='{$cls}'>{$pri}</span></p>
            <p><b>Station:</b> {$current['StationName']}</p>
        </div>";

        // FIR box
        echo "<div class='detail-box'>
            <h4>📄 FIR Details</h4>";
        if ($fir) {
            echo "<p><b>FIR ID:</b> {$fir['FIRID']}</p>
                  <p><b>FIR Date:</b> {$fir['FIRDate']}</p>";
        } else {
            echo "<p style='color:#999;'>No FIR registered for this case.</p>";
        }
        echo "</div>";

        echo "</div>"; // end detail-grid

        // Officers table
        echo "<div class='detail-box' style='margin-bottom:12px;'>
            <h4>🚔 Assigned Officers</h4>";
        if ($officers->num_rows > 0) {
            echo "<table class='sub-table'><tr><th>Name</th><th>Rank</th><th>Role</th></tr>";
            while($o = $officers->fetch_assoc()) {
                echo "<tr><td>{$o['Name']}</td><td>{$o['Rank']}</td><td>{$o['Role']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color:#999;'>No officers assigned.</p>";
        }
        echo "</div>";

        // Persons table
        echo "<div class='detail-box' style='margin-bottom:12px;'>
            <h4>👤 Persons Involved</h4>";
        if ($persons->num_rows > 0) {
            echo "<table class='sub-table'><tr><th>Name</th><th>Gender</th><th>Phone</th><th>Type</th></tr>";
            while($p = $persons->fetch_assoc()) {
                echo "<tr><td>{$p['Name']}</td><td>{$p['Gender']}</td><td>{$p['Phone']}</td><td>{$p['Type']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color:#999;'>No persons linked.</p>";
        }
        echo "</div>";

        // Evidence table
        echo "<div class='detail-box' style='margin-bottom:12px;'>
            <h4>🔬 Evidence</h4>";
        if ($evidences->num_rows > 0) {
            echo "<table class='sub-table'><tr><th>Evidence ID</th><th>Type</th></tr>";
            while($e = $evidences->fetch_assoc()) {
                echo "<tr><td>{$e['EvidenceID']}</td><td>{$e['Type']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color:#999;'>No evidence recorded.</p>";
        }
        echo "</div>";

        echo "<br>
        <a class='btn update' href='update_case.php?id={$current['CaseID']}'>✏️ Update</a>
        <a class='btn delete' href='delete_case.php?id={$current['CaseID']}'
           onclick=\"return confirm('Delete this case?')\">🗑️ Delete</a>
        <hr>
        <a class='nav-btn' href='view_cases.php?pos=0'>⏮ First</a>
        <a class='nav-btn' href='view_cases.php?pos=".($pos-1)."'>⬅ Prev</a>
        <a class='nav-btn' href='view_cases.php?pos=".($pos+1)."'>Next ➡</a>
        <a class='nav-btn' href='view_cases.php?pos=last'>⏭ Last</a>
        <span style='margin-left:10px;color:#555;'>Record ".($pos+1)." of $total</span>";
    } else {
        echo "<p style='color:#888;'>No records available.</p>";
    }
}
?>

<br><br>
<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>
</body>
</html>
