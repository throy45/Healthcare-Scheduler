<?php 
require_once '../../database.php'; 
include '../header.php';

$statement = $conn->prepare("SELECT e.FName, e.LName, MIN(em.StartDate) AS FirstDayOfWork, e.Role, e.DoBirth, e.Email, SUM(TIMESTAMPDIFF(HOUR, s.StartTime, s.EndTime)) AS TotalHours
                            FROM Schedule s, Employees e, Employment em
                            WHERE e.EmployeeID = s.EmployeeID AND 
                            e.EmployeeID = em.EmployeeID AND
                            em.EndDate IS NULL AND
                            e.Role IN ('Nurse', 'Doctor') AND
                            NOT EXISTS (SELECT * FROM Infections i WHERE i.EmployeeID = e.EmployeeID)
                            GROUP BY s.EmployeeID
                            ORDER BY e.Role, e.FName, e.LName;

");
$statement->execute();
$statement->bind_result($fname, $lname, $firstdayofwork, $role, $dob, $email, $totalhours);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Query 17</title>
</head>
<body>
    <h1>List of nurses and doctors who were never infected by COVID-19</h1>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>First Day of Work</th>
                <th>Role</th>
                <th>Date of Birth</th>
                <th>Email</th>
                <th>Total Working Hours</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($statement->fetch()) { ?>
                <tr>
                    <td><?php echo $fname; ?></td>
                    <td><?php echo $lname; ?></td>
                    <td><?php echo $firstdayofwork; ?></td>
                    <td><?php echo $role; ?></td>
                    <td><?php echo $dob; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $totalhours; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>
