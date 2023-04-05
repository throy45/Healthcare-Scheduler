<?php require_once '../../database.php';

if (isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID'])) {
    $EmployeeID = $_GET['EmployeeID'];
    $statement = $conn->prepare("DELETE FROM Manager WHERE EmployeeID = $EmployeeID");
    $statement->execute();
    header("Location: ./index.php");
};
?>
