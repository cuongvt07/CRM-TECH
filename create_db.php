<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $pdo->exec('CREATE DATABASE IF NOT EXISTS `CRM`');
    echo "DB_CREATED";
} catch (Exception $e) {
    echo $e->getMessage();
}
