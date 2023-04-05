<?php 
require_once '../../database.php';

if (
    isset($_GET['FacilityID']) && 
    isset($_GET['EmployeeID']) &&
    isset($_GET['ContractID'])
) {
    $FacilityID = $_GET['FacilityID'];
    $EmployeeID = $_GET['EmployeeID'];
    $ContractID = $_GET['ContractID'];
    
    $stmt = $conn->prepare("DELETE FROM Employment WHERE FacilityID = $FacilityID AND EmployeeID = $EmployeeID AND ContractID = $ContractID");

    if ($stmt->execute()) {
        header("Location: ./index.php");
    } else {
        echo "Something went wrong. Please try again later.";
    }
}
?>
