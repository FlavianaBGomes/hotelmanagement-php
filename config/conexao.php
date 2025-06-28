<?php

try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=hotel_management", "root", "12345");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8");
} catch (PDOException $e) {
    die("Erro ao conectar com o banco! " . $e->getMessage());
}

