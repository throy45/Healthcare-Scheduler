<?php require_once '../../database.php'; 
    include '../header.php';
    
    if (isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID'])) {
        $EmployeeID = $_GET['EmployeeID'];
        $result = $conn->query("SELECT * FROM employees WHERE EmployeeID = $EmployeeID");
        $row = $result->fetch_assoc();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row["FName"];?> <?php echo $row["LName"];?></title>
</head>
<body>
    <p>Employee ID: <?php echo $row["EmployeeID"];?></p>
    <p>First Name: <?php echo $row["FName"];?></p>
    <p>Last Name: <?php echo $row["LName"];?></p>
    <p>Role: <?php echo $row["Role"];?></p>
    <p>Date of birth: <?php echo $row["DoBirth"];?></p>
    <p>Medicare number: <?php echo $row["MedicareNumber"];?></p>
    <p>Email: <?php echo $row["Email"];?></p>
    <p>Citizenship: <?php echo $row["Citizenship"];?></p>
    <p>Phone number: <?php echo $row["PhoneNumber"];?></p>
    <p>Address: <?php echo $row["Address"];?></p>
    <p>Postal code:<?php echo $row["PostalCode"];?></p>
    <a href="./index.php">Back</a>
</body>
</html>