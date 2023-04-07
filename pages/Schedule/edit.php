<?php 
require_once '../../database.php'; 
include '../header.php';

if (isset($_GET['FacilityID']) && isset($_GET['EmployeeID']) && isset($_GET['Date']) && isset($_GET['StartTime'])) {
    $FacilityID = $_GET['FacilityID'];
    $EmployeeID = $_GET['EmployeeID'];
    $Date = $_GET['Date'];
    $StartTime = $_GET['StartTime'];
    $result = $conn->query("SELECT * FROM Schedule WHERE FacilityID = $FacilityID AND EmployeeID = $EmployeeID AND Date = '$Date' AND StartTime = '$StartTime'");
    $row = $result->fetch_assoc();
}

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
        $stmt = $conn->prepare("UPDATE Schedule SET EndTime='$EndTime' WHERE FacilityID=$FacilityID AND EmployeeID=$EmployeeID AND Date='$Date' AND StartTime='$StartTime' AND NOT EXISTS (SELECT * FROM Schedule WHERE EmployeeId = $EmployeeID AND Date = '$Date' AND ((StartTime <= '$EndTime' AND EndTime > '$StartTime' - INTERVAL 1 HOUR) OR (StartTime >= '$StartTime' AND StartTime < '$EndTime' + INTERVAL 1 HOUR)))");
        $stmt->execute();
        header("Location: ./index.php");
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        if (strpos($error_message, 'CheckScheduleConflict') !== false) {
            echo "Error: Employee is scheduled at a conflicting time.";
        } else {
            echo "Something went wrong. Please try again later.";
        }
        header("Location: ./edit.php?FacilityID=".$FacilityID."&EmployeeID=".$EmployeeID."&Date=".$Date."&StartTime=".$StartTime);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule</title>
</head>
<body>
    <h1>Edit Schedule</h1>
    <form action="./edit.php" method="post">
        <label for="FacilityID">Facility ID</label><br>
        <input type="number" name="FacilityID" id="FacilityID" readonly="readonly"  value="<?php echo $row["FacilityID"]  ?>" require><br>
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" readonly="readonly"  value="<?php echo $row["EmployeeID"]  ?>" require><br>
        <label for="Date">Date</label><br>
        <input type="date" name="Date" id="Date" readonly="readonly"  value="<?php echo $row["Date"]  ?>" require><br>
        <label for="StartTime">Start Time</label><br>
        <input type="time" name="StartTime" id="StartTime" readonly="readonly"  value="<?php echo $row["StartTime"]  ?>" require><br>
        <label for="EndTime">End Time</label><br>
        <input type="time" name="EndTime" id="EndTime" value="<?php echo $row["EndTime"] ?>" require><br><br>
        <button type="submit">Update</button><br><br>
    </form>
    <a href="./">Back to Schedule list</a>
</body>
</html>
