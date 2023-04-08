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

// Prepare the SQL query
$statement = $conn->prepare('SELECT e.Role, e.FName, e.LName, em.StartDate, e.DoBirth, e.MedicareNumber, e.PhoneNumber, e.Address, p.City, p.Province, p.PostalCode, e.Citizenship, e.Email
                             FROM  Facilities f, Employment em, Employees e, PostalCodes p
                             WHERE f.FacilityID = em.FacilityID AND
                                   em.EmployeeID = e.EmployeeID AND
                                   e.PostalCode = p.PostalCode AND
                                   f.Name = ? AND
                                   em.EndDate IS NULL
                             ORDER BY e.Role, e.FName, e.LName');

// Bind the selected facility name to the SQL query
$statement->bind_param('s', $facilityName);

// Execute the SQL query
$statement->execute();
mysqli_stmt_bind_result($statement, $row['Role'], $row['FirstName'], $row['LastName'], $row['StartDate'], $row['DateOfBirth'], $row['MedicareNumber'], $row['PhoneNumber'], $row['Address'], $row['City'], $row['Province'], $row['PostalCode'], $row['Citizenship'], $row['Email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Query 7</title>
</head>
<body>
    <h1>List of Employees working at <?php echo $facilityName ?></h1>

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
                <th>Role</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Start Date</th>
                <th>Date of Birth</th>
                <th>Medicare Number</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>City</th>
                <th>Province</th>
                <th>Postal Code</th>
                <th>Citizenship</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php while (mysqli_stmt_fetch($statement)) {?>
                <tr>
                    <td><?php echo $row['Role'] ?></td>
                    <td><?php echo $row['FirstName'] ?></td>
                    <td><?php echo $row['LastName'] ?></td>
                    <td><?php echo $row['StartDate'] ?></td>
                    <td><?php echo $row['DateOfBirth'] ?></td>
                    <td><?php echo $row['MedicareNumber'] ?></td>
                    <td><?php echo $row['PhoneNumber'] ?></td>
                    <td><?php echo $row['Address'] ?></td>
                    <td><?php echo $row['City'] ?></td>
                    <td><?php echo $row['Province'] ?></td>
                    <td><?php echo $row['PostalCode'] ?></td>
                    <td><?php echo $row['Citizenship'] ?></td>
                    <td><?php echo $row['Email'] ?></td><td>
                </tr>
            <?php }; ?>
        </tbody>
    </table>
    <a href="../../index.php">Back to main page</a>
</body>
</html>