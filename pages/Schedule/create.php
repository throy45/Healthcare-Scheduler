<?php 
require_once '../../database.php'; 

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
        $stmt = $conn->prepare("INSERT INTO hfests.Schedule (FacilityID, EmployeeID, Date, StartTime, EndTime)
                                SELECT ?, ?, ?, ?, ?
                                FROM DUAL
                                WHERE NOT EXISTS (
                                    SELECT * FROM hfests.Schedule
                                    WHERE EmployeeID = ?
                                    AND Date = ?
                                    AND (
                                        (StartTime <= CONCAT(?, ' ', ?) AND EndTime > (CONCAT(?, ' ', ?) - INTERVAL 1 HOUR))
                                        OR
                                        (StartTime >= CONCAT(?, ' ', ?) AND StartTime < (CONCAT(?, ' ', ?) + INTERVAL 1 HOUR))
                                    ))
                                    AND (? < (NOW() + INTERVAL 4 WEEK)
                                );");
        $stmt->bind_param("iisssissssssssss", $FacilityID, $EmployeeID, $Date, $StartTime, $EndTime, $EmployeeID, $Date, $Date, $StartTime, $Date, $StartTime, $Date, $StartTime, $Date, $EndTime, $Date);
        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            throw new Exception("Schedule conflict or more than 4 weeks ahead.");
        }
        
        header("Location: ./index.php");
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "')</script>";
    }

}
include '../header.php';
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
        <input type="time" name="StartTime" id="StartTime" min="00:00" max="23:59" required><br>
        <label for="EndTime">End Time</label><br>
        <input type="time" name="EndTime" id="EndTime" min="00:00" max="23:59" required><br><br>
        <button type="submit">Add</button><br><br>
    </form>
    <a href="./">Back to Schedule list</a>
</body>

</html>

