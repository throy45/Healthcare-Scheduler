<?php
require_once '../../database.php';

if (isset($_GET['FacilityID']) && isset($_GET['EmployeeID']) && isset($_GET['StartDate'])) {
    $FacilityID = $_GET['FacilityID'];
    $EmployeeID = $_GET['EmployeeID'];
    $StartDate = $_GET['StartDate'];

    $statement = $conn->prepare("DELETE FROM Managers WHERE FacilityID = ? AND EmployeeID = ? AND StartDate = ?");
    $statement->bind_param("iii", $FacilityID, $EmployeeID, $StartDate);
    $statement->execute();

    header("Location: ./index.php");
    exit();
}
?>
