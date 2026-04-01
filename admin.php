<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit();
}

$adminId = $_SESSION['user_id'];

$adminQuery = "SELECT * FROM users WHERE id = $adminId";
$adminResult = $conn->query($adminQuery);

if (!$adminResult || $adminResult->num_rows == 0) {
    header("Location: login.html");
    exit();
}

$admin = $adminResult->fetch_assoc();
$fullName = $admin['firstName'] . " " . $admin['lastName'];
$email = $admin['emailAddress'];

$blockedUsers = [];
$blockedQuery = "SELECT * FROM blockeduser";
$blockedResult = $conn->query($blockedQuery);

if ($blockedResult) {
    while ($row = $blockedResult->fetch_assoc()) {
        $blockedUsers[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin | HerBite</title>

  <link rel="stylesheet" href="stylesheet.css">
</head>
<body class="admin-page">
<div class="page">

  <!-- HEADER -->
  <header class="site-header">
    <div class="header-inner">
      <a href="index.html" class="home-link" aria-label="Go to home">
        <img src="home.PNG" alt="Home">
      </a>

      <div class="brand">
        <img src="logo.jpg" alt="HerBite Logo">
      </div>

      <div class="brand-title">
        <img src="title.jpg" alt="HerBite Title">
      </div>

      <div class="header-right">
        <div class="welcome">Welcome <span class="name"><?php echo htmlspecialchars($admin['firstName']); ?></span></div>
      </div>

      <div class="logout">
        <a href="logout.php">Sign-out</a>
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="page-main">

    <!-- My Information -->
    <section class="admin-card admin-info-card">
      <div class="admin-big-box">
        <h2>My Information</h2>
        <div class="admin-info">
          <div class="label">Name</div>
          <div class="value"><?php echo htmlspecialchars($fullName); ?></div>

          <div class="label">Email address</div>
          <div class="value"><?php echo htmlspecialchars($email); ?></div>
        </div>
      </div>
    </section>

    <!-- Reported Recipes -->
    <section class="admin-card">
      <h2>Reported Recipes</h2>

      <table class="admin-table">
        <thead>
          <tr>
            <th>Recipe Name</th>
            <th>Recipe Creator</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>
              <a href="viewRecipes.html">Greek Yoghurt Bowl</a>
            </td>

            <td>
              <div class="creator-cell">
                <img src="curly-girl.png" alt="Creator photo" class="creator-avatar square">
                <span class="name">Hanan</span>
              </div>
            </td>

            <td>
              <form class="action-form" action="#" method="get">
                <label>
                  <input type="radio" name="r1"> Block User
                </label>
                <label>
                  <input type="radio" name="r1"> Dismiss Report
                </label>
                <button type="submit" class="btn-small">Submit</button>
              </form>
            </td>
          </tr>

          <tr>
            <td>
              <a href="viewRecipes.html">Avocado Glow Toast</a>
            </td>

            <td>
              <div class="creator-cell">
                <img src="blonde-girl.png" alt="Creator photo" class="creator-avatar square">
                <span class="name">Reem</span>
              </div>
            </td>

            <td>
              <form class="action-form" action="#" method="get">
                <label>
                  <input type="radio" name="r2"> Block User
                </label>
                <label>
                  <input type="radio" name="r2"> Dismiss Report
                </label>
                <button type="submit" class="btn-small">Submit</button>
              </form>
            </td>
          </tr>
        </tbody>
      </table>
    </section>

    <!-- Blocked Users -->
    <section class="admin-card">
      <h2>Blocked Users List</h2>

      <table class="admin-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email Address</th>
          </tr>
        </thead>

        <tbody>
          <?php if (count($blockedUsers) > 0) { ?>
            <?php foreach ($blockedUsers as $blocked) { ?>
              <tr>
                <td><?php echo htmlspecialchars($blocked['firstName']); ?></td>
                <td><?php echo htmlspecialchars($blocked['emailAddress']); ?></td>
              </tr>
            <?php } ?>
          <?php } else { ?>
            <tr>
              <td colspan="2">No blocked users.</td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </section>

  </main>

  <!-- FOOTER -->
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