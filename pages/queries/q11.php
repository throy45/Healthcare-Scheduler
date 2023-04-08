<?php 
require_once '../../database.php'; 
include '../header.php';

// Get the selected facility name from the drop-down menu
if (isset($_POST['facility'])) {
    $facilityName = $_POST['facility'];
} else {
    $facilityName = "Hospital Maisonneuve Rosemont";
}


// Get a list of all medical facilities
$facilities = $conn->query("SELECT DISTINCT Name FROM Facilities ORDER BY Name");


// Prepare and execute the SQL query with the selected facility
$statement = $conn->prepare('SELECT e.FName, e.LName, e.Role FROM Facilities f, Employment em, Employees e, Schedule s
                             WHERE f.FacilityID = em.FacilityID AND em.EmployeeID = e.EmployeeID AND e.EmployeeID = s.EmployeeID 
                             AND f.Name = ? AND e.Role IN ("Nurse", "Doctor") AND DATEDIFF(CURDATE(), s.Date) <= 14
                             GROUP BY e.EmployeeID ORDER BY e.Role, e.FName');
$statement->bind_param('s', $facilityName);
$statement->execute();

// Bind the results to variables
$statement->bind_result($fName, $lName, $role);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Query 11</title>
</head>
<body>
    <h1>List of working doctors in the last 2 weeks</h1>

    <!-- Create the drop-down menu -->
    <form method="post">
        <label for="facility">Select a medical facility:</label>
        <select name="facility" id="facility" onchange="this.form.submit()">
            <?php while ($facility = mysqli_fetch_assoc($facilities)) {?>
                <option value="<?php echo $facility['Name'] ?>" <?php if ($facilityName == $facility['Name']) echo 'selected' ?>><?php echo $facility['Name'] ?></option>
            <?php }; ?>
        </select>
    </form>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($statement->fetch()) { ?>
                <tr>
                    <td><?php echo $fName ?></td>
                    <td><?php echo $lName ?></td>
                    <td><?php echo $role ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>
