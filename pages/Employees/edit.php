<?php require_once '../../database.php'; 
    if (isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID'])) {
        $EmployeeID = $_GET['EmployeeID'];
        $result = $conn->query("SELECT * FROM Employees WHERE EmployeeID = $EmployeeID");
        $row = $result->fetch_assoc();
    }
    if (
        isset($_POST["EmployeeID"]) && 
        isset($_POST["FirstName"]) && 
        isset($_POST["LastName"]) && 
        isset($_POST["Role"]) && 
        isset($_POST["DateOfBirth"]) && 
        isset($_POST["MedicareNumber"]) && 
        isset($_POST["Email"]) && 
        isset($_POST["Citizenship"]) && 
        isset($_POST["PhoneNumber"]) && 
        isset($_POST["Address"]) && 
        isset($_POST["PostalCode"])
        ) {
        $EmployeeID = $_POST["EmployeeID"];
        $FirstName = $_POST["FirstName"];
        $LastName = $_POST["LastName"];
        $Role = $_POST["Role"];
        $date_of_birth = $_POST["DateOfBirth"];
        $medicare_number = $_POST["MedicareNumber"];
        $email = $_POST["Email"];
        $citizenship = $_POST["Citizenship"];
        $phone_number = $_POST["PhoneNumber"];
        $address = $_POST["Address"];
        $PostalCode = $_POST["PostalCode"];

        $stmt = $conn->prepare("UPDATE Employees SET FName='$FirstName', LName='$LastName', Role='$Role', DoBirth='$date_of_birth', MedicareNumber='$medicare_number', Email='$email', Citizenship=' $citizenship', PhoneNumber='$phone_number', Address='$address',PostalCode='$PostalCode' WHERE EmployeeID=$EmployeeID");

        
        if ($stmt->execute()) {
            header("Location: ./index.php");
        } else {
            $error_message = $conn->errorInfo()[2];
            if (strpos($error_message, 'Cannot assign this employee to the facility') !== false) {
                echo "Cannot assign this employee to the facility. The facility has reached its maximum capacity.";
            } else {
                echo "Something went wrong. Please try again later.";
            }
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
    <title>Edit an Employee</title>
</head>
<body>
    <h1>Edit Employee</h1>
    <form action="./edit.php" method="post">
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" readonly="readonly"  value="<?php echo $row["EmployeeID"]  ?>" require><br>
        <label for="FirstName">First Name</label><br>
        <input type="text" name="FirstName" id="FirstName" value="<?php echo $row["FName"] ?>"><br>
        <label for="LastName">Last Name</label><br>
        <input type="text" name="LastName" id="LastName" value="<?php echo $row["LName"] ?>"><br>
        <label for="Role">Role</label><br>
        <select name="Role" id="Role" required>
            <option value="Nurse" <?php if($row["Role"]=="Nurse") echo "selected"; ?>>Nurse</option>
            <option value="Doctor" <?php if($row["Role"]=="Doctor") echo "selected"; ?>>Doctor</option>
            <option value="Cashier" <?php if($row["Role"]=="Cashier") echo "selected"; ?>>Cashier</option>
            <option value="Pharmacist" <?php if($row["Role"]=="Pharmacist") echo "selected"; ?>>Pharmacist</option>
            <option value="Receptionist" <?php if($row["Role"]=="Receptionist") echo "selected"; ?>>Receptionist</option>
            <option value="Admin personnel" <?php if($row["Role"]=="Admin personnel") echo "selected"; ?>>Administrative Personnel</option>
            <option value="Security personnel" <?php if($row["Role"]=="Security personnel") echo "selected"; ?>>Security Personnel</option>
            <option value="Regular employee" <?php if($row["Role"]=="Regular employee") echo "selected"; ?>>Regular Employee</option>
        </select><br>
        <label for="DateOfBirth">Date of Birth</label><br>
        <input type="date" name="DateOfBirth" id="DateOfBirth" value="<?php echo $row["DoBirth"] ?>"><br>
        <label for="MedicareNumber">Medicare Number</label><br>
        <input type="text" name="MedicareNumber" id="MedicareNumber" value="<?php echo $row["MedicareNumber"] ?>"><br>
        <label for="Email">Email</label><br>
        <input type="text" name="Email" id="Email" value="<?php echo $row["Email"] ?>"><br>
        <label for="Citizenship">Citizenship</label><br>
        <input type="text" name="Citizenship" id="Citizenship" value="<?php echo $row["Citizenship"] ?>"><br>
        <label for="PhoneNumber">Phone Number</label><br>
        <input type="text" name="PhoneNumber" id="PhoneNumber" value="<?php echo $row["PhoneNumber"] ?>"><br>
        <label for="Address">Address</label><br>
        <input type="text" name="Address" id="Address" value="<?php echo $row["Address"] ?>"><br>
        <label for="PostalCode">Postal Code</label><br>
        <input type="text" name="PostalCode" id="PostalCode" value="<?php echo $row["PostalCode"]?>"><br><br>
        <button type="submit">Update</button><br><br>
    </form>
    <a href="./">Back to Employee list</a>
</body>
</html>
