<?php require_once '../../database.php'; 
    include '../header.php';
  

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

    $stmt = $conn->prepare("INSERT INTO Vaccines (EmployeeID, FacilityID, VaccineID, Type, DoseNumber, Date) VALUES ($EmployeeID, $FacilityID, $VaccineID, '$Type', $DoseNumber, '$Date')");

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
    <title>Create a Vaccine</title>
</head>
<body>
    <h1>Add Vaccine</h1>
    <form action="./create.php" method="post">
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" required><br>
        <label for="FacilityID">Facility ID</label><br>
        <input type="number" name="FacilityID" id="FacilityID" required><br>
        <label for="VaccineID">Vaccine ID</label><br>
        <input type="number" name="VaccineID" id="VaccineID" required><br>
        <label for="Type">Type</label><br>
        <select name="Type" id="Type" required>
        <option value="">Select Type</option>
            <option value="Pfizer">Pfizer</option>
            <option value="Moderna">Moderna</option>
            <option value="AstraZeneca">AstraZeneca</option>
            <option value="Johnson & Johnson">Johnson & Johnson</option>
            <option value="Other">Other</option>
        </select><br>

        <label for="DoseNumber">Dose Number</label><br>
        <input type="number" name="DoseNumber" id="DoseNumber"><br>
        <label for="Date">Date</label><br>
        <input type="date" name="Date" id="Date"><br><br>
        <button type="submit">Add</button><br><br>
    </form>
    <a href="./">Back to Vaccine list</a>
</body>
</html> 