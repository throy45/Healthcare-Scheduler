<?php require_once '../../database.php'; 
    include '../header.php';
  
if (isset($_GET['FacilityID']) && !empty($_GET['FacilityID'])) {
    $FacilityID = $_GET['FacilityID'];
    $result = $conn->query("SELECT * FROM Facilities WHERE FacilityID = $FacilityID");
    $row = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row["Name"];?></title>
</head>
<body>
    <p>Facility ID: <?php echo $row["FacilityID"];?></p>
    <p>Name: <?php echo $row["Name"];?></p>
    <p>Type: <?php echo $row["Type"];?></p>
    <p>Capacity: <?php echo $row["Capacity"];?></p>
    <p>Web Address: <?php echo $row["WebAddress"];?></p>
    <p>Phone Number: <?php echo $row["PhoneNumber"];?></p>
    <p>Address: <?php echo $row["Address"];?></p>
    <p>Postal code:<?php echo $row["PostalCode"];?></p>
    <a href="./index.php">Back</a>
</body>
</html>