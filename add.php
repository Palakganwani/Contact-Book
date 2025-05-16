<?php
include 'db.php';

$name = $email = $phone = "";
$name_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name (required)
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter a name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Get email and phone (optional)
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);

    // If no errors, insert into DB
    if (empty($name_err)) {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $phone);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Contact - Contact Book</title>
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
  <div class="container">
    <h1>Add New Contact</h1>
    <form action="add.php" method="POST" novalidate>
      <div class="form-group">
        <label for="name">Name <span style="color:red">*</span></label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required />
        <small class="error"><?php echo $name_err; ?></small>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" />
      </div>

      <div class="form-group">
        <label for="phone">Phone</label>
        <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>" />
      </div>

      <button type="submit" class="btn">Add Contact</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>

<?php $conn->close(); ?>
