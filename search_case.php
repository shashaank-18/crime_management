<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
<title>Search Cases</title>
<link rel="stylesheet" href="style.css">

<style>
.message {
    margin-top: 20px;
    font-weight: bold;
    color: red;
}
.success {
    color: green;
}
</style>

</head>
<body>

<div class="container">
<h2>Search Cases</h2>

<form method="post">
<input type="text" name="search" placeholder="Enter Crime Type" required>
<button type="submit">Search</button>
</form>

<br>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search'];

    $result = $conn->query("SELECT * FROM CrimeCase 
                            WHERE CrimeType LIKE '%$search%'");

    // ✅ CHECK IF RECORD EXISTS
    if ($result->num_rows > 0) {

        echo "<table>
        <tr>
            <th>Case ID</th>
            <th>Crime Type</th>
            <th>Status</th>
        </tr>";

        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>".$row["CaseID"]."</td>
                <td>".$row["CrimeType"]."</td>
                <td>".$row["Status"]."</td>
            </tr>";
        }

        echo "</table>";

    } else {
        // ❌ NO DATA FOUND MESSAGE
        echo "<div class='message'>❌ No records found for '<b>$search</b>'</div>";
    }
}
?>
<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>