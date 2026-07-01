<?php
// src/delete.php
// =============================================================================
// DELETE (the "D" in CRUD).
// The little Delete form on each message POSTs the message id here.
// =============================================================================

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Cast to int as a first line of defense -- an id should only ever be a number.
$id = (int)($_POST['id'] ?? 0);

if ($id > 0) {
    // Prepared statement again: the id comes from the user, so treat it as data.
    // "i" = the placeholder is an integer.
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header('Location: index.php');
exit;
