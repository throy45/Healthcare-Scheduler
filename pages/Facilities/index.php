<?php
    require_once '../../database.php';
    $statement = $conn->prepare('SELECT * FROM facilities');
    $statement->execute();
    mysqli_stmt_bind_result($statement, $row['FacilityID'], $row['Name'], $row['Type'], $row['Capacity'], $row['WebAddress'], $row['PhoneNumber'], $row['Address'])
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilities</title>
</head>
<body>
    <h1>List of Facilities</h1>
    <table>
        <tr>
            <th>Facility ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Capacity</th>
            <th>WebAddress</th>
            <th>Phone Number</th>
            <th>Address</th>
        </tr>
    </table>
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
                <br>
            </tr>
        <?php }; ?>
    </tbody>
    <a href="../../index.php">Back to main page</a>
</body>
</html>