<?php require_once '../../database.php'; 
    include '../header.php';

// Get all schedules for a specific employee
if (isset($_GET['employeeID']) && !empty($_GET['employeeID'])) {
    $employeeID = $_GET['employeeID'];
    $result = $conn->query("SELECT * FROM Schedule WHERE EmployeeID = $employeeID");
    $schedules = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Get all schedules for all employees
    $result = $conn->query("SELECT * FROM Schedule");
    $schedules = $result->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../pages/style.css">
    <title>Schedule</title>
</head>
<body>
    <h1>Schedule</h1>
    <a href="./add.php">Add a Schedule</a>
    <?php if (count($schedules) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Facility ID</th>
                    <th>Employee ID</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $schedule): ?>
                    <tr>
                        <td><?php echo $schedule['FacilityID']; ?></td>
                        <td><?php echo $schedule['EmployeeID']; ?></td>
                        <td><?php echo $schedule['Date']; ?></td>
                        <td><?php echo $schedule['StartTime']; ?></td>
                        <td><?php echo $schedule['EndTime']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No schedules found.</p>
    <?php endif; ?>
    <br>
    <a href="../../index.php">Back to main page</a>
</body>
</html>
