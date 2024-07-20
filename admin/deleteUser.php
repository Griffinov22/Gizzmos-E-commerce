<?php

header("Content-Type: application/json");
$error = ['error' => 'There was an error processing your request'];
$success = ['success' => 'User was successfully deleted'];

if (empty($_POST)) {
    echo json_encode($error);
    exit();
}

$userId = $_POST['userId'];
$origin = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) == "/admin/admin-panel.php";





if (!empty($userId) && is_numeric($userId) && $origin) {
    include "../conn/openDb.php";
    $isSuccess = $db->prepare("DELETE FROM Users WHERE UserId = ?")->execute([$userId]);

    echo $isSuccess ? json_encode($success) : json_encode($error);
} else {
    echo json_encode($error);
}
