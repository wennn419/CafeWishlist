-- db/init.sql
-- This file runs AUTOMATICALLY the first time the MySQL container starts
-- (because docker-compose mounts the ./db folder into the container's
--  /docker-entrypoint-initdb.d directory).
--
-- It creates the one table our guestbook needs, and adds two sample rows
-- so the page isn't empty the very first time students open it.

USE guestbook;

-- A table is like a spreadsheet: columns define the shape, rows are the data.
CREATE TABLE IF NOT EXISTS messages (
    id          INT AUTO_INCREMENT PRIMARY KEY,   -- unique number per message, auto-assigned
    name        VARCHAR(100) NOT NULL,            -- who wrote it (max 100 chars, required)
    message     TEXT NOT NULL,                    -- the message body (long text, required)
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- when it was posted, filled in automatically
);

-- Seed data so the table isn't empty on day one.
INSERT INTO messages (name, message) VALUES
    ('Ada', 'Welcome to the guestbook! Leave us a note.'),
    ('Linus', 'Hello from the database. This row was inserted by init.sql.');
