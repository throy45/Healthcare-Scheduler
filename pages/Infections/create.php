<?php require_once '../../database.php'; 
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
    
    $stmt = $conn->prepare("INSERT INTO Infections (EmployeeID, InfectionID, Type, Date) VALUES ($EmployeeID, $InfectionID, '$Type', '$Date')");

    if ($stmt->execute()) {
      header("Location: ./index.php");
    } else {
      echo "Something went wrong. Please try again later.";
    }
  }
  include '../header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create an Infection</title>
</head>
<body>
  <h1>Add Infection</h1>
  <form action="./create.php" method="post">
    <label for="EmployeeID">Employee ID</label><br>
    <input type="number" name="EmployeeID" id="EmployeeID" required><br>
    <label for="InfectionID">InfectionID</label><br>
    <input type="number" name="InfectionID" id="InfectionID" required><br>
    <label for="Type">Type</label><br>
        <select name="Type" id="Type" required>
          <option value="COVID-19">COVID-19</option>
          <option value="SARS-Cov-2 Variant">SARS-Cov-2 Variant</option>
          <option value="Flu">Flu</option>
          <option value="Other">Other</option>
        </select><br>
    <label for="Date">Date</label><br>
    <input type="date" name="Date" id="Date" required><br>
    <button type="submit">Add</button><br><br>
  </form>
  <a href="./index.php">Back to Infections list</a>
</body>
</html>
