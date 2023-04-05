<?php 
require_once '../../database.php'; 
if (isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID'])) {
    $EmployeeID = $_GET['EmployeeID'];
    $result = $conn->query("SELECT * FROM Infection WHERE EmployeeID = $EmployeeID");
    $row = $result->fetch_assoc();
}
if (isset($_GET['InfectionID']) && !empty($_GET['InfectionID'])) {
    $InfectionID = $_GET['InfectionID'];
    $result = $conn->query("SELECT * FROM Infection WHERE InfectionID = $InfectionID");
    $row = $result->fetch_assoc();
}
if (
  isset($_POST["EmployeeID"]) && 
  isset($_POST["InfectionID"]) && 
  isset($_POST["Type"]) && 
  isset($_POST["Date"]) 
) {
  $EmployeeID = $_POST["EmployeeID"];
  $InfectionID = $_POST["InfectionID"];
  $Type = $_POST["Type"];
  $Date = $_POST["Date"];

  $stmt = $conn->prepare("UPDATE Infections SET  InfectionID=$InfectionID, Type=$Type, Date=$Date WHERE EmployeeID=$EmployeeID");

  if ($stmt->execute()) {
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
  <title>Edit Infections</title>
</head>
<body>
  <h1>Edit Infections</h1>
  <form action="./edit.php" method="post">
    <label for="EmployeeID">Employee ID</label><br>
    <input type="number" name="EmployeeID" id="EmployeeID" readonly="readonly" value="<?php echo $row["EmployeeID"]  ?>" required><br>
    <label for="InfectionID">InfectionID</label><br>
    <input type="number" name="InfectionID" id="InfectionID" value="<?php echo $row["InfectionID"] ?>" required><br>
    <label for="Type">Type</label><br>
    <input type="text" name="Type" id="Type" value="<?php echo $row["Type"]  ?>" required><br>
    <label for="Date">Date</label><br>
    <input type="number" name="Date" id="Date" value="<?php echo $row["Date"]  ?>" required><br>
    <button type="submit">Add</button><br><br>
  </form>
  <a href="./index.php">Back to Infections list</a>
</body>
</html>