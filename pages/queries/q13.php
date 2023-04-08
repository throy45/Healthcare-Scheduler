<?php 
    require_once '../../database.php'; 
    include '../header.php';
    $statement = $conn->prepare('SELECT p.Province, f.Name AS FacilityName, f.Capacity, COUNT(e.EmployeeID) AS TotalInfectedCovidEmployeesPastTwoWeeks
                                FROM Facilities f, Employment em, Employees e, Infections i, PostalCodes p
                                WHERE f.FacilityID = em.FacilityID AND
                                      em.EmployeeID = e.EmployeeID AND
                                      e.EmployeeID = i.EmployeeID AND
                                      f.PostalCode = p.PostalCode AND
                                      i.Type = "COVID-19" AND
                                      DATEDIFF(CURDATE(), i.Date) <= 14
                                GROUP BY f.FacilityID

                                UNION

                                SELECT p.Province, f.Name AS FacilityName, f.Capacity, 0 AS TotalInfectedCovidEmployeesPastTwoWeeks
                                FROM Facilities f, PostalCodes p
                                WHERE f.PostalCode = p.PostalCode AND
                                      f.FacilityID NOT IN (
                                          SELECT DISTINCT f.FacilityID
                                          FROM Facilities f, Employment em, Employees e, Infections i
                                          WHERE f.FacilityID = em.FacilityID AND
                                                em.EmployeeID = e.EmployeeID AND
                                                e.EmployeeID = i.EmployeeID AND
                                                i.Type = "COVID-19" AND
                                                DATEDIFF(CURDATE(), i.Date) <= 14
                                      )
                                ORDER BY Province, TotalInfectedCovidEmployeesPastTwoWeeks;');
    $statement->execute();
    mysqli_stmt_bind_result($statement, $province, $facilityName, $capacity, $totalInfectedCovidEmployeesPastTwoWeeks);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Query 13</title>
</head>
<body>
    <h1>List of COVID infectons in the past 2 weeks, by facility</h1>
    <table>
        <thead>
            <tr>
                <th>Province</th>
                <th>Name</th>
                <th>Capacity</th>
                <th>Total Infected COVID Employees Past Two Weeks</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $province ?></td>
                    <td><?php echo $facilityName ?></td>
                    <td><?php echo $capacity ?></td>
                    <td><?php echo $totalInfectedCovidEmployeesPastTwoWeeks ?></td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>