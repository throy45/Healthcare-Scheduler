<?php require_once '../../database.php'; 
    include '../header.php';
  
    if (isset($_GET['EmployeeID']) && isset($_GET['FacilityID']) && isset($_GET['VaccineID'])) {
        $EmployeeID = $_GET['EmployeeID'];
        $FacilityID = $_GET['FacilityID'];
        $VaccineID = $_GET['VaccineID'];
        $result = $conn->query("SELECT * FROM Vaccines WHERE EmployeeID = $EmployeeID AND FacilityID = $FacilityID AND VaccineID = $VaccineID");
        $row = $result->fetch_assoc();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee <?php echo $row["EmployeeID"];?> Dose <?php echo $row["DoseNumber"]?></title>
</head>
<body>
    <p>Employee ID: <?php echo $row["EmployeeID"];?></p>
    <p>Facility ID: <?php echo $row["FacilityID"];?></p>
    <p>Vaccine ID: <?php echo $row["VaccineID"];?></p>
    <p>Type: <?php echo $row["Type"];?></p>
    <p>Dose Number: <?php echo $row["DoseNumber"];?></p>
    <p>Date: <?php echo $row["Date"];?></p>
    <a href="./index.php">Back</a>
</body>
</html>