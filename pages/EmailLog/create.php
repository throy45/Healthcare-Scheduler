<?php require_once '../../database.php'; 
if (
    isset($_POST["FacilityID"]) && 
    isset($_POST["EmployeeID"]) && 
    isset($_POST["Date"]) && 
    isset($_POST["Subject"]) && 
    isset($_POST["Body"])
    ) {
        
    $FacilityID = $_POST["FacilityID"];
    $EmployeeID = $_POST["EmployeeID"];
    $Date = $_POST["Date"];
    $Subject = $_POST["Subject"];
    $Body = $_POST["Body"];

    $stmt = $conn->prepare("INSERT INTO EmailLog (FacilityID, EmployeeID, Date, Subject, Body) VALUES ($FacilityID, $EmployeeID, '$Date', '$Subject', '$Body')");


    if ($stmt->execute()){
        header("Location: ./index.php");
    } else {
        echo "Something went wrong. Please try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Email Log</title>
</head>
<body>
    <h1>Add Email Log</h1>
    <form action="./create.php" method="post">
        <label for="FacilityID">Facility ID</label><br>
        <input type="number" name="FacilityID" id="FacilityID" required><br>
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" required><br>
        <label for="Date">Date</label><br>
        <input type="date" name="Date" id="Date" required><br>
        <label for="Subject">Subject</label><br>
        <input type="text" name="Subject" id="Subject" required><br>
        <label for="Body">Body</label><br>
        <textarea name="Body" id="Body" cols="30" rows="10" required></textarea><br><br>
        <button type="submit">Add</button><br><br>
    </form>
    <a href="./">Back to Email Log list</a>
</body>
</html>