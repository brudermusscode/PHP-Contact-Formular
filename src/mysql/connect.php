<?php

try {

    $dsn = 'mysql:dbname=test-justin;host=mysql';
    $user = 'root';
    $password = 'secret';

    $pdo = new PDO($dsn, $user, $password);

    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);

    return $pdo;
} catch (PDOException $pdoerror) {

    echo $pdoerror->getMessage();
}
