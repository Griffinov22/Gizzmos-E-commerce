<?php

require_once "../models/product.php";
session_start();
include '../conn/openDb.php';

$postData = json_decode(file_get_contents('php://input'), true);

if (empty($postData) || 
    !isset($postData['productId']) ||
    !isset($postData['targetAmount']) ||
    !isset($_SESSION['loggedIn']) ||
    !isset($_SESSION['userId']) ||
    !isset($_SESSION['products'])) {
    echo json_encode(['error' => true, 'reason' => 'arguments invalid']);
}

$prodId = $postData['productId'];
$amount = $postData['targetAmount'];

// update session cart
$serArr = [];
foreach ($_SESSION['products'] as $ind => &$cartProd) {
    if ($cartProd->productId == $prodId) {
        $cartProd = new Product($prodId, $amount);
    }
    // serialize session cart
    array_push($serArr, serialize($cartProd));
}

$jsonProducts = json_encode($serArr);

// update database
if ($jsonProducts) {
    $cartQuery = $db->prepare("UPDATE Carts SET Products = ? WHERE UserId = ?");
    
    $success = $cartQuery->execute([$jsonProducts, $_SESSION['userId']]);
}

// return success from mock api
if ($success) {
    echo json_encode(['success' => true, 'prodId' => $prodId, 'targetAmount' => $amount]);
} else {
    echo json_encode(['error' => true, 'reason' => 'arguments invalid']);
}

