<?php
session_start();

header("Content-Type: application/json");
$error = ['error' => 'There was an error processing your request'];
$success = ['success' => 'User was successfully deleted'];

if (empty($_POST)) {
    echo json_encode($error);
    exit();
}

$userId = $_POST['userId'];
$origin = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) == "/admin/admin-panel.php";

$notCurrentUser = $userId != $_SESSION['userId'];



if (!empty($userId) && is_numeric($userId) && $origin && $notCurrentUser) {
    include "../conn/openDb.php";
    //prevent deletion of current signed in user
    $isSuccess = $db->prepare("DELETE FROM Users WHERE UserId = ?")->execute([$userId]);
    
    echo $isSuccess ? json_encode($success) : json_encode($error);
} else {
    echo json_encode($error);
}
