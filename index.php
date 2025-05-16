<?php
include 'db.php';

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $sql = "SELECT * FROM contacts 
            WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?
            ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $like = "%$search%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM contacts ORDER BY created_at DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Book - Home</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h1>Contact Book</h1>

    <a href="add.php" class="btn">+ Add New Contact</a>

    <!-- Search Bar -->
    <form method="GET" action="index.php" style="margin: 20px 0;">
      <input type="text" name="search" placeholder="Search by name, email, or phone" value="<?php echo htmlspecialchars($search); ?>" style="padding: 8px; width: 70%; max-width: 400px;">
      <button type="submit" class="btn">Search</button>
      <a href="index.php" class="btn btn-secondary">Reset</a>
    </form>

    <!-- Contact Table -->
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo htmlspecialchars($row['phone']); ?></td>
              <td><?php echo $row['created_at']; ?></td>
              <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
<a href="delete.php?id=<?php echo $row['id']; ?>" 
   class="btn btn-delete"
   onclick="confirmDelete(event, 'delete.php?id=<?php echo $row['id']; ?>');">
   Delete
</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5">No contacts found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <script src="script.js"></script>
</body>
</html>

<?php $conn->close(); ?>
