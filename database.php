<?php
/*
$servername = 'localhost:3306';
$username = 'root';
$password = '';
$database = 'hfests';
*/


$servername = 'lcc5531.encs.concordia.ca';
$username = 'lcc55314';
$password = '34h60z46';
$database = 'lcc55314';

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "<script>
window.onload = function() {
  console.log('Connected successfully');
};
</script>";
?>