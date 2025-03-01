<?php require_once '../../database.php'; 
  if (
    isset($_POST["FacilityID"]) && 
    isset($_POST["Name"]) && 
    isset($_POST["Type"]) && 
    isset($_POST["Capacity"]) && 
    isset($_POST["WebAddress"]) && 
    isset($_POST["PhoneNumber"]) && 
    isset($_POST["Address"]) && 
    isset($_POST["PostalCode"])
  ) {
    $FacilityID = $_POST["FacilityID"];
    $Name = $_POST["Name"];
    $Type = $_POST["Type"];
    $Capacity = $_POST["Capacity"];
    $WebAddress = $_POST["WebAddress"];
    $PhoneNumber = $_POST["PhoneNumber"];
    $Address = $_POST["Address"];
    $PostalCode = $_POST["PostalCode"];

    $stmt = $conn->prepare("INSERT INTO Facilities (FacilityID, Name, Type, Capacity, WebAddress, PhoneNumber, Address, PostalCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississss", $FacilityID, $Name, $Type, $Capacity, $WebAddress, $PhoneNumber, $Address, $PostalCode);
    try {
      $stmt->execute();
      header("Location: ./index.php");
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
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
  <title>Create Facility</title>
</head>
<body>
  <h1>Add Facility</h1>
  <form action="./create.php" method="post">
    <label for="FacilityID">Facility ID</label><br>
    <input type="number" name="FacilityID" id="FacilityID" required><br>
    <label for="Name">Name</label><br>
    <input type="text" name="Name" id="Name" required><br>
    <label for="Type">Type</label><br>
    <select name="Type" id="Type" required>
      <option value="Hospital">Hospital</option>
      <option value="CLSC">CLSC</option>
      <option value="Clinic">Clinic</option>
      <option value="Pharmacy">Pharmacy</option>
      <option value="Special Installment">Special instalment</option>
    </select><br>
    <label for="Capacity">Capacity</label><br>
    <input type="number" name="Capacity" id="Capacity" required><br>
    <label for="WebAddress">Web Address</label><br>
    <input type="text" name="WebAddress" id="WebAddress"><br>
    <label for="PhoneNumber">Phone Number</label><br>
    <input type="text" name="PhoneNumber" id="PhoneNumber"><br>
    <label for="Address">Address</label><br>
    <input type="text" name="Address" id="Address" required><br>
    <label for="PostalCode">Postal Code</label><br>
    <input type="text" name="PostalCode" id="PostalCode" required><br><br>
    <button type="submit">Add</button><br><br>
  </form>
  <a href="./index.php">Back to Facility list</a>
</body>
</html>