<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "users";

// run try, and if no errors just skip catch
try {
    // PDO class instance - to conmnect to MySql (and many other) database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);

    // error handling - set to PDO::ERRMODE_EXCEPTION (any database-related error will cause PDO to throw PDDException (that catches and hamdles try-catch block). 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // catch errors (exceptions)
    die("Connection failed: " . $e->getMessage());
}
