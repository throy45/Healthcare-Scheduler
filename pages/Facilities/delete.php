<?php require_once '../../database.php';
if (isset($_GET['FacilityID']) && !empty($_GET['FacilityID'])) {
    $FacilityID = $_GET['FacilityID'];
    $statement = $conn->prepare("DELETE FROM Facilities WHERE FacilityID = $FacilityID");
    $statement->execute();
    header("Location: ./index.php");
};
?>