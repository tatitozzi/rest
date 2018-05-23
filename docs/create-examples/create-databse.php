<?php

try {
    $dbh = new PDO('mysql:host=localhost', 'root', '', [
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    ]);

    $dbh->exec( file_get_contents(__DIR__ . '/create-databse.sql') )
        or die(print_r($dbh->errorInfo(), true));
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
