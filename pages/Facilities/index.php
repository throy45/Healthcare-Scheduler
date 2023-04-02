<?php
    require_once '../../database.php';
    $statement = $conn->prepare('SELECT * FROM Facilities');
    $statement->execute();
    mysqli_stmt_bind_result($statement, $row['FacilityID'], $row['Name'], $row['Type'], $row['Capacity'], $row['WebAddress'], $row['PhoneNumber'], $row['Address'], $row['PostalCode']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Facilities</title>
</head>
<body>
    <h1>List of Facilities</h1>
    <a href="./create.php">Add a new Facility</a>
    <table>
        <thead>
            <tr>
                <th>Facility ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Capacity</th>
                <th>Web Address</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Postal Code</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $row['FacilityID'] ?></td>
                    <td><?php echo $row['Name'] ?></td>
                    <td><?php echo $row['Type'] ?></td>
                    <td><?php echo $row['Capacity'] ?></td>
                    <td><?php echo $row['WebAddress'] ?></td>
                    <td><?php echo $row['PhoneNumber'] ?></td>
                    <td><?php echo $row['Address'] ?></td>
                    <td><?php echo $row['PostalCode'] ?></td>
                    <td>
                        <a href="./edit.php?FacilityID=<?php echo $row["FacilityID"] ?>">Edit</a>
                        <a href="./delete.php?FacilityID=<?php echo $row["FacilityID"] ?>">Delete</a>
                    </td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>
