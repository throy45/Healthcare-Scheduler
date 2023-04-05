<?php 
require_once '../../database.php';

if (isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID']) 
    && isset($_GET['InfectionID']) && !empty($_GET['InfectionID'])) {
    $EmployeeID = $_GET['EmployeeID'];
    $InfectionID = $_GET['InfectionID'];
    
    $statement = $conn->prepare("DELETE FROM Infections WHERE EmployeeID = '$EmployeeID' AND InfectionID = '$InfectionID'");
    
    if ($statement->execute()) {
        header("Location: ./index.php");
    } else {
        echo "Something went wrong. Please try again later.";
    }
}
?>
