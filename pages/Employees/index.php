<?php
    require_once '../../database.php';
    $statement = $conn->prepare('SELECT * FROM employees');
    $statement->execute();
    mysqli_stmt_bind_result($statement, $row['EmployeeID'], $row['FirstName'], $row['LastName'], $row['Role'], $row['DateOfBirth'], $row['MedicareNumber'], $row['Email'], $row['Citizenship'], $row['PhoneNumber'], $row['Address'])
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
</head>
<body>
    <h1>List of Employees</h1>
    <a href="./create.php">Add a new Employee</a>
    <table>
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
        </tr>
    </table>
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
                <br>
            </tr>
        <?php }; ?>
    </tbody>
    <a href="../../index.php">Back to main page</a>
</body>
</html>