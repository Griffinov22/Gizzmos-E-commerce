<?php

header("Content-Type: application/json");
$error = ['error' => 'There was an error processing your request'];
$success = ['success' => 'Product was successfully deleted'];

if (empty($_POST)) {
    echo json_encode($error);
    exit();
}

$productId = $_POST['productId'];
$origin = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) == "/admin/admin-panel.php";





if (!empty($productId) && is_numeric($productId) && $origin) {
    include "../conn/openDb.php";
    $isSuccess = $db->prepare("DELETE FROM Products WHERE ProductId = ?")->execute([$productId]);

    echo $isSuccess ? json_encode($success) : json_encode($error);
} else {
    echo json_encode($error);
}
