<?php require_once '../../database.php';
if (isset($_GET['FacilityID']) && !empty($_GET['FacilityID'])) {
    $EmployeeID = $_GET['FacilityID'];
    $statement = $conn->prepare("DELETE FROM facilities WHERE FacilityID = $FacilityID");
    $statement->execute();
    header("Location: ./index.php");
};
?>