<?php
require_once '../../database.php';

if (isset($_GET['EmployeeID']) && isset($_GET['FacilityID']) && isset($_GET['VaccineID'])) {
    $EmployeeID = $_GET['EmployeeID'];
    $FacilityID = $_GET['FacilityID'];
    $VaccineID = $_GET['VaccineID'];

    $statement = $conn->prepare("DELETE FROM Vaccines WHERE EmployeeID = ? AND FacilityID = ? AND VaccineID = ?");
    $statement->bind_param("iii", $EmployeeID, $FacilityID, $VaccineID);
    $statement->execute();

    header("Location: ./index.php");
}
?>
