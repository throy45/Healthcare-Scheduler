<?php require_once '../../database.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Managing</title>
</head>
<body>
    <h1>Managing</h1>
    <a href="./create.php">Add Managing relation</a>
    <table>
        <tr>
            <th>Facility ID</th>
            <th>Employee ID</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Action</th>
        </tr>
        <?php
            $result = $conn->query("SELECT * FROM Managers");
            while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row["FacilityID"]; ?></td>
            <td><?php echo $row["EmployeeID"]; ?></td>
            <td><?php echo $row["StartDate"]; ?></td>
            <td><?php echo $row["EndDate"]; ?></td>
            <td>
                <a href="./edit.php?FacilityID=<?php echo $row["FacilityID"]; ?>&EmployeeID=<?php echo $row["EmployeeID"]; ?>&StartDate=<?php echo $row["StartDate"]; ?>">Edit</a>
                <a href="./delete.php?FacilityID=<?php echo $row["FacilityID"]; ?>&EmployeeID=<?php echo $row["EmployeeID"]; ?>&StartDate=<?php echo $row["StartDate"]; ?>" onclick="return confirm('Are you sure you want to delete this manager?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>
