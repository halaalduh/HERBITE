<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != "admin") {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: admin.php");
    exit();
}

$reportID  = isset($_POST['reportID']) ? (int)$_POST['reportID'] : 0;
$creatorID = isset($_POST['creatorID']) ? (int)$_POST['creatorID'] : 0;
$action    = isset($_POST['action']) ? $_POST['action'] : '';

if ($reportID <= 0 || $creatorID <= 0 || ($action != 'block' && $action != 'dismiss')) {
    header("Location: admin.php");
    exit();
}

/* dismiss فقط */
if ($action == "dismiss") {
    $stmt = $conn->prepare("DELETE FROM report WHERE id = ?");
    $stmt->bind_param("i", $reportID);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php");
    exit();
}

/* block */
if ($action == "block") {

    /* 1) get user info */
    $stmt = $conn->prepare("SELECT firstName, lastName, emailAddress FROM users WHERE id = ?");
    $stmt->bind_param("i", $creatorID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        header("Location: admin.php");
        exit();
    }

    /* 2) add to blockeduser if not already exists */
    $stmt = $conn->prepare("SELECT id FROM blockeduser WHERE emailAddress = ?");
    $stmt->bind_param("s", $user['emailAddress']);
    $stmt->execute();
    $checkBlocked = $stmt->get_result();
    $alreadyBlocked = $checkBlocked->num_rows > 0;
    $stmt->close();

    if (!$alreadyBlocked) {
        $stmt = $conn->prepare("INSERT INTO blockeduser (firstName, lastName, emailAddress) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user['firstName'], $user['lastName'], $user['emailAddress']);
        $stmt->execute();
        $stmt->close();
    }

    /* 3) get all recipe ids for this user */
    $recipeIDs = [];
    $stmt = $conn->prepare("SELECT id FROM recipe WHERE userID = ?");
    $stmt->bind_param("i", $creatorID);
    $stmt->execute();
    $recipesResult = $stmt->get_result();

    while ($row = $recipesResult->fetch_assoc()) {
        $recipeIDs[] = (int)$row['id'];
    }
    $stmt->close();

    /* 4) delete related data for each recipe */
    foreach ($recipeIDs as $recipeID) {

        $stmt = $conn->prepare("DELETE FROM likes WHERE recipeID = ?");
        $stmt->bind_param("i", $recipeID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM comment WHERE recipeID = ?");
        $stmt->bind_param("i", $recipeID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM favourites WHERE recipeID = ?");
        $stmt->bind_param("i", $recipeID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM report WHERE recipeID = ?");
        $stmt->bind_param("i", $recipeID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM recipeingredient WHERE recipeID = ?");
        $stmt->bind_param("i", $recipeID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM recipeinstruction WHERE recipeID = ?");
        $stmt->bind_param("i", $recipeID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM recipe WHERE id = ?");
        $stmt->bind_param("i", $recipeID);
        $stmt->execute();
        $stmt->close();
    }

    /* 5) delete user's own activity */
    $stmt = $conn->prepare("DELETE FROM likes WHERE userID = ?");
    $stmt->bind_param("i", $creatorID);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM comment WHERE userID = ?");
    $stmt->bind_param("i", $creatorID);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM favourites WHERE userID = ?");
    $stmt->bind_param("i", $creatorID);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM report WHERE userID = ?");
    $stmt->bind_param("i", $creatorID);
    $stmt->execute();
    $stmt->close();

    /* 6) delete user */
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $creatorID);
    $stmt->execute();
    $stmt->close();

    /* 7) make sure the selected report is removed too */
    $stmt = $conn->prepare("DELETE FROM report WHERE id = ?");
    $stmt->bind_param("i", $reportID);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php");
    exit();
}

header("Location: admin.php");
exit();
?>