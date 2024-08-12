<?php
require_once '../models/product.php';
session_start();
include '../conn/openDb.php';

if (!$_SESSION['loggedIn'] ||
 sizeof($_SESSION['products']) == 0 ||
 !isset($_SESSION['userId']) ||
 $_SERVER['REQUEST_METHOD'] != 'GET' ||
 $_GET['csrf_token'] != $_SESSION['csrf_token']) {
    header('Location: ../index.php');
    exit();
 }

$json = json_encode([]);

$query = $db->prepare("UPDATE Carts SET Products = ? WHERE UserId = ?");

$success = $query->execute([$json, (int)$_SESSION['userId']]);

if ($success) {
    $_SESSION['products'] = [];

    header('Location: ../cart.php');
    exit();
} else {
    // IDK
    header('Location: ../cart.php');
    exit();
}