<?php
include 'db.php';

// Check if ID is passed
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        // Redirect to index after deletion
        header("Location: index.php");
        exit;
    } else {
        echo "Error deleting contact: " . $conn->error;
    }
    $stmt->close();
} else {
    // Invalid or missing ID
    header("Location: index.php");
    exit;
}

$conn->close();
?>
