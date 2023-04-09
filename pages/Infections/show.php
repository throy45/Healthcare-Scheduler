<?php require_once '../../database.php'; 
    include '../header.php';
    if (isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID']) && isset($_GET['InfectionID']) && !empty($_GET['InfectionID'])) {
        $EmployeeID = $_GET['EmployeeID'];
        $InfectionID = $_GET['InfectionID'];
        $result = $conn->query("SELECT * FROM Infections WHERE EmployeeID = $EmployeeID AND InfectionID = $InfectionID");
        $row = $result->fetch_assoc();
      }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infection <?php echo $row["InfectionID"]; ?></title>
</head>
<body>
    <p>Infection ID: <?php echo $row["InfectionID"];?></p>
    <p>Employee ID: <?php echo $row["EmployeeID"];?></p>
    <p>Type: <?php echo $row["Type"];?></p>
    <p>Date: <?php echo $row["Date"];?></p>
    <a href="./index.php">Back</a>
</body>
</html>