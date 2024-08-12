<?php
require_once '../models/product.php';
session_start();

if (empty($_GET) ||
 !isset($_GET['productId']) ||
 !isset($_GET['amount']) ||
 !isset($_SESSION['products']) ||
 !isset($_SESSION['userId']) ||
 !isset($_SESSION['loggedIn']) ||
 $_GET['csrf_token'] != $_SESSION['csrf_token']) {
    header('Location: ../index.php');
    exit();
}

if (!is_numeric($_GET['productId']) || !is_numeric($_GET['amount'])) {
    header('Location: ../index.php');
    exit();
}

$inCart = false;
foreach ($_SESSION['products'] as $products) {
    if ($products->productId == $_GET['productId'])
        $inCart = true;
}

if ($inCart) {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}

include '../conn/openDb.php';

// validate real item
$prodQuery = $db->prepare("SELECT ProductId FROM Products WHERE ProductId = ?");
$prodQuery->execute([$_GET['productId']]);
$res = $prodQuery->fetch(PDO::FETCH_ASSOC);

if (!$res) {
    header('Location: ../index.php');
    exit();
}

$product = new Product($_GET['productId'], (int)$_GET['amount']);

// add productId to session cart and to db record
array_push($_SESSION['products'], $product);

$serArr = [];
foreach($_SESSION['products'] as $prod) {
    array_push($serArr, serialize($prod));
}

$jsonProducts = json_encode($serArr);

$success = false;
if ($jsonProducts) {
    $cartQuery = $db->prepare("UPDATE Carts SET Products = ? WHERE UserId = ?");
    
    $success = $cartQuery->execute([$jsonProducts, $_SESSION['userId']]);
}


if ($success) {
    header('Location: ../cart.php');
    exit();
} else {
    // destroy session and return to index
    header('../logout.php');
    exit();
}