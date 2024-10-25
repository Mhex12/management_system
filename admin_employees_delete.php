<?php
include('config.php');
$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header('Location: admin_index.php');
exit();
?>
