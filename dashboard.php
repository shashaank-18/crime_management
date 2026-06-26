<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Crime Management Dashboard</title>
<link rel="stylesheet" href="style.css">
<style>
.header {
    background: #0d47a1;
    padding: 15px 30px;
    font-size: 22px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 10px;
}

.dashboard {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    padding: 30px 40px;
}

.card {
    padding: 25px 15px;
    border-radius: 12px;
    color: white;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    cursor: pointer;
    transition: 0.3s;
    text-decoration: none;
    box-shadow: 0px 5px 15px rgba(0,0,0,0.3);
}

.card:hover { transform: translateY(-5px); }

.insert    { background: #1b5e20; }
.view      { background: #0d47a1; }
.search    { background: #4a148c; }
.officer   { background: #e65100; }
.person    { background: #880e4f; }
.fir       { background: #006064; }
.evidence  { background: #37474f; }
.station   { background: #1a237e; }
.logout    { background: #b71c1c; }

.stats {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin: 20px 40px;
}

.stat-box {
    background: white;
    color: #0d47a1;
    padding: 15px 25px;
    border-radius: 10px;
    font-weight: bold;
    font-size: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    min-width: 130px;
    text-align: center;
}

.stat-box span {
    display: block;
    font-size: 26px;
    color: #0d47a1;
}

.section-title {
    padding: 0 40px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 2px;
    opacity: 0.7;
    margin-bottom: -10px;
}
</style>
</head>
<body>

<div class="header">
    👮 Crime Record Management System
</div>

<h2 style="padding:0 40px;">Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></h2>

<?php
include 'db.php';

$total        = $conn->query("SELECT COUNT(*) AS c FROM CrimeCase")->fetch_assoc()['c'];
$open         = $conn->query("SELECT COUNT(*) AS c FROM CrimeCase WHERE Status='Open'")->fetch_assoc()['c'];
$closed       = $conn->query("SELECT COUNT(*) AS c FROM CrimeCase WHERE Status='Closed'")->fetch_assoc()['c'];
$investing    = $conn->query("SELECT COUNT(*) AS c FROM CrimeCase WHERE Status='Investigating'")->fetch_assoc()['c'];
$officers     = $conn->query("SELECT COUNT(*) AS c FROM PoliceOfficer")->fetch_assoc()['c'];
$persons      = $conn->query("SELECT COUNT(*) AS c FROM Person")->fetch_assoc()['c'];
$firs         = $conn->query("SELECT COUNT(*) AS c FROM FIR")->fetch_assoc()['c'];
$evidences    = $conn->query("SELECT COUNT(*) AS c FROM Evidence")->fetch_assoc()['c'];
?>

<div class="stats">
    <div class="stat-box"><span><?php echo $total; ?></span>Total Cases</div>
    <div class="stat-box"><span><?php echo $open; ?></span>Open</div>
    <div class="stat-box"><span><?php echo $closed; ?></span>Closed</div>
    <div class="stat-box"><span><?php echo $investing; ?></span>Investigating</div>
    <div class="stat-box"><span><?php echo $officers; ?></span>Officers</div>
    <div class="stat-box"><span><?php echo $persons; ?></span>Persons</div>
    <div class="stat-box"><span><?php echo $firs; ?></span>FIRs</div>
    <div class="stat-box"><span><?php echo $evidences; ?></span>Evidence</div>
</div>

<p class="section-title">📁 Case Management</p>
<div class="dashboard">
    <a href="insert_case.php" class="card insert">➕<br>Insert Case</a>
    <a href="view_cases.php" class="card view">📊<br>View / Update / Delete</a>
    <a href="search_case.php" class="card search">🔍<br>Search Cases</a>
    <a href="manage_fir.php" class="card fir">📄<br>Manage FIR</a>
</div>

<p class="section-title">👥 People & Officers</p>
<div class="dashboard">
    <a href="manage_officer.php" class="card officer">🚔<br>Police Officers</a>
    <a href="manage_person.php" class="card person">👤<br>Persons (Victim/Suspect)</a>
    <a href="manage_station.php" class="card station">🏢<br>Police Stations</a>
    <a href="manage_evidence.php" class="card evidence">🔬<br>Evidence</a>
</div>

<div class="dashboard" style="grid-template-columns: repeat(4,1fr);">
    <a href="logout.php" class="card logout">🚪<br>Logout</a>
</div>

</body>
</html>
