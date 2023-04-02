<?php require_once '../../database.php'; 
if (isset($_GET['PostalCode']) && !empty($_GET['PostalCode'])) {
    $PostalCode = urldecode($_GET['PostalCode']);
    $result = $conn->query("SELECT * FROM PostalCodes WHERE PostalCode = '$PostalCode'");
    $row = $result->fetch_assoc();
}
if (
    isset($_POST["PostalCode"]) && 
    isset($_POST["City"]) && 
    isset($_POST["Province"])
    ) {
        
    $PostalCode = $_POST["PostalCode"];
    $City = $_POST["City"];
    $Province = $_POST["Province"];

    $stmt = $conn->prepare("UPDATE PostalCodes SET City='$City', Province='$Province' WHERE PostalCode='$PostalCode'");

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
    <title>Edit Postal Code</title>
</head>
<body>
    <h1>Edit Postal Code</h1>
    <form action="./edit.php" method="post">
        <label for="PostalCode">Postal Code</label><br>
        <input type="text" name="PostalCode" id="PostalCode" maxlength="7" readonly="readonly" value="<?php echo urldecode($row["PostalCode"])  ?>" required><br>
        <label for="City">City</label><br>
        <input type="text" name="City" id="City" value="<?php echo $row["City"]  ?>" required><br>
        <label for="Province">Province</label><br>
        <input type="text" name="Province" id="Province" value="<?php echo $row["Province"]  ?>" required><br>
        <br><br>
        <button type="submit">Edit</button><br><br>
    </form>
    <a href="./index.php">Back to Postal Code list</a>
</body>
</html>