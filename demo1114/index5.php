<?php

session_start();
require_once 'auth.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

$host = 'localhost'; 
$dbname = 'golf_inventory'; 
$user = 'jcac1'; 
$pass = 'jacob';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Handle golf item search
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = '%' . $_GET['search'] . '%';  // Ensure you're using the right term for search
    $search_sql = 'SELECT item_id, item_name, manufacturer, price FROM golf_items WHERE item_name LIKE :search';
    $search_stmt = $pdo->prepare($search_sql);
    $search_stmt->execute(['search' => $search_term]);
    $search_results = $search_stmt->fetchAll();
}

// Handle form submissions (Add new item)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['item_name']) && isset($_POST['price']) && isset($_POST['manufacturer'])) {
        // Insert new entry
        $item_name = htmlspecialchars($_POST['item_name']);  // Corrected variable
        $price = htmlspecialchars($_POST['price']);          // Corrected variable
        $manufacturer = htmlspecialchars($_POST['manufacturer']); // Corrected variable
        
        // Prepare the SQL statement
        $insert_sql = 'INSERT INTO golf_items (item_name, price, manufacturer) VALUES (:item_name, :price, :manufacturer)';
        $stmt_insert = $pdo->prepare($insert_sql);
        
        // Execute the prepared statement with actual values
        if ($stmt_insert->execute([
            'item_name' => $item_name, 
            'price' => $price, 
            'manufacturer' => $manufacturer
        ])) {
            $_SESSION['message'] = "Item added successfully!";
        } else {
            $_SESSION['message'] = "Error adding item.";
        }
    }
}

// Handle item deletion
if (isset($_POST['delete_id'])) {
    $delete_id = (int) $_POST['delete_id'];
    $delete_sql = 'DELETE FROM golf_items WHERE item_id = :item_id';
    $stmt_delete = $pdo->prepare($delete_sql);
    $stmt_delete->execute(['item_id' => $delete_id]);
    $_SESSION['message'] = "Item deleted successfully!";
}

// Get all golf items for main table
$sql = 'SELECT item_id, item_name, price, manufacturer FROM golf_items';
$stmt = $pdo->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Golf Gear Inventory</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <h1 class="hero-title">Golf Gear Inventory</h1>
        <p class="hero-subtitle">"Keeping your swing in check, one item at a time!"</p>
        
        <!-- Search moved to hero section -->
        <div class="hero-search">
            <h2>Search for a Golf Item</h2>
            <form action="" method="GET" class="search-form">
                <label for="search">Search by Name:</label>
                <input type="text" id="search" name="search" required>
                <input type="submit" value="Search">
            </form>
            
            <?php if (isset($_GET['search'])): ?>
                <div class="search-results">
                    <h3>Search Results</h3>
                    <?php if ($search_results && count($search_results) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Manufacturer</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($search_results as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['item_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                                    <td><?php echo htmlspecialchars($row['manufacturer']); ?></td>
                                    <td>
                                        <form action="index5.php" method="post" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?php echo $row['item_id']; ?>">
                                            <input type="submit" value="Remove">
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No items found matching your search.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Table section with container -->
    <div class="table-container">
        <h2>All Golf Items in Inventory</h2>
        
        <!-- Display session message (success or error) -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?> <!-- Clear message after display -->
            </div>
        <?php endif; ?>

        <table class="half-width-left-align">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Manufacturer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['item_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['manufacturer']); ?></td>
                    <td>
                        <form action="index5.php" method="post" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo $row['item_id']; ?>">
                            <input type="submit" value="Remove">
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Form section with container -->
    <div class="form-container">
        <h2>Add a Golf Item</h2>
        <form action="index5.php" method="post">
            <label for="item_name">Name:</label>
            <input type="text" id="item_name" name="item_name" required>
            <br><br>
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" required>
            <br><br>
            <label for="manufacturer">Manufacturer:</label>
            <input type="text" id="manufacturer" name="manufacturer" required>
            <br><br>
            <input type="submit" value="Add Item">
        </form>
    </div>
</body>
</html>
