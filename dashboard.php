<?php
session_start();
include('db_connect.php');
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>📚 Dashboard - E-Library</title>
<link rel="stylesheet" href="style.css">
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  .category-section {
    margin-bottom: 35px;
  }
  .category-title {
    color: #facc15;
    font-size: 1.3em;
    font-weight: bold;
    margin: 20px 0 10px;
    border-left: 5px solid #facc15;
    padding-left: 8px;
  }
  .book-card {
    background: rgba(255,255,255,0.08);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.25);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
  .book-card:hover {
    transform: translateY(-4px);
  }
  .book-card img {
    width: 100%;
    height: 130px; /* Pinaliit ang image height */
    object-fit: cover;
    border-bottom: 2px solid rgba(255,255,255,0.1);
  }
  .book-info {
    padding: 8px;
  }
  .book-info h3 {
    color: #facc15;
    font-size: 0.9em;
    margin-bottom: 3px;
  }
  .book-info p {
    color: #f1f5f9;
    font-size: 0.75em;
    margin: 2px 0;
  }
  .cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); /* Mas maliit ang lapad ng cards */
    gap: 10px;
  }
  .container {
    padding: 20px;
  }
</style>
</head>

<body class="bg-advanced">
  <nav class="topnav">
    <div class="brand">📚 E-Library</div>
    <div class="nav-right">
      <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
      <a href="books.php">Books</a>
      <a href="borrow.php">Borrow</a>
      <a href="return.php">Return</a>
      <?php if($_SESSION['role'] === 'Librarian') echo '<a href="activity_log.php">Activity</a>'; ?>
      <a href="logout.php">Logout</a>
    </div>
  </nav>

  <main class="container">
    <h2>📖 Books by Category</h2>
    <?php
      // Kunin lahat ng categories
      $categories = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");
      while ($cat = $categories->fetch_assoc()) {
        echo "<section class='category-section'>";
        echo "<h3 class='category-title'>" . htmlspecialchars($cat['category_name']) . "</h3>";
        
        // Kunin ang books per category
        $cid = (int)$cat['category_id'];
        $books = $conn->query("SELECT * FROM books WHERE category_id=$cid");
        
        if ($books->num_rows > 0) {
          echo "<div class='cards'>";
          while ($b = $books->fetch_assoc()) {
            $cover = htmlspecialchars($b['cover_url'] ?: 'https://via.placeholder.com/250x350?text=No+Cover');
            echo "
              <div class='book-card'>
                <img src='$cover' alt='Book Cover'>
                <div class='book-info'>
                  <h3>".htmlspecialchars($b['title'])."</h3>
                  <p><strong>Author:</strong> ".htmlspecialchars($b['author'])."</p>
                  <p><strong>Available:</strong> ".(int)$b['available_copies']."</p>
                </div>
              </div>
            ";
          }
          echo "</div>";
        } else {
          echo "<p style='color:#ccc; font-size:0.85em;'>No books available in this category.</p>";
        }
        
        echo "</section>";
      }
    ?>
  </main>
</body>
</html>