<?php require_once '../../database.php'; 
    include '../header.php';

    // Get schedules for a specific employee between specific dates
    if (isset($_GET['employeeID']) && !empty($_GET['employeeID'])) {
        $employeeID = $_GET['employeeID'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $result = $conn->query("SELECT f.Name, s.Date, s.StartTime, s.EndTime
                                FROM Facilities f, Employees e, Schedule s
                                WHERE e.EmployeeID = s.EmployeeID AND
                                s.FacilityID = f.FacilityID AND
                                e.EmployeeID = $employeeID AND
                                s.Date BETWEEN '$startDate' AND '$endDate'
                                ORDER BY f.Name, s.Date, s.StartTime");
        $schedules = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Get all schedules for all employees
        $result = $conn->query("SELECT f.Name, s.Date, s.StartTime, s.EndTime
                                FROM Facilities f, Employees e, Schedule s
                                WHERE e.EmployeeID = s.EmployeeID AND
                                s.FacilityID = f.FacilityID
                                ORDER BY f.Name, s.Date, s.StartTime");
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
    <title>Query 8</title>
</head>
<body>
    <h1><?php if (isset($_GET['employeeID']) && !empty($_GET['employeeID'])) { echo "Schedule for employee $employeeID";} else {echo "Search schedule for employee ID";} ?></h1>
    <form method="get">
        <label for="employeeID">Search by Employee ID:</label>
        <input type="text" id="employeeID" name="employeeID" value="<?php if (isset($_GET['employeeID']) && !empty($_GET['employeeID'])) {echo "$employeeID";}?>">
        <br>
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startDate" value="2023-01-01">
        <br>
        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" name="endDate" value="2023-12-31">
        <br>
        <button type="submit">Search</button>
    </form>
    <br>
    <?php if (count($schedules) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Facility Name</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $schedule): ?>
                    <tr>
                        <td><?php echo $schedule['Name']; ?></td>
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