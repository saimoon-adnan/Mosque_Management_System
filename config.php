<?php
/*
 * config.php
 * ----------------------------------------------------------
 * Eta database er sathe connect korar file.
 * Onno shob PHP file ekhane require kore, tai shudhu ekhanei
 * password / port change korlei hobe.
 * ----------------------------------------------------------
 */

// ====== 1. Database er tothyo (ekhane apnar password likhun) ======
$host     = "localhost";
$port     = "5432";                 // PostgreSQL er default port
$dbname   = "mosque_db";            // pgAdmin e jei database banaben
$user     = "postgres";             // default user
$password = "123456";   // <-- PostgreSQL install er somoy ja diyechilen

// ====== 2. PDO diye connect kora ======
// PDO holo PHP er way database er sathe kotha bolar.
try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // error hole exception dibe
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // row gulo array hishabe pabo
        ]
    );
} catch (PDOException $ex) {
    die("Database er sathe connect hocche na: " . $ex->getMessage());
}

// e() = text ke safe kore screen e dekhay
function e($text) {
    return htmlspecialchars((string)$text);
}
