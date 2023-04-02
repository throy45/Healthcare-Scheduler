<?php
    require_once '../../database.php';
    $statement = $conn->prepare('SELECT * FROM PostalCodes');
    $statement->execute();
    mysqli_stmt_bind_result($statement, $row['PostalCode'], $row['City'], $row['Province']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Postal Codes</title>
</head>
<body>
    <h1>List of Postal Codes</h1>
    <a href="./create.php">Add a new Postal Code</a>
    <table>
        <thead>
            <tr>
                <th>Postal Code</th>
                <th>City</th>
                <th>Province</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $row['PostalCode'] ?></td>
                    <td><?php echo $row['City'] ?></td>
                    <td><?php echo $row['Province'] ?></td>
                    <td>
                        <a href="./edit.php?PostalCode=<?php echo urlencode($row["PostalCode"]) ?>">Edit</a>
                        <a href="./delete.php?PostalCode=<?php echo urlencode($row["PostalCode"]) ?>">Delete</a>
                    </td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>