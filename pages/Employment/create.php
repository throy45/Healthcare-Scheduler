<?php require_once '../../database.php'; 
    if (
        isset($_POST["FacilityID"]) && 
        isset($_POST["EmployeeID"]) && 
        isset($_POST["ContractID"]) && 
        isset($_POST["StartDate"]) && 
        isset($_POST["EndDate"])
        ) {
            
        $FacilityID = $_POST["FacilityID"];
        $EmployeeID = $_POST["EmployeeID"];
        $ContractID = $_POST["ContractID"];
        $StartDate = $_POST["StartDate"];
        $EndDate = $_POST["EndDate"];

        $stmt = $conn->prepare("INSERT INTO Employment (FacilityID, EmployeeID, ContractID, StartDate, EndDate) VALUES (?, ?, ?, ?, ?)");

        $stmt->bind_param("iiiss", $FacilityID, $EmployeeID, $ContractID, $StartDate, $EndDate);

        if ($stmt->execute()){
            header("Location: ./index.php");
        } else {
            echo "Something went wrong. Please try again later.";
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
    <title>Create an Employment</title>
</head>
<body>
    <h1>Add Employment</h1>
    <form action="./create.php" method="post">
        <label for="FacilityID">Facility ID</label><br>
        <input type="number" name="FacilityID" id="FacilityID" required><br>
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" required><br>
        <label for="ContractID">Contract ID</label><br>
        <input type="number" name="ContractID" id="ContractID" required><br>
        <label for="StartDate">Start Date</label><br>
        <input type="date" name="StartDate" id="StartDate" required><br>
        <label for="EndDate">End Date</label><br>
        <input type="date" name="EndDate" id="EndDate"><br><br>
        <button type="submit">Add</button><br><br>
    </form>
    <a href="./">Back to Employment list</a>
</body>
</html>