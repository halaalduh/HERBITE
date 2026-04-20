<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != "admin") {
    header("Location: login.html");
    exit();
}

$adminID = (int) $_SESSION['user_id'];

/* get admin info */
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $adminID);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* get reports */
$reportsQuery = "
SELECT 
    report.id AS reportID,
    recipe.id AS recipeID,
    recipe.name AS recipeName,
    users.id AS creatorID,
    users.firstName,
    users.lastName,
    users.photoFileName
FROM report
JOIN recipe ON report.recipeID = recipe.id
JOIN users ON recipe.userID = users.id
ORDER BY report.id DESC
";
$reportsResult = $conn->query($reportsQuery);

/* get blocked users */
$blockedQuery = "SELECT * FROM blockeduser ORDER BY id DESC";
$blockedResult = $conn->query($blockedQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin | HerBite</title>
  <link rel="stylesheet" href="stylesheet.css">
  <style>
    .action-form {
      display: flex;
      flex-direction: column;
      gap: 8px;
      align-items: flex-start;
    }

    .action-option {
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 0;
    }

    .action-form button {
      margin-top: 6px;
    }

    .creator-name {
      white-space: nowrap;
    }

    .recipe-link {
      font-weight: 600;
      text-decoration: none;
    }

    .recipe-link:hover {
      text-decoration: underline;
    }

    .admin-table td,
    .admin-table th {
      vertical-align: middle;
    }
  </style>
</head>
<body class="admin-page">
<div class="page">

  <header class="site-header">
    <div class="header-inner">

      <a href="index.html" class="home-link">
        <img src="home.PNG" alt="Home">
      </a>

      <div class="brand">
        <img src="logo.jpg" alt="Logo">
      </div>

      <div class="brand-title">
        <img src="title.jpg" alt="Title">
      </div>

      <div class="header-right">
        <div class="welcome">
          Welcome <span class="name"><?php echo htmlspecialchars($admin['firstName']); ?></span>
        </div>
      </div>

      <div class="logout">
        <a href="logout.php">Sign-out</a>
      </div>

    </div>
  </header>

  <main class="page-main">

    <section class="admin-card">
      <h2>My Information</h2>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($admin['firstName'] . " " . $admin['lastName']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['emailAddress']); ?></p>
    </section>

    <section class="admin-card">
      <h2>Reported Recipes</h2>

      <?php if ($reportsResult && $reportsResult->num_rows > 0) { ?>
      <table class="admin-table">
        <tr>
          <th>Recipe Name</th>
          <th>Creator</th>
          <th>Action</th>
        </tr>

        <?php while ($row = $reportsResult->fetch_assoc()) { ?>
        <tr>
          <td>
            <a class="recipe-link" href="viewRecipe.php?id=<?php echo $row['recipeID']; ?>">
              <?php echo htmlspecialchars($row['recipeName']); ?>
            </a>
          </td>

          <td class="creator-name">
            <?php echo htmlspecialchars($row['firstName'] . " " . $row['lastName']); ?>
          </td>

          <td>
            <form class="action-form" action="handleReportAction.php" method="POST">
              <input type="hidden" name="reportID" value="<?php echo $row['reportID']; ?>">
              <input type="hidden" name="recipeID" value="<?php echo $row['recipeID']; ?>">
              <input type="hidden" name="creatorID" value="<?php echo $row['creatorID']; ?>">

              <label class="action-option">
                <input type="radio" name="action" value="block" required>
                <span>Block User</span>
              </label>

              <label class="action-option">
                <input type="radio" name="action" value="dismiss" required>
                <span>Dismiss Report</span>
              </label>

              <button type="submit">Submit</button>
            </form>
          </td>
        </tr>
        <?php } ?>
      </table>
      <?php } else { ?>
        <p>No reports found.</p>
      <?php } ?>
    </section>

    <section class="admin-card">
      <h2>Blocked Users</h2>

      <?php if ($blockedResult && $blockedResult->num_rows > 0) { ?>
      <table class="admin-table">
        <tr>
          <th>Name</th>
          <th>Email</th>
        </tr>

        <?php while ($row = $blockedResult->fetch_assoc()) { ?>
        <tr>
          <td><?php echo htmlspecialchars($row['firstName'] . " " . $row['lastName']); ?></td>
          <td><?php echo htmlspecialchars($row['emailAddress']); ?></td>
        </tr>
        <?php } ?>
      </table>
      <?php } else { ?>
        <p>No blocked users.</p>
      <?php } ?>
    </section>

  </main>

  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-col">
        <h4>Services</h4>
        <p>Healthy Recipes</p>
        <p>Quick Meals</p>
        <p>Balanced Plates</p>
      </div>

      <div class="footer-col">
        <h4>Locations</h4>
        <p>Riyadh</p>
        <p>Jeddah</p>
        <p>Dammam</p>
      </div>

      <div class="footer-col">
        <h4>Contact Us</h4>
        <p>+966 5X XXX XXXX</p>
        <p>herbite@email.com</p>
        <p>@HerBite</p>
      </div>
    </div>

    <div class="footer-bottom">
      © 2026 HerBite. All rights reserved.
    </div>
  </footer>

</div>
</body>
</html>