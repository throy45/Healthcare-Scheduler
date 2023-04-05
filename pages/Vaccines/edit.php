<?php require_once '../../database.php'; 
    include '../header.php';
  

if (isset($_GET['EmployeeID']) && isset($_GET['FacilityID']) && isset($_GET['VaccineID'])) {
    $EmployeeID = $_GET['EmployeeID'];
    $FacilityID = $_GET['FacilityID'];
    $VaccineID = $_GET['VaccineID'];
    $result = $conn->query("SELECT * FROM Vaccines WHERE EmployeeID = $EmployeeID AND FacilityID = $FacilityID AND VaccineID = $VaccineID");
    $row = $result->fetch_assoc();
}

if (
    isset($_POST["EmployeeID"]) && 
    isset($_POST["FacilityID"]) && 
    isset($_POST["VaccineID"]) && 
    isset($_POST["Type"]) && 
    isset($_POST["DoseNumber"]) && 
    isset($_POST["Date"])
    ) {
    $EmployeeID = $_POST["EmployeeID"];
    $FacilityID = $_POST["FacilityID"];
    $VaccineID = $_POST["VaccineID"];
    $Type = $_POST["Type"];
    $DoseNumber = $_POST["DoseNumber"];
    $Date = $_POST["Date"];

    $stmt = $conn->prepare("UPDATE Vaccines SET Type='$Type', DoseNumber='$DoseNumber', Date='$Date' WHERE EmployeeID=$EmployeeID AND FacilityID=$FacilityID AND VaccineID=$VaccineID");

    if ($stmt->execute()){
        header("Location: ./index.php");
    } else {
        echo "Something went wrong. Please try again later.";
        header("Location: ./edit.php?EmployeeID=".$EmployeeID."&FacilityID=".$FacilityID."&VaccineID=".$VaccineID);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vaccine</title>
</head>
<body>
    <h1>Edit Vaccine</h1>
    <form action="./edit.php" method="post">
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" readonly="readonly" value="<?php echo $row["EmployeeID"] ?>" required><br>
        <label for="FacilityID">Facility ID</label><br>
        <input type="number" name="FacilityID" id="FacilityID" readonly="readonly" value="<?php echo $row["FacilityID"] ?>" required><br>
        <label for="VaccineID">Vaccine ID</label><br>
        <input type="number" name="VaccineID" id="VaccineID" readonly="readonly" value="<?php echo $row["VaccineID"] ?>" required><br>
        <label for="Type">Type</label><br>
        <input type="text" name="Type" id="Type" value="<?php echo $row["Type"] ?>"><br>
        <label for="DoseNumber">Dose Number</label><br>
        <input type="number" name="DoseNumber" id="DoseNumber" value="<?php echo $row["DoseNumber"] ?>"><br>
        <label for="Date">Date</label><br>
        <input type="date" name="Date" id="Date" value="<?php echo $row["Date"] ?>"><br><br>
        <button type="submit">Update</button><br><br>
    </form>
    <a href="./">Back to Vaccine list</a>
</body>
</html> 