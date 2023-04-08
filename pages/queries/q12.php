<?php 
require_once '../../database.php'; 
include '../header.php';

// Get the selected facility name from the drop-down menu
if (isset($_POST['facility'])) {
    $facilityName = $_POST['facility'];
} else {
    $facilityName = "Hospital Maisonneuve Rosemont";
}

// Get the selected period from the input fields
if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
} else {
    $startDate = '2023-03-01';
    $endDate = '2023-03-30';
}

// Get a list of all medical facilities
$facilities = $conn->query("SELECT DISTINCT Name FROM Facilities ORDER BY Name");

// Prepare and execute the SQL query with the selected facility and period
$statement = $conn->prepare('SELECT e.Role, SUM(TIMESTAMPDIFF(HOUR, s.StartTime, s.EndTime)) AS TotalHours
FROM Facilities f, Employment em, Employees e, Schedule s
WHERE  f.FacilityID = em.FacilityID AND
        	   em.EmployeeID = e.EmployeeID AND
        	   e.EmployeeID = s.EmployeeID AND
        	   f.Name = ? AND
        	   s.Date BETWEEN ? AND ?
GROUP BY e.Role
ORDER BY e.Role;');
$statement->bind_param('sss', $facilityName, $startDate, $endDate);
$statement->execute();

// Bind the results to variables
$statement->bind_result($role, $totalHours);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Query 12</title>
</head>
<body>
    <h1>Total working hours by role</h1>
    <!-- Create the drop-down menu and input fields for period -->
    <form method="post">
        <label for="facility">Select a medical facility:</label>
        <select name="facility" id="facility" onchange="this.form.submit()">
            <?php while ($facility = mysqli_fetch_assoc($facilities)) {?>
                <option value="<?php echo $facility['Name'] ?>" <?php if ($facilityName == $facility['Name']) echo 'selected' ?>><?php echo $facility['Name'] ?></option>
            <?php }; ?>
        </select>
        <label for="start_date">Start date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo $startDate ?>">
        <label for="end_date">End date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo $endDate ?>">
        <button type="submit">Submit</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Role</th>
                <th>Total Working Hours</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($statement->fetch()) { ?>
                <tr>
                    <td><?php echo $role ?></td>
                    <td><?php echo $totalHours ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
    </body>
</html>