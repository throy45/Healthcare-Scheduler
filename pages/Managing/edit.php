<?php require_once '../../database.php'; 
    include '../header.php';
  

if (isset($_GET['FacilityID']) && isset($_GET['EmployeeID']) && isset($_GET['StartDate']) && !empty($_GET['FacilityID']) && !empty($_GET['EmployeeID']) && !empty($_GET['StartDate'])) {
    $FacilityID = $_GET['FacilityID'];
    $EmployeeID = $_GET['EmployeeID'];
    $StartDate = $_GET['StartDate'];
    $result = $conn->query("SELECT * FROM Managing WHERE FacilityID = $FacilityID AND EmployeeID = $EmployeeID AND StartDate = '$StartDate'");
    $row = $result->fetch_assoc();
}
if (
    isset($_POST["FacilityID"]) && 
    isset($_POST["EmployeeID"]) && 
    isset($_POST["StartDate"]) && 
    isset($_POST["EndDate"])
) {
    $FacilityID = $_POST["FacilityID"];
    $EmployeeID = $_POST["EmployeeID"];
    $StartDate = $_POST["StartDate"];
    $EndDate = $_POST["EndDate"];

    $stmt = $conn->prepare("UPDATE Managing SET EndDate='$EndDate' WHERE FacilityID=$FacilityID AND EmployeeID=$EmployeeID AND StartDate='$StartDate'");

    if ($stmt->execute()){
        header("Location: ./index.php");
    } else {
        echo "Something went wrong. Please try again later.";
        header("Location: ./edit.php?FacilityID=".$FacilityID."&EmployeeID=".$EmployeeID."&StartDate=".$StartDate);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Managing relation</title>
</head>
<body>
    <h1>Edit Managing relation</h1>
    <form action="./edit.php" method="post">
        <label for="FacilityID">Facility ID</label><br>
        <input type="number" name="FacilityID" id="FacilityID" readonly="readonly" value="<?php echo $row["FacilityID"]  ?>" require><br>
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" readonly="readonly" value="<?php echo $row["EmployeeID"]  ?>" require><br>
        <label for="StartDate">Start Date</label><br>
        <input type="date" name="StartDate" id="StartDate" readonly="readonly" value="<?php echo $row["StartDate"]  ?>" require><br>
        <label for="EndDate">End Date</label><br>
        <input type="date" name="EndDate" id="EndDate" value="<?php echo $row["EndDate"] ?>"><br><br>
        <button type="submit">Update</button><br><br>
    </form>
    <a href="./">Back to Managing relation list</a>
</body>
</html>
