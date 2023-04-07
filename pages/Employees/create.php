<?php require_once '../../database.php'; 
    include '../header.php';
  
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
    isset($_POST["Address"])
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

    $stmt = $conn->prepare("INSERT INTO employees (EmployeeID, FName, LName, Role, DoBirth, MedicareNumber, Email, Citizenship, PhoneNumber, Address) VALUES ($EmployeeID, '$FirstName', '$LastName', '$Role', '$date_of_birth', '$medicare_number', '$email', ' $citizenship', '$phone_number', '$address')");


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
    <title>Create an Employee</title>
</head>
<body>
    <h1>Add Employee</h1>
    <form action="./create.php" method="post">
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" require><br>
        <label for="FirstName">First Name</label><br>
        <input type="text" name="FirstName" id="FirstName"><br>
        <label for="LastName">Last Name</label><br>
        <input type="text" name="LastName" id="LastName"><br>
        <label for="Role">Role</label><br>
        <select name="Role" id="Role">
            <option value="Nurse">Nurse</option>
            <option value="Doctor">Doctor</option>
            <option value="Cashier">Cashier</option>
            <option value="Pharmacist">Pharmacist</option>
            <option value="Receptionist">Receptionist</option>
            <option value="Admin personnel">Administrative Personnel</option>
            <option value="Security personnel">Security Personnel</option>
            <option value="Regular employee">Regular Employee</option>
        </select><br>
        <label for="DateOfBirth">Date of Birth</label><br>
        <input type="date" name="DateOfBirth" id="DateOfBirth"><br>
        <label for="MedicareNumber">Medicare Number</label><br>
        <input type="text" name="MedicareNumber" id="MedicareNumber"><br>
        <label for="Email">Email</label><br>
        <input type="text" name="Email" id="Email"><br>
        <label for="Citizenship">Citizenship</label><br>
        <input type="text" name="Citizenship" id="Citizenship"><br>
        <label for="PhoneNumber">Phone Number</label><br>
        <input type="text" name="PhoneNumber" id="PhoneNumber"><br>
        <label for="Address">Address</label><br>
        <input type="text" name="Address" id="Address"><br><br>
        <button type="submit">Add</button><br><br>
    </form>
    <a href="./">Back to Employee list</a>
</body>
</html>