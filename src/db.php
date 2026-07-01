<?php
// src/db.php
// -----------------------------------------------------------------------------
// One job: open a connection to MySQL and hand it back.
// Every other PHP page that needs the database will do:  require 'db.php';
// Keeping this in one file means if the password ever changes, we edit it ONCE.
// -----------------------------------------------------------------------------

// These values match what we set in docker-compose.yml for the "db" service.
// NOTE the host is "db" -- NOT "localhost". Inside Docker, each container is
// reachable by its service name. Our web container finds MySQL at the name "db".
$host = 'db';
$user = 'appuser';
$pass = 'apppass';
$name = 'guestbook';

// mysqli is the built-in PHP extension for talking to MySQL.
// This line dials the phone and connects.
$conn = new mysqli($host, $user, $pass, $name);

// Always check the call connected. If not, stop everything and show why.
// (In a real app you'd log this, not print it -- but for learning, show it.)
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Make sure text with emojis / accents is stored correctly.
$conn->set_charset('utf8mb4');
