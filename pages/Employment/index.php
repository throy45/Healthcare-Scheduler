<?php
require_once '../../database.php';
$statement = $conn->prepare('SELECT * FROM employment');
$statement->execute();
mysqli_stmt_bind_result($statement, $row['FacilityID'], $row['EmployeeID'], $row['ContractID'], $row['StartDate'], $row['EndDate'])
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Employment</title>
</head>
<body>
    <h1>List of Employment</h1>
    <a href="./create.php">Add a new Employment</a>
    <table>
        <thead>
            <tr>
                <th>Facility ID</th>
                <th>Employee ID</th>
                <th>Contract ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $row['FacilityID'] ?></td>
                    <td><?php echo $row['EmployeeID'] ?></td>
                    <td><?php echo $row['ContractID'] ?></td>
                    <td><?php echo $row['StartDate'] ?></td>
                    <td><?php echo $row['EndDate'] ?></td>
                    <td>
                        <a href="./edit.php?FacilityID=<?php echo $row["FacilityID"] ?>&EmployeeID=<?php echo $row["EmployeeID"] ?>&ContractID=<?php echo $row["ContractID"] ?>">Edit</a>
                        <a href="./delete.php?FacilityID=<?php echo $row["FacilityID"] ?>&EmployeeID=<?php echo $row["EmployeeID"] ?>&ContractID=<?php echo $row["ContractID"] ?>">Delete</a>
                    </td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>
