<?php
require_once '../../database.php';

if (isset($_GET['FacilityID']) && isset($_GET['EmployeeID']) && isset($_GET['Date']) && isset($_GET['StartTime'])) {
    $FacilityID = $_GET['FacilityID'];
    $EmployeeID = $_GET['EmployeeID'];
    $Date = $_GET['Date'];
    $StartTime = $_GET['StartTime'];

    $statement = $conn->prepare("DELETE FROM Schedule WHERE FacilityID = ? AND EmployeeID = ? AND Date = ? AND StartTime = ?");
    $statement->bind_param("iiss", $FacilityID, $EmployeeID, $Date, $StartTime);
    $statement->execute();

    header("Location: ./index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Schedule Deletion</title>
</head>
<body>
    <h1>Delete Schedule Entry</h1>
    <form action="" method="GET">
        <label for="FacilityID">Facility ID:</label>
        <input type="number" name="FacilityID" required>
        <br>
        <label for="EmployeeID">Employee ID:</label>
        <input type="number" name="EmployeeID" required>
        <br>
        <label for="Date">Date:</label>
        <input type="date" name="Date" required>
        <br>
        <label for="StartTime">Start Time:</label>
        <input type="time" name="StartTime" required>
        <br>
        <input type="submit" value="Delete">
    </form>
</body>
</html>
