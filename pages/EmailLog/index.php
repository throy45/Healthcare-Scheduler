<?php
    require_once '../../database.php';
    $statement = $conn->prepare('SELECT * FROM EmailLog');
    $statement->execute();
    mysqli_stmt_bind_result($statement, $row['FacilityID'], $row['EmployeeID'], $row['Date'], $row['Subject'], $row['Body']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Email Log</title>
</head>
<body>
    <h1>Email Log</h1>
    <a href="./create.php">Add a new email</a>
    <table>
        <thead>
            <tr>
                <th>Facility ID</th>
                <th>Employee ID</th>
                <th>Date</th>
                <th>Subject</th>
                <th>Body</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $row['FacilityID'] ?></td>
                    <td><?php echo $row['EmployeeID'] ?></td>
                    <td><?php echo $row['Date'] ?></td>
                    <td><?php echo $row['Subject'] ?></td>
                    <td><?php echo $row['Body'] ?></td>
                    <td>
                        <a href="./edit.php?FacilityID=<?php echo $row["FacilityID"] ?>&EmployeeID=<?php echo $row["EmployeeID"] ?>&Date=<?php echo $row["Date"] ?>">Edit</a>
                        <a href="./delete.php?FacilityID=<?php echo $row["FacilityID"] ?>&EmployeeID=<?php echo $row["EmployeeID"] ?>&Date=<?php echo $row["Date"] ?>">Delete</a>
                    </td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>
