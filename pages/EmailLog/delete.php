<?php require_once '../../database.php';

if (isset($_GET['FacilityID']) && !empty($_GET['FacilityID']) 
    && isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID']) 
    && isset($_GET['Date']) && !empty($_GET['Date'])) {
        
    $FacilityID = $_GET['FacilityID'];
    $EmployeeID = $_GET['EmployeeID'];
    $Date = $_GET['Date'];
    
    $statement = $conn->prepare("DELETE FROM EmailLog WHERE FacilityID = '$FacilityID' AND EmployeeID = '$EmployeeID' AND Date = '$Date'");
    $statement->execute();
    
    header("Location: ./index.php");
}
?>
