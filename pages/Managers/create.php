<?php require_once '../../database.php';  
    if (
        isset($_POST["EmployeeID"]) 
        ) {
            
        $EmployeeID = $_POST["EmployeeID"];

        $stmt = $conn->prepare("INSERT INTO Managers (EmployeeID) VALUES ($EmployeeID)");


        if ($stmt->execute()){
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
    <title>Create a Manager</title>
</head>
<body>
    <h1>Add Manager</h1>
    <form action="./create.php" method="post">
        <label for="EmployeeID">Employee ID</label><br>
        <input type="number" name="EmployeeID" id="EmployeeID" require><br>
        <br>
        <button type="submit">Add</button><br><br>
    </form>
    <a href="./">Back to Managers list</a>
</body>
</html> 