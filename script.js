// Confirm before deleting a contact
function confirmDelete(event, url) {
  if (confirm("Are you sure you want to delete this contact?")) {
    window.location.href = url;
  } else {
    event.preventDefault(); // Stop the link from working
  }
}
