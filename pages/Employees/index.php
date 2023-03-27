<?php
    require_once '../../database.php';
    $statement = $conn -> prepare("SELECT * FROM Employees");
    $statement->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>List of Employees</h1>
    <table>
        <thead>
            <tr>
                <td>Employee ID</td>
                <td>First Name</td>
                <td>Last Name</td>
                <td>Role</td>
                <td>Date of Birth</td>
                <td>Medicare Number</td>
                <td>Email</td>
                <td>Citizenship</td>
                <td>Phone Number</td>
                <td>Address</td>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) { ?>
                <tr>
                    <td><?= $row['EmployeeID'] ?></td>
                    <td><?=  $row['FName'] ?></td>
                    <td><?=  $row['LName'] ?></td>
                    <td><?=  $row['Role'] ?></td>
                    <td><?=  $row['DoBirth'] ?></td>
                    <td><?=  $row['MedicareNumber'] ?></td>
                    <td><?=  $row['Email'] ?></td>
                    <td><?=  $row['Citizenship'] ?></td>
                    <td><?=  $row['PhoneNumber'] ?></td>
                    <td><?=  $row['Address'] ?></td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
</body>
</html>