<?php require_once 'database.php'; 
    include './pages/header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HFESTS main page</title>
</head>
<body>
    <h1>Welcome to HFESTS web app</h1>
    <table>
        <thead>
            <tr>
                <th>Main tables</th>
                <th>Special tables</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <a href="./pages/EmailLog/index.php">EmailLog</a><br>
                </td>
                <td>
                    <a href="./pages/queries/q6.php">Query 6</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="./pages/Employees/index.php">Employees</a><br>
                </td>
                <td>
                    <a href="./pages/queries/q7.php">Query 7</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="./pages/Employment/index.php">Employment</a><br>
                </td>
                <td>
                    <a href="./pages/queries/q8.php">Query 8</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="./pages/Facilities/index.php">Facilities</a><br>
                </td>
                <td>
                    <a href="./pages/queries/q9.php">Query 9</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="./pages/Infections/index.php">Infections</a><br>
                </td>
                <td>
                    <a href="./pages/queries/q10.php">Query 10</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="./pages/Managers/index.php">Managers</a><br>
                </td>
                <td>
                    <a href="./pages/queries/q11.php">Query 11</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="./pages/Managing/index.php">Managing</a><br>
                </td>
                <td>
                    <a href="./pages/queries/q12.php">Query 12</a>
                </td>
            </tr>
        </tbody>    
    
    <a href="./pages/PostalCodes/index.php">Postal Codes</a><br>
    <a href="./pages/Schedule/index.php">Schedule</a><br>
    <a href="./pages/Vaccines/index.php">Vaccines</a><br>
    </table>
</body>
</html>