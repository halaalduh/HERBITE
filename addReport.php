<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$recipeID = $_POST['recipeID'];
$userID = $_SESSION['user_id'];

$check = $conn->prepare("SELECT * FROM report WHERE userID=? AND recipeID=?");
$check->bind_param("ii", $userID, $recipeID);
$check->execute();
$result = $check->get_result();

if ($result->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO report (userID, recipeID) VALUES (?, ?)");
    $stmt->bind_param("ii", $userID, $recipeID);
    $stmt->execute();
}

header("Location: viewRecipe.php?id=" . $recipeID);
exit();
?>