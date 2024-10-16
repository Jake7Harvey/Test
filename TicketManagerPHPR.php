<?php
$conn = new mysqli("localhost", "root", " ", "userdata");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT id, title, description, status FROM tickets ORDER BY id ASC";
$result = $conn->query($sql);

$tickets = array();
while($row = $result->fetch_assoc()) {
  $tickets[] = $row;
}

header('Content-Type: application/json');
echo json_encode($tickets);
$conn->close();
?>