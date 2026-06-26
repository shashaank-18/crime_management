<?php
$conn = new mysqli("localhost", "root", "", "crime_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create all tables based on ER diagram
$conn->query("CREATE TABLE IF NOT EXISTS PoliceStation (
    StationID VARCHAR(20) PRIMARY KEY,
    Name VARCHAR(100),
    Address VARCHAR(200),
    Phone VARCHAR(20)
)");

$conn->query("CREATE TABLE IF NOT EXISTS PoliceOfficer (
    OfficerID VARCHAR(20) PRIMARY KEY,
    Name VARCHAR(100),
    Rank VARCHAR(50),
    Phone VARCHAR(20),
    StationID VARCHAR(20),
    FOREIGN KEY (StationID) REFERENCES PoliceStation(StationID)
)");

$conn->query("CREATE TABLE IF NOT EXISTS CrimeCase (
    CaseID VARCHAR(20) PRIMARY KEY,
    CaseNo VARCHAR(20),
    CrimeType VARCHAR(100),
    Date DATE,
    Location VARCHAR(200),
    Status VARCHAR(50),
    StationID VARCHAR(20),
    FOREIGN KEY (StationID) REFERENCES PoliceStation(StationID)
)");

$conn->query("CREATE TABLE IF NOT EXISTS CaseOfficer (
    CaseID VARCHAR(20),
    OfficerID VARCHAR(20),
    Role VARCHAR(100),
    PRIMARY KEY (CaseID, OfficerID),
    FOREIGN KEY (CaseID) REFERENCES CrimeCase(CaseID),
    FOREIGN KEY (OfficerID) REFERENCES PoliceOfficer(OfficerID)
)");

$conn->query("CREATE TABLE IF NOT EXISTS Person (
    PersonID VARCHAR(20) PRIMARY KEY,
    Name VARCHAR(100),
    DOB DATE,
    Gender VARCHAR(10),
    Address VARCHAR(200),
    Phone VARCHAR(20),
    Role VARCHAR(50)
)");

$conn->query("CREATE TABLE IF NOT EXISTS CasePerson (
    CaseID VARCHAR(20),
    PersonID VARCHAR(20),
    Type VARCHAR(50),
    PRIMARY KEY (CaseID, PersonID),
    FOREIGN KEY (CaseID) REFERENCES CrimeCase(CaseID),
    FOREIGN KEY (PersonID) REFERENCES Person(PersonID)
)");

$conn->query("CREATE TABLE IF NOT EXISTS FIR (
    FIRID VARCHAR(20) PRIMARY KEY,
    FIRDate DATE,
    CaseID VARCHAR(20),
    FOREIGN KEY (CaseID) REFERENCES CrimeCase(CaseID)
)");

$conn->query("CREATE TABLE IF NOT EXISTS Evidence (
    EvidenceID VARCHAR(20) PRIMARY KEY,
    Type VARCHAR(100),
    CaseID VARCHAR(20),
    FOREIGN KEY (CaseID) REFERENCES CrimeCase(CaseID)
)");

$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255)
)");
?>
