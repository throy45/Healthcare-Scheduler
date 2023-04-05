<?php require_once '../../database.php'; 

if (isset($_GET['FacilityID']) && !empty($_GET['FacilityID']) && isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID']) && isset($_GET['Date']) && !empty($_GET['Date'])) {
    $FacilityID = $_GET['FacilityID'];
    $EmployeeID = $_GET['EmployeeID'];
    $Date = $_GET['Date'];
    $result = $conn->query("SELECT * FROM EmailLog WHERE FacilityID = $FacilityID AND EmployeeID = $EmployeeID AND Date = '$Date'");
    $row = $result->fetch_assoc();
}

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

    $stmt = $conn->prepare("UPDATE EmailLog SET Subject='$Subject', Body='$Body' WHERE FacilityID=$FacilityID AND EmployeeID=$EmployeeID AND Date='$Date'");

    if ($stmt->execute()){
        header("Location: ./index.php");
    } else {
        echo "Something went wrong. Please try again later.";
        header("Location: ./edit.php?FacilityID=".$FacilityID."&EmployeeID=".$EmployeeID."&Date=".$Date);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit an Email Log Entry</title>
</head>
<body>
    <h1>Edit Email Log Entry</h1>
    <form action="./edit.php" method="post">
        <label for="FacilityID">Facility ID</label><br>
        <input type="number" name="FacilityID" id="FacilityID" readonly="readonly" value="<?php echo $row["FacilityID"] ?>" required><br>
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" readonly="readonly" value="<?php echo $row["EmployeeID"] ?>" required><br>
        <label for="Date">Date</label><br>
        <input type="date" name="Date" id="Date" readonly="readonly" value="<?php echo $row["Date"] ?>" required><br>
        <label for="Subject">Subject</label><br>
        <input type="text" name="Subject" id="Subject" value="<?php echo $row["Subject"] ?>" required><br>
        <label for="Body">Body</label><br>
        <textarea name="Body" id="Body" rows="10" cols="50" required><?php echo $row["Body"] ?></textarea><br><br>
        <button type="submit">Update</button><br><br>
    </form>
    <a href="./">Back to Email Log list</a>
</body>
</html>