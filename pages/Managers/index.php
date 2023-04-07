<?php require_once '../../database.php'; 
    include '../header.php';
    $statement = $conn->prepare('SELECT * FROM Managers, Employees WHERE Managers.EmployeeID = Employees.EmployeeID');
    $statement->execute();
    mysqli_stmt_bind_result($statement, $row['EmployeeID'], $row['EmployeeID2'], $row['FirstName'], $row['LastName'], $row['Role'], $row['DateOfBirth'], $row['MedicareNumber'], $row['Email'], $row['Citizenship'], $row['PhoneNumber'], $row['Address'], $row['PostalCode'])
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Managers</title>
</head>
<body>
    <h1>List of Managers</h1>
    <a href="./create.php">Add a new Manager</a>
    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Role</th>
                <th>Date of Birth</th>
                <th>Medicare Number</th>
                <th>Email</th>
                <th>Citizenship</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Postal Code</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $row['EmployeeID'] ?></td>
                    <td><?php echo $row['FirstName'] ?></td>
                    <td><?php echo $row['LastName'] ?></td>
                    <td><?php echo $row['Role'] ?></td>
                    <td><?php echo $row['DateOfBirth'] ?></td>
                    <td><?php echo $row['MedicareNumber'] ?></td>
                    <td><?php echo $row['Email'] ?></td>
                    <td><?php echo $row['Citizenship'] ?></td>
                    <td><?php echo $row['PhoneNumber'] ?></td>
                    <td><?php echo $row['Address'] ?></td>
                    <td><?php echo $row['PostalCode'] ?></td>
                    <td>
                        <a href="./edit.php?EmployeeID=<?php echo $row["EmployeeID" ] ?>" style="pointer-events: none">Edit</a>
                        <a href="./delete.php?EmployeeID=<?php echo $row["EmployeeID"] ?>">Delete</a>
                    </td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>