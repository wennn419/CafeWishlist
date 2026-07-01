<?php
// src/index.php
// =============================================================================
// THE GUESTBOOK -- main page.
//
// This single file does the "R" in CRUD (READ): it asks the database for all
// messages and draws them on the page. The form at the bottom posts to
// create.php (the "C" = CREATE). Each message has a Delete button that posts
// to delete.php (the "D" = DELETE).
//
// Big idea for today: the BROWSER (HTML/CSS/JS you already know) talks to the
// SERVER (PHP), and the SERVER talks to the DATABASE (MySQL). PHP is the
// middle layer that glues your front end to your data.
// =============================================================================

require 'db.php';   // gives us $conn (the open database connection)

// --- READ: ask the database for every message, newest first --------------
$sql = "SELECT id, name, message, created_at FROM messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Guestbook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="wrap">
        <h1>📖 Guestbook</h1>
        <p class="sub">Leave a message. It gets saved in MySQL and shown to everyone.</p>

        <!-- THE FORM (front end).
             When submitted, the browser sends the typed values to create.php.
             method="post" = send data in the request body (good for writes).
             action="create.php" = which PHP file handles the submission. -->
        <form class="card form" action="create.php" method="post">
            <label>
                Your name
                <input type="text" name="name" maxlength="100" required>
            </label>
            <label>
                Your message
                <textarea name="message" rows="3" required></textarea>
            </label>
            <button type="submit">Sign the guestbook</button>
        </form>

        <!-- THE LIST (back end output).
             PHP loops over the database rows and prints one card each. -->
        <section class="messages">
            <?php if ($result->num_rows === 0): ?>
                <p class="empty">No messages yet. Be the first!</p>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <article class="card message">
                        <header class="message-head">
                            <!-- htmlspecialchars() turns < > & " into safe text so
                                 someone can't inject HTML/JS into our page (XSS).
                                 ALWAYS escape data on the way OUT to the browser. -->
                            <strong><?= htmlspecialchars($row['name']) ?></strong>
                            <time><?= htmlspecialchars($row['created_at']) ?></time>
                        </header>
                        <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>

                        <!-- Delete button. It's a tiny form so we can POST the id. -->
                        <form action="delete.php" method="post" class="delete-form"
                              onsubmit="return confirm('Delete this message?');">
                            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                            <button type="submit" class="link-danger">Delete</button>
                        </form>
                    </article>
                <?php endwhile; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
<?php
$conn->close();   // hang up the phone -- we're done talking to MySQL
?>
