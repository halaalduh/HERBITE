<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userID = $_SESSION['user_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $categoryID = $_POST['categoryID'];

    $photoFileName = "default.png";
    $videoFilePath = "";

    $stmt = $conn->prepare("INSERT INTO recipe (userID, categoryID, name, description, photoFileName, videoFilePath)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $userID, $categoryID, $name, $description, $photoFileName, $videoFilePath);
    $stmt->execute();

    header("Location: myRecipes.php");
    exit();
}
?>