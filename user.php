<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SESSION['user_type'] != 'user') {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['user_id'];

$userQuery = "SELECT * FROM users WHERE id = $userId";
$userResult = $conn->query($userQuery);

if (!$userResult || $userResult->num_rows == 0) {
    header("Location: login.html");
    exit();
}

$user = $userResult->fetch_assoc();

$fullName = $user['firstName'] . " " . $user['lastName'];
$email = $user['emailAddress'];
$photoFileName = $user['photoFileName'];

// optional counts if recipe tables already exist
$totalRecipes = 0;
$totalLikes = 0;

$checkRecipeTable = $conn->query("SHOW TABLES LIKE 'recipe'");
$checkLikesTable = $conn->query("SHOW TABLES LIKE 'likes'");

if ($checkRecipeTable && $checkRecipeTable->num_rows > 0) {
    $recipeCountQuery = "SELECT COUNT(*) AS totalRecipes FROM recipe WHERE userID = $userId";
    $recipeCountResult = $conn->query($recipeCountQuery);
    if ($recipeCountResult && $recipeCountResult->num_rows > 0) {
        $totalRecipes = $recipeCountResult->fetch_assoc()['totalRecipes'];
    }
}

if (
    $checkRecipeTable && $checkRecipeTable->num_rows > 0 &&
    $checkLikesTable && $checkLikesTable->num_rows > 0
) {
    $likesQuery = "
        SELECT COUNT(l.userID) AS totalLikes
        FROM likes l
        INNER JOIN recipe r ON l.recipeID = r.id
        WHERE r.userID = $userId
    ";
    $likesResult = $conn->query($likesQuery);
    if ($likesResult && $likesResult->num_rows > 0) {
        $totalLikes = $likesResult->fetch_assoc()['totalLikes'];
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HerBite | User Page</title>

  <link rel="stylesheet" href="stylesheet.css" />
</head>

<body class="user-page header-admin">
  <main class="page">

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
          <div class="welcome">Welcome <span class="name"><?php echo htmlspecialchars($user['firstName']); ?></span></div>
        </div>
        <div class="logout">
          <a href="logout.php">Sign-out</a>
        </div>
      </div>
    </header>

    <div class="page-main">

      <section class="card">
        <div class="big-box">
          <div class="bar-left">
            <h2 class="bar-title">My Information</h2>
            <p><strong>Name :</strong> <?php echo htmlspecialchars($fullName); ?></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($email); ?></p>
          </div>

          <div class="bar-right">
            <div class="user-photo-box">
              <?php if ($photoFileName == "default.png") { ?>
                <img src="default.png" alt="User photo">
              <?php } else { ?>
                <img src="images/<?php echo htmlspecialchars($photoFileName); ?>" alt="User photo">
              <?php } ?>
            </div>
          </div>
        </div>
      </section>

      <section class="card">
        <div class="big-box">
          <div class="bar-left">
            <h2 class="bar-title">
              <a href="myRecipes.html">My Recipes</a>
            </h2>
            <p><strong>Total Recipes:</strong> <?php echo $totalRecipes; ?></p>
            <p><strong>Total Likes:</strong> <?php echo $totalLikes; ?></p>
          </div>
          <div class="bar-right"></div>
        </div>
      </section>

      <section class="box">
        <div class="section-head">
          <h2>All Available Recipes</h2>

          <div class="filter">
            <select>
              <option>All Categories</option>
              <option>Inner Glow</option>
              <option>Outer Glow</option>
              <option>Healthy Snacks</option>
            </select>
            <button class="btn-filter" type="button">Filter</button>
          </div>
        </div>

        <table class="recipes-table">
          <thead>
            <tr>
              <th>Recipe Name</th>
              <th>Recipe Photo</th>
              <th>Recipe Creator</th>
              <th>Number of Likes</th>
              <th>Category</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td><a href="viewRecipes.html">Berry Yogurt Glow Bowl</a></td>
              <td><img src="berry yogurt.jpeg" class="recipe-img" alt="Berry Yogurt Glow Bowl"></td>
              <td>
                <div class="creator-cell">
                  <img src="curly-girl.png" alt="Creator photo" class="creator-avatar">
                  <span>Hanan</span>
                </div>
              </td>
              <td><span class="likes-pill">40</span></td>
              <td>Inner Glow</td>
            </tr>

            <tr>
              <td><a href="viewRecipes.html">Iron Boost Spinach Salad</a></td>
              <td><img src="spinach salad.jpeg" class="recipe-img" alt="Iron Boost Spinach Salad"></td>
              <td>
                <div class="creator-cell">
                  <img src="blonde-girl.png" alt="Creator photo" class="creator-avatar">
                  <span>Reem</span>
                </div>
              </td>
              <td><span class="likes-pill">22</span></td>
              <td>Inner Glow</td>
            </tr>

            <tr>
              <td><a href="viewRecipes.html">Avocado Glow Toast</a></td>
              <td><img src="avocado toast.jpeg" class="recipe-img" alt="Avocado Glow Toast"></td>
              <td>
                <div class="creator-cell">
                  <img src="curly-girl.png" alt="Creator photo" class="creator-avatar">
                  <span>Hanan</span>
                </div>
              </td>
              <td><span class="likes-pill">61</span></td>
              <td>Outer Glow</td>
            </tr>

            <tr>
              <td><a href="viewRecipes.html">Date Cocoa Energy Bites</a></td>
              <td><img src="date bites.jpeg" class="recipe-img" alt="Date Cocoa Energy Bites"></td>
              <td>
                <div class="creator-cell">
                  <img src="curly-girl.png" alt="Creator photo" class="creator-avatar">
                  <span>Hanan</span>
                </div>
              </td>
              <td><span class="likes-pill">29</span></td>
              <td>Outer Glow</td>
            </tr>

            <tr>
              <td><a href="viewRecipes.html">Skin Boost Nut Mix</a></td>
              <td><img src="nut mix.jpeg" class="recipe-img" alt="Skin Boost Nut Mix"></td>
              <td>
                <div class="creator-cell">
                  <img src="blonde-girl.png" alt="Creator photo" class="creator-avatar">
                  <span>Reem</span>
                </div>
              </td>
              <td><span class="likes-pill">25</span></td>
              <td>Outer Glow</td>
            </tr>

            <tr>
              <td><a href="viewRecipes.html">Banana Oat Cookies</a></td>
              <td><img src="banana oat cookies.jpeg" class="recipe-img" alt="Banana Oat Cookies"></td>
              <td>
                <div class="creator-cell">
                  <img src="curly-girl.png" alt="Creator photo" class="creator-avatar">
                  <span>Hanan</span>
                </div>
              </td>
              <td><span class="likes-pill">18</span></td>
              <td>Healthy Snacks</td>
            </tr>

            <tr>
              <td><a href="viewRecipes.html">Peanut Butter Energy Balls</a></td>
              <td><img src="peanut butter balls.jpeg" class="recipe-img" alt="Peanut Butter Energy Balls"></td>
              <td>
                <div class="creator-cell">
                  <img src="blonde-girl.png" alt="Creator photo" class="creator-avatar">
                  <span>Reem</span>
                </div>
              </td>
              <td><span class="likes-pill">15</span></td>
              <td>Healthy Snacks</td>
            </tr>

            <tr>
              <td><a href="viewRecipes.html">Apple Cinnamon Snack Bites</a></td>
              <td><img src="apple cinnamon.jpeg" class="recipe-img" alt="Apple Cinnamon Snack Bites"></td>
              <td>
                <div class="creator-cell">
                  <img src="curly-girl.png" alt="Creator photo" class="creator-avatar">
                  <span>Hanan</span>
                </div>
              </td>
              <td><span class="likes-pill">20</span></td>
              <td>Healthy Snacks</td>
            </tr>
          </tbody>
        </table>
      </section>

      <section class="box">
        <h2>My Favourite Recipes ♥</h2>

        <table class="favorites-table">
          <thead>
            <tr>
              <th>Recipe Name</th>
              <th>Recipe Photo</th>
              <th></th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td><a href="viewRecipes.html">Avocado Glow Toast</a></td>
              <td><img src="avocado toast.jpeg" class="recipe-img" alt="Avocado Glow Toast"></td>
              <td><a class="remove-link" href="user.php">Remove</a></td>
            </tr>

            <tr>
              <td><a href="viewRecipes.html">Date Cocoa Energy Bites</a></td>
              <td><img src="date bites.jpeg" class="recipe-img" alt="Date Cocoa Energy Bites"></td>
              <td><a class="remove-link" href="user.php">Remove</a></td>
            </tr>

            <tr>
              <td><a href="viewRecipes.html">Banana Oat Cookies</a></td>
              <td><img src="banana oat cookies.jpeg" class="recipe-img" alt="Banana Oat Cookies"></td>
              <td><a class="remove-link" href="user.php">Remove</a></td>
            </tr>
          </tbody>
        </table>
      </section>

    </div>
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

</body>
</html>


