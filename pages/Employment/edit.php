<?php require_once '../../database.php'; 

if (
    isset($_POST["FacilityID"]) && 
    isset($_POST["EmployeeID"]) && 
    isset($_POST["ContractID"]) && 
    isset($_POST["StartDate"])
    ) {
    $FacilityID = $_POST["FacilityID"];
    $EmployeeID = $_POST["EmployeeID"];
    $ContractID = $_POST["ContractID"];
    $StartDate = $_POST["StartDate"];
    $EndDate = isset($_POST["EndDate"]) ? $_POST["EndDate"] : null;

    $stmt = $conn->prepare("UPDATE Employment SET StartDate=?, EndDate=? WHERE FacilityID=? AND EmployeeID=? AND ContractID=?");

    $stmt->bind_param("ssiii", $StartDate, $EndDate, $FacilityID, $EmployeeID, $ContractID);
    
    if ($stmt->execute()){
        header("Location: ./index.php");
    } else {
        echo "Something went wrong. Please try again later.";
        header("Location: ./edit.php?FacilityID=".$FacilityID."&EmployeeID=".$EmployeeID."&ContractID=".$ContractID);
    }
} else if (isset($_GET['FacilityID']) && !empty($_GET['FacilityID']) && isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID']) && isset($_GET['ContractID']) && !empty($_GET['ContractID'])) {
    $FacilityID = $_GET['FacilityID'];
    $EmployeeID = $_GET['EmployeeID'];
    $ContractID = $_GET['ContractID'];
    $result = $conn->query("SELECT * FROM Employment WHERE FacilityID = $FacilityID AND EmployeeID = $EmployeeID AND ContractID = $ContractID");
    $row = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employment</title>
</head>
<body>
    <h1>Edit Employment</h1>
    <form action="./edit.php" method="post">
        <input type="hidden" name="FacilityID" value="<?php echo $row["FacilityID"] ?>">
        <input type="hidden" name="EmployeeID" value="<?php echo $row["EmployeeID"] ?>">
        <input type="hidden" name="ContractID" value="<?php echo $row["ContractID"] ?>">
        <label for="StartDate">Start Date</label><br>
        <input type="date" name="StartDate" id="StartDate" value="<?php echo $row["StartDate"] ?>"><br>
        <label for="EndDate">End Date</label><br>
        <input type="date" name="EndDate" id="EndDate" value="<?php echo $row["EndDate"] ?>"><br><br>
        <button type="submit">Update</button><br><br>
    </form>
    <a href="./">Back to Employment list</a>
</body>
</html>