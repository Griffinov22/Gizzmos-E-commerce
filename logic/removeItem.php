<?php

require_once "../models/product.php";
session_start();
include '../conn/openDb.php';

$postData = json_decode(file_get_contents('php://input'), true);

if (empty($postData) || 
    !isset($postData['productId']) ||
    !isset($_SESSION['loggedIn']) ||
    !isset($_SESSION['userId']) ||
    !isset($_SESSION['products']) ||
    !is_numeric($postData['productId'])) {
    echo json_encode(['error' => true, 'reason' => 'arguments invalid']);
}

$prodId = (int)$postData['productId'];

// update session cart
$_SESSION['products'] = array_filter($_SESSION['products'], function ($prod) use ($prodId) {
    return $prod->productId != $prodId;
});

// reindex the array
$_SESSION['products'] = array_values($_SESSION['products']);

$serArr = [];
foreach ($_SESSION['products'] as $ind => $cartProd) {
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
    echo json_encode(['success' => true, 'prodId' => $prodId]);
} else {
    echo json_encode(['error' => true, 'reason' => 'arguments invalid']);
}

