<?php require_once '../../database.php';
if (isset($_GET['PostalCode']) && !empty($_GET['PostalCode'])) {
    $PostalCode = urldecode($_GET['PostalCode']);
    $statement = $conn->prepare("DELETE FROM PostalCodes WHERE PostalCode = '$PostalCode'");
    $statement->execute();
    header("Location: ./index.php");
};
?>