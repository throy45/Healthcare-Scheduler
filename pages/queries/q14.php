<?php 
    require_once '../../database.php'; 
    include '../header.php';
    $query = 'SELECT e.FName, e.LName, p2.City, COUNT(f.FacilityID) AS TotalFacilities
              FROM Facilities f, Employment em, Employees e,  PostalCodes p1, PostalCodes p2
              WHERE  f.FacilityID = em.FacilityID AND
                     em.EmployeeID = e.EmployeeID AND
                     f.PostalCode = p1.PostalCode AND
                     e.PostalCode = p2.PostalCode AND
                     em.EndDate IS NULL AND
                     e.Role = "Doctor" AND
                     p1.Province = "Quebec"
              GROUP BY e.EmployeeID
              ORDER BY p2.City, TotalFacilities DESC';
    $statement = $conn->prepare($query);
    $statement->execute();
    mysqli_stmt_bind_result($statement, $fname, $lname, $city, $totalFacilities);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Query 14</title>
</head>
<body>
    <h1>List of doctors who work in Quebec</h1>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>City of Residence</th>
                <th>Total Facilities</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $fname ?></td>
                    <td><?php echo $lname ?></td>
                    <td><?php echo $city ?></td>
                    <td><?php echo $totalFacilities ?></td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>