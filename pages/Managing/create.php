<?php require_once '../../database.php'; 
    include '../header.php';
  
if (
    isset($_POST["FacilityID"]) && 
    isset($_POST["EmployeeID"]) && 
    isset($_POST["StartDate"]) && 
    isset($_POST["EndDate"])
) {
        
    $FacilityID = $_POST["FacilityID"];
    $EmployeeID = $_POST["EmployeeID"];
    $StartDate = $_POST["StartDate"];
    $EndDate = $_POST["EndDate"];

    $stmt = $conn->prepare("INSERT INTO Managers (FacilityID, EmployeeID, StartDate, EndDate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $FacilityID, $EmployeeID, $StartDate, $EndDate);

    if ($stmt->execute()){
        header("Location: ./index.php");
    } else {
        echo "Something went wrong. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Managin relation</title>
</head>
<body>
    <h1>Add Managing relation</h1>
    <form action="./create.php" method="post">
        <label for="FacilityID">Facility ID</label><br>
        <input type="number" name="FacilityID" id="FacilityID" required><br>
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" required><br>
        <label for="StartDate">Start Date</label><br>
        <input type="date" name="StartDate" id="StartDate" required><br>
        <label for="EndDate">End Date</label><br>
        <input type="date" name="EndDate" id="EndDate"><br><br>
        <button type="submit">Add</button><br><br>
    </form>
    <a href="./index.php">Back to Managing list</a>
</body>
</html>
