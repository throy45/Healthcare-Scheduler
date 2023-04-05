<?php require_once '../database.php';
$statement = $conn->prepare('SELECT * FROM Infections');
$statement->execute();
mysqli_stmt_bind_result($statement, $row['EmployeeID'], $row['InfectionID'], $row['Type'], $row['Date']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>List of Infections</title>
</head>
<body>
    <h1>List of Infections</h1>
    <a href="./create.php">Add a new Infection</a>
    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Infection ID</th>
                <th>Type</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $row['EmployeeID'] ?></td>
                    <td><?php echo $row['InfectionID'] ?></td>
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

    <a href="../">Back to main page</a>
    
</body>
</html>