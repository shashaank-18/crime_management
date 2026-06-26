<?php
include 'db.php';

$id = $_GET['id'];

$conn->query("DELETE FROM CrimeCase WHERE CaseID='$id'");

header("Location: view_cases.php");
?>