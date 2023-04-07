<?php 
require_once '../../database.php'; 
include '../header.php';

if (
    isset($_POST["FacilityID"]) &&
    isset($_POST["EmployeeID"]) &&
    isset($_POST["Date"]) &&
    isset($_POST["StartTime"]) &&
    isset($_POST["EndTime"])
) {

    $FacilityID = $_POST["FacilityID"];
    $EmployeeID = $_POST["EmployeeID"];
    $Date = $_POST["Date"];
    $StartTime = $_POST["StartTime"];
    $EndTime = $_POST["EndTime"];

try {
    $stmt = $conn->prepare("INSERT INTO Schedule (FacilityID, EmployeeID, Date, StartTime, EndTime) 
                            SELECT ?,?,?,?,? 
                            FROM dual
                            WHERE NOT EXISTS (
                                SELECT * FROM Schedule
                                WHERE EmployeeID = ?
                                AND Date = ?
                                AND ((StartTime <= ? AND EndTime > ? - INTERVAL 1 HOUR)
                                    OR (StartTime >= ? AND StartTime < ? + INTERVAL 1 HOUR))
                                OR StartTime > (NOW() + INTERVAL 4 WEEK)
                            )");
    $stmt->bind_param("iisssisssss", $FacilityID, $EmployeeID, $Date, $StartTime, $EndTime, $EmployeeID, $Date, $StartTime, $StartTime, $StartTime, $EndTime);
    $stmt->execute();
    if ($stmt->affected_rows == 0) {
        throw new Exception("Error: Schedule conflict or more than 4 weeks ahead.");
    }
    header("Location: ./index.php");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Schedule</title>
</head>

<body>
    <h1>Add Schedule</h1>
    <form action="./create.php" method="post">
        <label for="FacilityID">Facility ID</label><br>
        <input type="number" name="FacilityID" id="FacilityID" required><br>
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" required><br>
        <label for="Date">Date</label><br>
        <input type="date" name="Date" id="Date" required><br>
        <label for="StartTime">Start Time</label><br>
        <input type="time" name="StartTime" id="StartTime" required><br>
        <label for="EndTime">End Time</label><br>
        <input type="time" name="EndTime" id="EndTime" required><br><br>
        <button type="submit">Add</button><br><br>
    </form>
    <a href="./">Back to Schedule list</a>
</body>

</html>

