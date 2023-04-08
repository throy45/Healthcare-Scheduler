<?php 
    require_once '../../database.php'; 
    include '../header.php';
    $statement = $conn->prepare('SELECT f.Name AS FacilityName, f.Address, p.City, p.Province, p.PostalCode, f.PhoneNumber, f.WebAddress, f.Type, f.Capacity, CONCAT(e.FName, " ", e.LName) AS GeneralManager, COUNT(em.EmployeeID) AS NumberOfCurrentEmployees
                                FROM Facilities f, PostalCodes p, Managing mg, Managers mgrs, Employment em, Employees e
                                WHERE f.PostalCode = p.PostalCode AND
                                    f.FacilityID = mg.FacilityID AND
                                    mg.EmployeeID = mgrs.EmployeeID AND
                                    mgrs.EmployeeID = e.EmployeeID AND
                                    f.FacilityID = em.FacilityID AND
                                    em.EndDate IS NULL
                                GROUP BY f.FacilityID
                                ORDER BY p.province, p.city, f.Type, NumberOfCurrentEmployees');
    $statement->execute();
    mysqli_stmt_bind_result($statement, $row['FacilityName'], $row['Address'], $row['City'], $row['Province'], $row['PostalCode'], $row['PhoneNumber'], $row['WebAddress'], $row['Type'], $row['Capacity'], $row['GeneralManager'], $row['NumberOfCurrentEmployees']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Query 6</title>
</head>
<body>
    <h1>List of facilities</h1>
    <table>
        <thead>
            <tr>
                <th>Facility Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Province</th>
                <th>Postal Code</th>
                <th>Phone Number</th>
                <th>Web Address</th>
                <th>Type</th>
                <th>Capacity</th>
                <th>General Manager</th>
                <th>Number Of Current Employees</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $row['FacilityName'] ?></td>
                    <td><?php echo $row['Address'] ?></td>
                    <td><?php echo $row['City'] ?></td>
                    <td><?php echo $row['Province'] ?></td>
                    <td><?php echo $row['PostalCode'] ?></td>
                    <td><?php echo $row['PhoneNumber'] ?></td>
                    <td><?php echo $row['WebAddress'] ?></td>
                    <td><?php echo $row['Type'] ?></td>
                    <td><?php echo $row['Capacity'] ?></td>
                    <td><?php echo $row['GeneralManager'] ?></td>
                    <td><?php echo $row['NumberOfCurrentEmployees'] ?></td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>
