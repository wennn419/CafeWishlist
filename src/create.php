<?php
// src/create.php
// =============================================================================
// CREATE (the "C" in CRUD).
// The form in index.php POSTs here. We read the submitted values, save them to
// the database, then send the browser back to the list.
// =============================================================================

require 'db.php';

// Only accept POST. If someone visits this URL directly (a GET), bounce them home.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// $_POST is an array PHP fills with the form fields the browser sent.
// The keys ('name', 'message') match the name="" attributes in the HTML form.
$name    = trim($_POST['name'] ?? '');
$message = trim($_POST['message'] ?? '');

// Basic validation: don't save empty rows.
if ($name === '' || $message === '') {
    header('Location: index.php');
    exit;
}

// --- INSERT using a PREPARED STATEMENT ---------------------------------------
// Why not just glue the text into the SQL string? Because a visitor could type
//   '); DROP TABLE messages; --   and wreck our database (SQL injection).
// A prepared statement keeps the QUERY and the DATA separate, so user input is
// always treated as plain data, never as commands. This is the #1 habit to learn.
$stmt = $conn->prepare("INSERT INTO messages (name, message) VALUES (?, ?)");

// "ss" = the two ? placeholders are both strings. Then we bind our variables.
$stmt->bind_param("ss", $name, $message);
$stmt->execute();
$stmt->close();
$conn->close();

// Redirect back to the guestbook so a page refresh doesn't re-submit the form.
header('Location: index.php');
exit;
