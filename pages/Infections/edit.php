<?php require_once '../../database.php'; 
    include '../header.php';
  
if (isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID']) && isset($_GET['InfectionID']) && !empty($_GET['InfectionID'])) {
  $EmployeeID = $_GET['EmployeeID'];
  $InfectionID = $_GET['InfectionID'];
  $result = $conn->query("SELECT * FROM Infections WHERE EmployeeID = $EmployeeID AND InfectionID = $InfectionID");
  $row = $result->fetch_assoc();
} else if (
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
        <input type="hidden" name="EmployeeID" value="<?php echo $row["EmployeeID"] ?>">
        <input type="hidden" name="InfectionID" value="<?php echo $row["InfectionID"] ?>">
        <label for="Type">Type</label><br>
        <select name="Type" id="Type" required>
          <option value="COVID-19" <?php if($row["Type"]=="COVID-19") echo "selected"; ?>>COVID-19</option>
          <option value="SARS-Cov-2 Variant" <?php if($row["Type"]=="SARS-Cov-2 Variant") echo "selected"; ?>>SARS-Cov-2 Variant</option>
          <option value="Flu" <?php if($row["Type"]=="Flu") echo "selected"; ?>>Flu</option>
          <option value="Other" <?php if($row["Type"]=="Other") echo "selected"; ?>>Other</option>
        </select><br>
        <label for="Date">Date</label><br>
        <input type="date" name="Date" id="Date" value="<?php echo $row["Date"] ?>"><br><br>
        <button type="submit">Update</button><br><br>
    </form>
  <a href="./index.php">Back to Infections list</a>
</body>
</html>