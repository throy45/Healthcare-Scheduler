<?php require_once '../../database.php'; 
    if (
        isset($_POST["PostalCode"]) && 
        isset($_POST["City"]) && 
        isset($_POST["Province"])
        ) {
            
        $postal_code = $_POST["PostalCode"];
        $city = $_POST["City"];
        $province = $_POST["Province"];

        $stmt = $conn->prepare("INSERT INTO PostalCodes (PostalCode, City, Province) VALUES ('$postal_code', '$city', '$province')");

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
    <title>Add Postal Code</title>
</head>
<body>
    <h1>Add Postal Code</h1>
    <form action="./create.php" method="post">
        <label for="PostalCode">Postal Code</label><br>
        <input type="text" name="PostalCode" id="PostalCode" maxlength="7" required><br>
        <label for="City">City</label><br>
        <input type="text" name="City" id="City" required><br>
        <label for="Province">Province</label><br>
        <input type="text" name="Province" id="Province" required><br>
        <br>
        <button type="submit">Add</button><br><br>
    </form>
    <a href="./index.php">Back to Postal Code list</a>
</body>
</html>