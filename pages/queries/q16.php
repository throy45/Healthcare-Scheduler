<?php
require_once '../../database.php';
include '../header.php';

$statement = $conn->prepare("SELECT FName, LName, FirstDayOfWork, Role, DoBirth, Email, TotalHours
FROM (
	 SELECT e.EmployeeID, e.FName, e.LName, MIN(em2.StartDate) AS FirstDayOfWork, e.Role, e.DoBirth, e.Email
        	FROM Employees e, Employment em1, Employment em2, Infections i
        	WHERE e.EmployeeID = i.EmployeeID AND
  	  	   e.EmployeeID = em1.EmployeeID AND
  	  	   e.EmployeeID = em2.EmployeeID AND
  	  	   e.Role IN ('Nurse', 'Doctor') AND
 	   	   em1.EndDate IS NULL AND
  	  	   e.EmployeeID IN (
                    		SELECT EmployeeID
                    		FROM Infections
                    		WHERE Type = 'COVID-19'
                    		GROUP BY EmployeeID
                    		HAVING COUNT(InfectionID) >=3)
        	GROUP BY e.EmployeeID
        	ORDER BY e.Role, e.FName, e.LName
) AS T1,
(
	SELECT EmployeeID, SUM(TIMESTAMPDIFF(HOUR, StartTime, EndTime)) AS TotalHours
	FROM Schedule
	GROUP BY EmployeeID
) AS T2
WHERE T1.EmployeeID = T2.EmployeeID;
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
    <title>Query 16</title>
</head>
<body>
    <h1>List of doctors and nurses infected 3 or more times by COVID-19</h1>
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
            <?php while ($statement->fetch()) {?>
                <tr>
                    <td><?php echo $fname ?></td>
                    <td><?php echo $lname ?></td>
                    <td><?php echo $firstdayofwork ?></td>
                    <td><?php echo $role ?></td>
                    <td><?php echo $dob ?></td>
                    <td><?php echo $email ?></td>
                    <td><?php echo $totalhours ?></td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>