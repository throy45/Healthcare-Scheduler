<?php require_once '../../database.php';
if (isset($_GET['EmployeeID']) && !empty($_GET['EmployeeID'])) {
    $EmployeeID = $_GET['EmployeeID'];
    $statement = $conn->prepare("DELETE FROM Infections WHERE EmployeeID = $EmployeeID");
    $statement->execute();
    header("Location: ./index.php");
};
?>

<?php require_once '../../database.php';
if (isset($_GET['InfectionID']) && !empty($_GET['InfectionID'])) {
    $EmployeeID = $_GET['InfectionID'];
    $statement = $conn->prepare("DELETE FROM Infections WHERE InfectionID = $InfectionID");
    $statement->execute();
    header("Location: ./index.php");
};
?>