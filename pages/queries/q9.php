<?php 
    require_once '../../database.php'; 
    include '../header.php';
    $statement = $conn->prepare('SELECT e.FName, e.LName, i.Date, f.Name AS FacilityName
        FROM Facilities f, Employees e, Employment em, Infections i
        WHERE e.EmployeeID = i.EmployeeID AND
              e.EmployeeID = em.EmployeeID AND
              em.FacilityID = f.FacilityID AND
              em.EndDate IS NULL AND
              e.Role = "Doctor" AND
              i.Type = "COVID-19" AND
              DATEDIFF(CURDATE(), i.Date) <= 14
        ORDER BY FacilityName, e.FName');
    $statement->execute();
    mysqli_stmt_bind_result($statement, $row['FName'], $row['LName'], $row['Date'], $row['FacilityName']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Query 9</title>
</head>
<body>
    <h1>List of doctor COVID-19 infections in the last 2 weeks</h1>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date</th>
                <th>Facility Name</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $row['FName'] ?></td>
                    <td><?php echo $row['LName'] ?></td>
                    <td><?php echo $row['Date'] ?></td>
                    <td><?php echo $row['FacilityName'] ?></td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>