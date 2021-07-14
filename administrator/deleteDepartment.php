<?php
session_start();
include '../connection.php';

$toDelete = $_GET["DeptId"];

$sql = "DELETE FROM department WHERE Dept_ID = '".$toDelete."'";

if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Department Deleted Successfully")</script>';
    header('location:departments.php');
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
?>