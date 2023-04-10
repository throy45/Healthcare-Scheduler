<?php require_once '../../database.php'; 
  if (isset($_GET['FacilityID']) && !empty($_GET['FacilityID'])) {
      $FacilityID = $_GET['FacilityID'];
      $result = $conn->query("SELECT * FROM Facilities WHERE FacilityID = $FacilityID");
      $row = $result->fetch_assoc();
  }
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

    $stmt = $conn->prepare("UPDATE Facilities SET Name='$Name', Type='$Type', Capacity=$Capacity, WebAddress='$WebAddress', PhoneNumber='$PhoneNumber', Address='$Address', PostalCode='$PostalCode' WHERE FacilityID=$FacilityID");

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
  <title>Edit Facility</title>
</head>
<body>
  <h1>Edit Facility</h1>
  <form action="./edit.php" method="post">
    <label for="FacilityID">Facility ID</label><br>
    <input type="number" name="FacilityID" id="FacilityID" readonly="readonly" value="<?php echo $row["FacilityID"]  ?>" required><br>
    <label for="Name">Name</label><br>
    <input type="text" name="Name" id="Name" value="<?php echo $row["Name"] ?>" required><br>
    <label for="Type">Type</label><br>
    <select name="Type" id="Type" required>
      <option value="Hospital" <?php if($row["Type"]=="Hospital") echo "selected"; ?>>Hospital</option>
      <option value="CLSC" <?php if($row["Type"]=="CLSC") echo "selected"; ?>>CLSC</option>
      <option value="Clinic" <?php if($row["Type"]=="Clinic") echo "selected"; ?>>Clinic</option>
      <option value="Pharmacy" <?php if($row["Type"]=="Pharmacy") echo "selected"; ?>>Pharmacy</option>
      <option value="Special Instalment" <?php if($row["Type"]=="Special instalment") echo "selected"; ?>>Special Installment</option>
    </select><br>
    <label for="Capacity">Capacity</label><br>
    <input type="number" name="Capacity" id="Capacity" value="<?php echo $row["Capacity"]  ?>" required><br>
    <label for="WebAddress">Web Address</label><br>
    <input type="text" name="WebAddress" id="WebAddress" value="<?php echo $row["WebAddress"]  ?>" required><br>
    <label for="PhoneNumber">Phone Number</label><br>
    <input type="text" name="PhoneNumber" id="PhoneNumber" value="<?php echo $row["PhoneNumber"]  ?>" required><br>
    <label for="Address">Address</label><br>
    <input type="text" name="Address" id="Address" value="<?php echo $row["Address"]  ?>" required><br>
    <label for="PostalCode">Postal Code</label><br>
    <input type="text" name="PostalCode" id="PostalCode" readonly="readonly" value="<?php echo $row["PostalCode"] ?>" required><br><br>
    <button type="submit">Update</button><br><br>
  </form>
  <a href="./index.php">Back to Facility list</a>
</body>
</html>