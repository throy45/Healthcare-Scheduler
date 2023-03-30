<?php require_once '../../database.php'; 
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
        <input type="number" name="EmployeeID" id="EmployeeID" value=100 require><br>
        <label for="FirstName">First Name</label><br>
        <input type="text" name="FirstName" id="FirstName" value='John'><br>
        <label for="LastName">Last Name</label><br>
        <input type="text" name="LastName" id="LastName" value='Doe'><br>
        <label for="Role">Role</label><br>
        <input type="text" name="Role" id="Role" value='Doctor'><br>
        <label for="DateOfBirth">Date of Birth</label><br>
        <input type="date" name="DateOfBirth" id="DateOfBirth" value='2023-02-27'><br>
        <label for="MedicareNumber">Medicare Number</label><br>
        <input type="text" name="MedicareNumber" id="MedicareNumber" value=1><br>
        <label for="Email">Email</label><br>
        <input type="text" name="Email" id="Email" value='john@gmail.com'><br>
        <label for="Citizenship">Citizenship</label><br>
        <input type="text" name="Citizenship" id="Citizenship" value='Canadian'><br>
        <label for="PhoneNumber">Phone Number</label><br>
        <input type="text" name="PhoneNumber" id="PhoneNumber" value=5141231234><br>
        <label for="Address">Address</label><br>
        <input type="text" name="Address" id="Address" value='Guy'><br><br>
        <button type="submit">Add</button><br><br>
    </form>
    <a href="./">Back to Employee list</a>
</body>
</html>