<?php
include 'db.php';

// Check if ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$name = $email = $phone = "";
$name_err = "";

// Fetch existing contact
$stmt = $conn->prepare("SELECT * FROM contacts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Contact not found.";
    exit;
}

$contact = $result->fetch_assoc();
$name = $contact['name'];
$email = $contact['email'];
$phone = $contact['phone'];
$stmt->close();

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter a name.";
    } else {
        $name = trim($_POST["name"]);
    }

    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);

    if (empty($name_err)) {
        $stmt = $conn->prepare("UPDATE contacts SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Update failed: " . $conn->error;
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
  <title>Edit Contact - Contact Book</title>
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
  <div class="container">
    <h1>Edit Contact</h1>
    <form action="edit.php?id=<?php echo $id; ?>" method="POST" novalidate>
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

      <button type="submit" class="btn">Update Contact</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>

<?php $conn->close(); ?>
