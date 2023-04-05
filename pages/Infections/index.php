<?php require_once '../../database.php'; 
    include '../header.php';
    $statement = $conn->prepare('SELECT * FROM Infections, Employees WHERE Infections.EmployeeID = Employees.EmployeeID');
    $statement->execute();
    mysqli_stmt_bind_result($statement, $row['EmployeeID'], $row['InfectionID'], $row['Type'], $row['Date'], $row['EmployeeID2'], $row['FName'], $row['LName'], $row['Role'], $row['DateOfBirth'], $row['MedicareNumber'], $row['Email'], $row['Citizenship'], $row['PhoneNumber'], $row['Address'], $row['PostalCode'])
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Infections</title>
</head>
<body>
    <h1>List of Infections</h1>
    <a href="./create.php">Add a new Infection</a>
    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Type</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $row['EmployeeID'] ?></td>
                    <td><?php echo $row['FName'] ?></td>
                    <td><?php echo $row['LName'] ?></td>
                    <td><?php echo $row['Type'] ?></td>
                    <td><?php echo $row['Date'] ?></td>
                    <td>
                        <a href="./edit.php?EmployeeID=<?php echo $row["EmployeeID"] ?>&InfectionID=<?php echo $row["InfectionID"] ?>">Edit</a>
                        <a href="./delete.php?EmployeeID=<?php echo $row["EmployeeID"] ?>&InfectionID=<?php echo $row["InfectionID"] ?>">Delete</a>
                    </td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>