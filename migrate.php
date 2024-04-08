<?php

require './vendor/autoload.php';


use App\Utils;

$dbHost = Utils::env('DB_HOST', 'localhost');
$dbUser = Utils::env('DB_USER', 'root');
$dbName = Utils::env('DB_NAME', 'webbylab');
$dbPass = Utils::env('DB_PASSWORD', '');



// Database connection settings
$dsn = "mysql:host=$dbHost;dbname=$dbName";
$username = $dbUser;
$password = $dbPass;



try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Connection failed: " . $e->getMessage());
}

/// SQL query to create a table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS formats (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS actors (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS movies (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    year INT(4) NOT NULL,
    format_id INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT(11) NOT NULL,
    FOREIGN KEY (format_id) REFERENCES formats(id),
    FOREIGN KEY (created_by) REFERENCES users(id)

);

CREATE TABLE IF NOT EXISTS movie_actors (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    movie_id INT(11) NOT NULL,
    actor_id INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (movie_id) REFERENCES movies(id),
    FOREIGN KEY (actor_id) REFERENCES actors(id)
); 

INSERT INTO `users` (`id`, `username`, `email`, `password`, `remember_token`, `created_at`) VALUES
(1, 'manager', 'manager@wbl.com', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, '2024-04-03 14:00:23');

INSERT INTO `formats` (`id`, `title`, `created_at`) VALUES
(1, 'VHS', '2024-04-03 13:58:34'),
(2, 'DVD', '2024-04-03 13:58:34'),
(3, 'Blu-ray', '2024-04-03 13:58:34');

INSERT INTO `actors` (`id`, `name`, `created_at`) VALUES
(1, 'Leonardo DiCaprio', '2024-04-04 14:38:27'),
(2, 'Kate Winslet', '2024-04-04 14:38:27'),
(3, 'Billy Zane', '2024-04-04 14:38:27'),
(4, 'Arnold Schwarzenegger', '2024-04-04 14:39:08'),
(5, 'Michael Biehn', '2024-04-04 14:39:08');

INSERT INTO `movies` (`id`, `title`, `year`, `format_id`, `created_at`, `created_by`) VALUES
(1, 'Titanic', 1997, 2, '2024-04-04 14:38:27', 1),
(2, 'The Terminator', 1984, 1, '2024-04-04 14:39:08', 1);

INSERT INTO `movie_actors` ( `id`,`movie_id`, `actor_id`, `created_at`) VALUES
(1, 1, 1, '2024-04-04 14:38:27'),
(2, 1, 2, '2024-04-04 14:38:27'),
(3, 1, 3, '2024-04-04 14:38:27'),
(4, 2, 4, '2024-04-04 14:39:08'),
(5, 2, 5, '2024-04-04 14:39:08');

";

// Executing the SQL query
try {
    $pdo->exec($sql);
    echo "Migration completed successfully.";
} catch (PDOException $e) {
    die("Error creating table: " . $e->getMessage());
}