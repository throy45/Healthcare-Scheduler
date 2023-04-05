<?php require_once '../../database.php'; 
    include '../header.php';
    $statement = $conn->prepare('SELECT * FROM Vaccines');
    $statement->execute();
    mysqli_stmt_bind_result($statement, $row['EmployeeID'], $row['FacilityID'], $row['VaccineID'], $row['Type'], $row['DoseNumber'], $row['Date']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Vaccines</title>
</head>
<body>
    <h1>List of Vaccines</h1>
    <a href="./create.php">Add a new Vaccine</a>
    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Facility ID</th>
                <th>Vaccine ID</th>
                <th>Type</th>
                <th>Dose Number</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $row['EmployeeID'] ?></td>
                    <td><?php echo $row['FacilityID'] ?></td>
                    <td><?php echo $row['VaccineID'] ?></td>
                    <td><?php echo $row['Type'] ?></td>
                    <td><?php echo $row['DoseNumber'] ?></td>
                    <td><?php echo $row['Date'] ?></td>
                    <td>
                        <a href="./edit.php?EmployeeID=<?php echo $row["EmployeeID"] ?>&FacilityID=<?php echo $row["FacilityID"] ?>&VaccineID=<?php echo $row["VaccineID"] ?>">Edit</a>
                        <a href="./delete.php?EmployeeID=<?php echo $row["EmployeeID"] ?>&FacilityID=<?php echo $row["FacilityID"] ?>&VaccineID=<?php echo $row["VaccineID"] ?>">Delete</a>
                    </td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html> 